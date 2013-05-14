<?php

class CS_Visitor {

  protected static $_token = false;
  protected static $_access_list = false;

  public function __construct() {
    //http://cloudswipe.hopto.me:8888/bose-quietcomfort-headphones/?cs_customer_token=074ecba0939997af98ca37645557391b854b67515ae94769&cs_customer_first_name=Lee
    $this->load_token();
    $this->load_access_list();
  }

  public function set_access_list(array $list) {
    self::$_access_list = $list;
  }

  public function load_access_list() {
    if(!is_array(self::$_access_list)) {
      $token = $this->get_token();
      $lib = new CS_Library();
      $access_list = $lib->get_expiring_orders($token);
      $access_list = is_array($access_list) ? $access_list : array();
      $this->set_access_list($access_list);
    }
  }

  public function drop_access_list() {
    self::$_access_list = false;
  }

  /**
   * Return an array of std objects that hold membership skus and days_in values
   *
   * If visitor is not logged in or has no memberships an empty array is returned
   *
   * Array (
   *   [0] => stdClass Object
   *     (
   *       [sku] => basic
   *       [days_in] => 0
   *     )
   * )
   *
   * @return array
   */
  public function get_access_list() {
    $list = is_array(self::$_access_list) ? self::$_access_list : array();
    return $list;
  }

  public function load_token() {
    self::$_token = false;
    if(isset($_COOKIE['csm_token'])) {
      self::$_token = $_COOKIE['csm_token'];
    }
  }

  public function check_remote_login() {
    // CS_Log::write("Checking for remote login");
    if(isset($_GET['cs_customer_token']) && isset($_GET['cs_customer_first_name'])) {
      $token = CS_Common::scrub('cs_customer_token', $_GET);
      $name = CS_Common::scrub('cs_customer_first_name', $_GET);
      $this->log_in($token, $name);
    }
  }

  public function log_in($token, $name) {
    $expire = time() + 60*60*24*30; // Expire in 30 days
    $data = $token . '~' . $name;
    $_COOKIE['csm_token'] = $data;
    setcookie('csm_token', $data, $expire, COOKIEPATH, COOKIE_DOMAIN, false, true);
    if (COOKIEPATH != SITECOOKIEPATH) {
      setcookie('csm_token', $data, $expire, SITECOOKIEPATH, COOKIE_DOMAIN, false, true);
      CS_Log::write("Logging in CS Member: $data");
    }
  }

  /**
   * Remove the member token cookie and set the token to false.
   */
  public function log_out() {
    self::$_token = false;
    unset($_COOKIE['csm_token']);
	  setcookie('csm_token', ' ', time() - 3600, COOKIEPATH);
    if (COOKIEPATH != SITECOOKIEPATH) {
      setcookie('csm_token', ' ', time() - 3600, SITECOOKIEPATH, COOKIE_DOMAIN, false, true);
    }
  }

  /**
   * Return true if the visitor has a valid member token, otherwise false.
   * 
   * @return boolean
   */
  public function is_logged_in() {
    return $this->get_token() ? true : false;
  }

  /**
   * Return the member access token, member name, or both values for the logged in visitor.
   *
   * If the visitor is not logged in or does not have a token return
   * an empty string. Unless otherwise specified by the $type parameter, 
   * the member access token is returned.
   *
   * @param string $type [full, token, name]
   * @return string
   */
  public function get_token($type='token') {
    $allowed = array('full', 'token', 'name');
    if(!in_array($type, $allowed)) {
      throw new CS_Exception("Invalid token type requested: $type");
    }

    $data = '';
    if(self::$_token) {
      list($token, $name) = explode('~', self::$_token);
      $data = array(
        'full' => self::$_token,
        'token' => $token,
        'name' => $name
      );
      $data = $data[$type];
    }

    return $data;
  }

  /**
   * Return true if the visitor should be allowed to see the link in the navigation
   *
   * @return boolean
   */
  public function can_view_link($post_id) {
    $view = true;
    $memberships = get_post_meta($post_id, 'csm_required_memberships', true);
    $override = ($this->is_logged_in()) ? get_post_meta($post_id, 'csm_when_logged_in', true) : get_post_meta($post_id, 'csm_when_logged_out', true);
     
    if($override == 'show') {
      $view = true;
    }
    elseif($override == 'hide') {
      $view = false;
    }
    elseif(is_array($memberships) && count($memberships)) {
      if($this->can_view_post($post_id)) {
        $view = true;
      }
      else {
        // CS_Log::write('View false because a membership is required and may not view post :: ' . print_r($memberships, true));
        $view = false;
      }
    }
    else {
      $view = true;
    }

    return $view;
  }

  /**
   * Return true if the visitor is allowed to view the post with the given id.
   *
   * This function always returns false if the visitor is not logged in.
   *
   * @param int The post id
   * @return boolean
   */
  public function can_view_post($post_id) {
    $allow = true;
    $memberships = get_post_meta($post_id, 'csm_required_memberships', true);

    if(is_array($memberships) && count($memberships)) {
      // CS_Log::write('This post requires memberships: ' . print_r($memberships, true));
      $allow = false; // only grant permission to logged in visitors with active subscriptions
      if($this->is_logged_in()) {
        $days_in = get_post_meta($post_id, 'csm_days_in', false);
        if($this->has_permission($memberships, $days_in)) {
          CS_Log::write('This visitor has permission to view this post:' . $post_id);
          $allow = true;
        }
      }
    }

    return $allow;
  }

  /**
   * Return true if one of the given memberships is in the access list and at least $days_in days old
   *
   * @param array $memberships An array of one or more membership SKUs
   * @param int $days_in The number of days a membership must be active before access is granted
   * @return boolean
   */
  public function has_permission(array $memberships, $days_in) {
    $access_list = $this->get_access_list();
    foreach($memberships as $sku) {
      foreach($access_list as $item) {
        if($sku == $item->sku && $days_in >= $item->days_in) {
          CS_Log::write("Permission ok: $sku :: Days in: $days_in :: " . $item->days_in);
          return true;
        }
      }
    }
    return false;
  }

}

