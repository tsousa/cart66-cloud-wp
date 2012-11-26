<?php

class CS_CartWidget extends WP_Widget {

  public function __construct() {
    $widget_ops = array('classname' => 'CS_CartWidget', 'description' => 'Sidebar widget for CloudSwipe');
    $this->WP_Widget('CS_CartWidget', 'CloudSwipe Shopping Cart', $widget_ops);
  }

  /**
   * The form in the WordPress admin for configuring the widget
   */
  public function form($instance) {
    $instance = wp_parse_args($instance, array('title' => 'Your Cart'));
    $title = esc_attr($instance['title']);
    $data = array(
      'widget' => $this,
      'title' => $title
    );
    $view = CS_View::get(CS_PATH . 'views/widget/cart_admin.phtml', $data);
    echo $view;
  }

  /**
   * Process the widget options to be saved
   */
  public function update($new, $instance) {
    $instance['title'] = esc_attr($new['title']);
    return $instance;
  }

  /**
   * Render the content of the widget
   */
  public function widget($args, $instance) {
    extract($args);
    $cart_summary = CS_Cart::get_summary();
    $data = array(
      'before_title' => $before_title,
      'after_title' => $after_title,
      'before_widget' => $before_widget,
      'after_widget' => $after_widget,
      'title' => $instance['title'],
      'view_cart_url' => CS_Cart::view_cart_url(),
      'checkout_url' => CS_Cart::checkout_url(), 
      'item_count' => $cart_summary->item_count,
      'subtotal' => $cart_summary->subtotal,
      'api_ok' => $cart_summary->api_ok
    );

    $view = CS_View::get(CS_PATH . 'views/widget/cart_sidebar.phtml', $data);
    echo $view;
  }

  public function ajax_render_content() {
    CS_Log::write('[' . basename(__FILE__) . ' - line ' . __LINE__ . "] Called CartWidget::ajax_render_content");
    $cart_summary = CS_Cart::get_summary();
    $data = array(
      'view_cart_url' => CS_Cart::view_cart_url(),
      'checkout_url' => CS_Cart::checkout_url(), 
      'item_count' => $cart_summary->item_count,
      'subtotal' => $cart_summary->subtotal,
      'api_ok' => $cart_summary->api_ok
    );
    $view = CS_View::get(CS_PATH . 'views/widget/cart_sidebar_content.phtml', $data);
    echo $view;
    die();
  }

}
