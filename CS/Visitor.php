<?php

class CS_Visitor {

  protected $_token = false;

  public function __construct() {
    // TODO: load the token from the cookie
    // Even chars = logged in and can access stuff
    // Odd chars = logged in and cannot access stuff
    // No chars = not logged in
    $this->_token = '';
  }

  public function log_in($token) {
    $expire = time() + 60*60*24*30; // Expire in 30 days
    setcookie('csm_token', $token, $expire, COOKIEPATH, COOKIE_DOMAIN, false, true);
    if (COOKIEPATH != SITECOOKIEPATH) {
      setcookie('csm_token', $token, $expire, SITECOOKIEPATH, COOKIE_DOMAIN, false, true);
    }
  }

  /**
   * Remove the member token cookie and set the token to false.
   */
  public function log_out() {
    $this->_token = false;
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
    return $this->_token ? true : false;
  }


  /**
   * Return the member token for the logged in visitor.
   *
   * If the visitor is not logged in or does not have a token return
   * an empty string.
   *
   * @return string
   */
  public function get_token() {
    return $this->_token;
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

    if($post_id == 79) {
      CS_Log::write("logged out checking if can view link :: allowed $view :: post_id: $post_id :: override => $override :: " . print_r($memberships, true)); 
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
        $cs_library = new CS_Library();
        if($cs_library->has_permission($this->_token, $memberships, $days_in)) {
          CS_Log::write('This visitor has permission to view this post:' . $post_id);
          $allow = true;
        }
      }
    }

    return $allow;
  }

}

