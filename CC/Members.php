<?php

class CC_Members {

  public static function init() {
    IS_ADMIN ? self::admin_init() : self::public_init();    
  }

  public static function admin_init() {
    /*
    $csm_admin = new CC_Admin();
    add_action('admin_init', array($csm_admin, 'register_settings'), 20);
    add_action('admin_menu', array($csm_admin, 'add_members_submenu'), 20);
    add_action('add_meta_boxes', array('CC_MetaBox', 'add_memberships_box'), 20);
    add_action('save_post', array('CC_MetaBox', 'save_membership_requirements'), 20);
     */
  }

}