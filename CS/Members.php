<?php

class CS_Members {

  public static function init() {
    IS_ADMIN ? self::admin_init() : self::public_init();    
  }

  public static function admin_init() {
    $csm_admin = new CS_Admin();
    add_action('admin_init', array($csm_admin, 'register_settings'), 20);
    add_action('admin_menu', array($csm_admin, 'add_members_submenu'), 20);
    add_action('add_meta_boxes', array('CS_MetaBox', 'add_memberships_box'), 20);
    add_action('save_post', array('CS_MetaBox', 'save_membership_requirements'), 20);
  }

  public static function public_init() {
    //add_action('template_redirect', array('CS_Monitor', 'restrict_pages'));
    $monitor = new CS_Monitor();

    // Remove content from restricted pages
    add_filter('the_content', array($monitor, 'restrict_pages'));

    add_filter('the_posts', array($monitor, 'filter_posts'));

    // Filter restricted pages that are not part of nav menus
    add_filter('get_pages', array($monitor, 'filter_pages'));

    add_filter('nav_menu_css_class', array($monitor, 'filter_menus'), 10, 2);

    add_action('wp_enqueue_scripts', array($monitor, 'enqueue_css'));
    add_action('init', array('CS_ShortcodeManager', 'register_shortcodes'));
  }

}
