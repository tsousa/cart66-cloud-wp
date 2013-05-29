<?php

class CC_MetaBox {

  public static function add_memberships_box() {
    $screens = array('post', 'page');
    $screens = apply_filters('ccm_meta_box_pages', $screens);

    foreach($screens as $screen) {
      add_meta_box(
        'ccm_membership_ids',
        __('Membership Requirements', 'cart66_memberships'),
        array(__CLASS__, 'render_memberships_box'),
        $screen,
        'side'
      );
    }
  }

  public static function render_memberships_box($post) {
    $lib = new CC_Library();
    try {
      $memberships = $lib->get_expiring_products();
      CC_Log::write("Expiring products data: " . print_r($memberships, true));
    }
    catch(CC_Exception_API $e) {
      $memberships = array(
        array(
          'name' => 'Products unavailable',
          'sku' => ''
        )
      );
    }

    $requirements = get_post_meta($post->ID, 'ccm_required_memberships', true);
    $days = get_post_meta($post->ID, 'ccm_days_in', true);
    $when_logged_in = get_post_meta($post->ID, 'ccm_when_logged_in', true);
    $when_logged_out = get_post_meta($post->ID, 'ccm_when_logged_out', true);
    $post_type = get_post_type($post->ID);
    $data = array(
      'memberships' => $memberships, 
      'requirements' => $requirements, 
      'days' => $days,
      'when_logged_in' => $when_logged_in,
      'when_logged_out' => $when_logged_out,
      'post_type' => $post_type
    );
    echo CC_View::get(CC_PATH . 'views/admin/memberships_box.phtml', $data);
  }

  public function save_membership_requirements() {
    // Don't do anything during autosaves
    if(defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) { return; }

    // Don't do anythingn if the nonce cannot be verified
    if( isset($_POST['ccm_membership_ids_nonce']) && 
      !wp_verify_nonce($_POST['ccm_membership_ids_nonce'], 'ccm_save_membership_ids')) { 
      return;
    }

    if(isset($_POST['post_ID'])) {
      $post_ID = $_POST['post_ID'];
      $membership_ids = (isset($_POST['ccm_membership_ids'])) ? $_POST['ccm_membership_ids'] : array();
      $days = (isset($_POST['ccm_days_in'])) ? (int)$_POST['ccm_days_in'] : 0;
      $when_logged_in = (isset($_POST['ccm_when_logged_in'])) ? $_POST['ccm_when_logged_in'] : '';
      $when_logged_out = (isset($_POST['ccm_when_logged_out'])) ? $_POST['ccm_when_logged_out'] : '';
      update_post_meta($post_ID, 'ccm_required_memberships', $membership_ids);
      update_post_meta($post_ID, 'ccm_days_in', $days);
      update_post_meta($post_ID, 'ccm_when_logged_in', $when_logged_in);
      update_post_meta($post_ID, 'ccm_when_logged_out', $when_logged_out);
    }
  }

}
