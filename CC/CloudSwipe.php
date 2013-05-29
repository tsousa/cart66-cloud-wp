<?php
class CC_CloudSwipe {

  public function __construct() {
    IS_ADMIN ? $this->init_admin() : $this->init_public();

    // Handle tasks passed via query strings and post backs
    add_action('init', array('CC_TaskDispatcher', 'dispatch'));

    // Register sidebar widgets
    add_action('widgets_init', create_function('', 'return register_widget("CC_CartWidget");'));
    add_action('widgets_init', create_function('', 'return register_widget("CC_AccountWidget");'));

    // Enqueue jQuery
    add_action('wp_enqueue_scripts', array('CC_Cart', 'enqueue_jquery'));

    // Add actions for ajax add to cart
    if(get_site_option('cs_redirect_type') == 'stay_ajax') {
      add_action('wp_enqueue_scripts', array('CC_Cart', 'enqueue_ajax_add_to_cart'));
      add_action('wp_ajax_cs_ajax_add_to_cart', array('CC_Cart', 'ajax_add_to_cart'));
      add_action('wp_ajax_nopriv_cs_ajax_add_to_cart', array('CC_Cart', 'ajax_add_to_cart'));
    }
  }

  public function init_public() {
    $this->members_public_init();
    // Check for page slurp
    add_action('init', array('CC_PageSlurp', 'check'));
    add_action('init', array('CC_Cart', 'get_summary'));
    add_action('template_redirect', array('CC_Cart', 'redirect_cart_links'));
    // add_action('template_redirect', array('CC_PageSlurp', 'debug'));
    add_shortcode('cs_product', array('CC_ShortcodeManager', 'product'));
    add_shortcode('cs_product_link', array('CC_ShortcodeManager', 'product_link'));

    // Enqueue cloudswipe styles
    add_action('wp_enqueue_scripts', array('CC_Cart', 'enqueue_cloudswipe_styles'));
  }

  public function init_admin() {
    $this->members_admin_init();
    add_action('add_meta_boxes', array('CC_ProductMetaBox', 'add'));
    add_action('save_post', array('CC_ProductMetaBox', 'save'));
    add_action('admin_menu', array($this, 'attach_settings_page'));
    add_action('admin_notices', array($this, 'show_cloudswipe_account_notice'));

    // Add media button for cloudswipe shortcodes
    if(in_array(CC_CURRENT_PAGE, array('post.php', 'page.php', 'page-new.php', 'post-new.php'))) {
      add_action('media_buttons_context', array('CC_ShortcodeManager', 'add_media_button'));
      add_action('admin_footer',  array('CC_ShortcodeManager', 'add_media_button_popup'));
    }

    add_filter('plugin_action_links', array($this, 'add_settings_link'), 10, 2);
  }


  public function members_public_init() {
    // Remove content from restricted pages
    $monitor = new CC_Monitor();
    add_filter('the_content', array($monitor, 'restrict_pages'));
    add_filter('the_posts',   array($monitor, 'filter_posts'));

    // Filter restricted pages that are not part of nav menus
    add_filter('get_pages',          array($monitor, 'filter_pages'));
    add_filter('nav_menu_css_class', array($monitor, 'filter_menus'), 10, 2);
    add_action('wp_enqueue_scripts', array($monitor, 'enqueue_css'));
    
    add_action('init', array('CC_ShortcodeManager', 'register_shortcodes'));

    $visitor = new CC_Visitor();
    add_action('init', array($visitor, 'check_remote_login'));
  }

  public static function members_admin_init() {
    $cs_admin = new CC_Admin();
    add_action('admin_init', array($cs_admin, 'register_settings'), 20);
    add_action('admin_menu', array($cs_admin, 'add_members_submenu'), 20);
    add_action('add_meta_boxes', array('CC_MetaBox', 'add_memberships_box'), 20);
    add_action('save_post', array('CC_MetaBox', 'save_membership_requirements'), 20);
    add_action('init', array('CC_Members', 'init'), 20);
  }

  public function attach_settings_page() {
    $settings_page = new CC_SettingsPage();
    add_menu_page(
      __('CloudSwipe', 'cloudswipe'),
      __('CloudSwipe', 'cloudswipe'),
      'administrator',
      'cloudswipe',
      array($settings_page, 'render'),
      CC_URL . 'resources/images/icon.png'
    );
  }

  public function show_cloudswipe_account_notice() {
    if(!(get_site_option('cs_public_key') && get_site_option('cs_secret_key'))) {
      echo '<div class="updated"><p>Please <a href="http://cloudswipe.com/pricing" target="_blank">create a CloudSwipe account</a> then enter your <a href="admin.php?page=cloudswipe">CloudSwipe keys</a>.</p></div>';
    }
  }

  public function add_settings_link($links, $file) {
    $pattern = DIRECTORY_SEPARATOR . 'cloudswipe.php';
    if(strpos($file, $pattern) > 0) {
      CC_Log::write('[' . basename(__FILE__) . ' - line ' . __LINE__ . "] YES! Adding the link");
      $settings_link = '<a href="admin.php?page=cloudswipe">' . __('Settings', 'cloudswipe') . '</a>';
      array_unshift($links, $settings_link);
    }
    return $links;
  }

}