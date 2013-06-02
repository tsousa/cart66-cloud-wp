<?php

class CC_ShortcodeManager {

  public function add_media_button($context) {
    $button = '<img src="' . CC_URL . 'resources/images/editor-icon.png" alt="' . __("Add Cart66 Cloud Product", 'cart66') . '" />';
    $button ='<a href="#TB_inline?width=480&height=600&inlineId=cc_editor_pop_up" class="thickbox" id="cc_product_shortcodes" title="' . __("Add Cart66 Product", 'cart66') . '">'.$button.'</a>';
    return $context . $button;
  }

  public static function add_media_button_popup() {
    $product_data = array();

    try {
      $lib = new CC_Library();
      $product_data = $lib->get_products();
    }
    catch(CC_Exception_API $e) {
      $product_data = CC_Common::unavailable_product_data();
      CC_Log::write('[' . basename(__FILE__) . ' - line ' . __LINE__ . "] Unable to retreive products for media button pop up: " . $e->get_message());
    }

    $data = array('product_data' => $product_data);
    $view = CC_View::get(CC_PATH . 'views/editor_pop_up.phtml', $data);
    echo $view;
  }

  public static function product($args, $content) {
    $form = '';

    if($error_message = CC_FlashData::get('api_error')) {
      $form .= "<p class=\"cc_error\">$error_message</p>";
    }

    $product_id = isset($args['id']) ? $args['id'] : false;
    $product_sku = isset($args['sku']) ? $args['sku'] : false;
    $display_quantity = isset($args['quantity']) ? $args['quantity'] : 'true';
    $display_price = isset($args['price']) ? $args['price'] : 'true';
    $display_mode = isset($args['display']) ? $args['display'] : null;

    if($form_with_errors = CC_FlashData::get($product_sku)) {
      $form .= $form_with_errors;
    }
    else {
      $product = new CC_Product();
      if($product_sku) {
        $product->sku = $product_sku;
      }
      elseif($product_id) {
        $product->id = $product_id;
      }
      else {
        throw new CC_Exception_Product('Unable to add product to cart without know the product sku or id');
      }

      try {
        $form .= $product->get_order_form($display_quantity, $display_price, $display_mode);
      }
      catch(CC_Exception_Product $e) {
        $form = "Product order form unavailable";
      }
    }

    return $form;
  }

  public static function product_link($args, $content) {
    $sku = isset($args['sku']) ? $args['sku'] : false;
    if($sku) {
      $quantity = isset($args['quantity']) ? (int)$args['quantity'] : 1;
      $query_string = array(
        'cc_task=add_to_cart',
        'sku=' . $args['sku'],
        'quantity=' . $quantity
      );
      if(isset($args['redirect'])) {
        $query_string['redirect'] = 'redirect=' . urlencode($args['redirect']);
      }
      $query_string = implode('&', $query_string);
      $link = get_site_url() . '?' . $query_string;
    }
    $link = "<a href='$link'>$content</a>";
    return $link;
  }


  public static function register_shortcodes() {
    add_shortcode('ccm_show_to', array('CC_ShortcodeManager', 'ccm_show_to'));
    add_shortcode('ccm_hide_from', array('CC_ShortcodeManager', 'ccm_hide_from'));
  }

  /**
   * Only show the enclosed content to visitors with an active subscription
   * to one or more of the provided SKUs. All SKUs will be lowercased before
   * evaluation.
   *
   * Special SKU values: 
   *   members: all logged in users regardless of subscriptions or subscription status
   *   guests: all vistors who are not logged in 
   *
   * Attributes:
   *   sku: Comma separated list of SKUs required to view content
   *   days_in: The number of days old the subscription must be before the content is available
   *
   * @param array $attrs An associative array of attributes, or an empty string if no attributes are given
   * @param string $content The content enclosed by the shortcode
   * @param string $tag The shortcode tag
   */
  public static function ccm_show_to($attrs, $content, $tag) {
    if(!self::visitor_in_group($attrs)) {
      $content = '';
    }
    return $content;
  }

  public static function ccm_hide_from($attrs, $content, $tag) {
    if(self::visitor_in_group($attrs)) {
      $content = '';
    }
    return $content;
  }

  public static function visitor_in_group($attrs) {
    $in_group = false;

    if(is_array($attrs)) {
      $member_id = 99; // TODO: Use real member id for ccm_show_to shortcode
      $days_in = (isset($attrs['days_in'])) ? (int) $attrs['days_in'] : 0;
      
      if(isset($attrs['sku'])) {
        $skus = explode(',', strtolower(trim(str_replace(' ', '', $attrs['sku']))));
      }
      
      if($member_id == 0 && in_array('guests', $skus)) {
        // Show content to all non-logged in visitors if "guests" is in the array of SKUs
        $in_group = true;
      }
      elseif($member_id > 0 && !in_array('guests', $skus)) {
        // If the visitor is logged in
        if(in_array('members', $skus)) {
          // Show content to all logged in visitors if "members" is in the array of SKUs
          $in_group = true;
        }
        else {
          $ccm_library = new CC_Library();
          if($ccm_library->has_permission($member_id, $skus, $days_in)) {
            $in_group = true;
          }
        }
      }
    }

    return $in_group;
  }
}
