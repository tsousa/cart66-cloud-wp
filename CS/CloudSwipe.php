<?php
class CS_CloudSwipe {

  public function __construct() {
    IS_ADMIN ? $this->init_admin() : $this->init_public();

    // Handle tasks passed via query strings and post backs
    add_action('init', array('CS_TaskDispatcher', 'dispatch'));

    // Register sidebar widgets
    add_action('widgets_init', create_function('', 'return register_widget("CS_CartWidget");'));

    // Enqueu jQuery
    add_action('wp_enqueue_scripts', array('CS_Cart', 'enqueue_jquery'));
  }

  public function init_public() {
    // Check for page slurp
    add_action('init', array('CS_PageSlurp', 'check'));
    add_action('template_redirect', array('CS_Cart', 'redirect_cart_links'));
    add_shortcode('cs_product', array('CS_ShortcodeManager', 'product'));
    add_shortcode('cs_product_link', array('CS_ShortcodeManager', 'product_link'));

    // Enqueue cloudswipe styles
    add_action('wp_enqueue_scripts', array('CS_Cart', 'enqueue_cloudswipe_styles'));
  }

  public function init_admin() {
    add_action('add_meta_boxes', array('CS_ProductMetaBox', 'add'));
    add_action('save_post', array('CS_ProductMetaBox', 'save'));
    add_action('admin_menu', array($this, 'attach_settings_page'));

    // Add media button for cloudswipe shortcodes
    if(in_array(CS_CURRENT_PAGE, array('post.php', 'page.php', 'page-new.php', 'post-new.php'))) {
      add_action('media_buttons_context', array('CS_ShortcodeManager', 'add_media_button'));
      add_action('admin_footer',  array('CS_ShortcodeManager', 'add_media_button_popup'));
    }
  }

  public function attach_settings_page() {
    $settings_page = new CS_SettingsPage();
    add_submenu_page('options-general.php',
      __('CloudSwipe', 'cloudswipe'),
      __('CloudSwipe', 'cloudswipe'),
      'administrator',
      'cloudswipe_admin',
      array($settings_page, 'render')
    );
  }

}