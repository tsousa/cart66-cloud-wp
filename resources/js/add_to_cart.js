jQuery(document).ready(function($) {
  $('.cart66-button').live("click", function() {
    var form = $(this).closest('form');
    var query_string = form.serialize();
    var data = 'action=cc_ajax_add_to_cart&' + query_string;
    $('.alert').hide();
    $.ajax({
      type: 'POST',
      url: cc_cart.ajax_url,
      data: data,
      dataType: 'html',
      success: function(response) {
        form.append('<div class="ajax_add_to_cart_message"><span class="alert alert-success ajax_button_notice"><a href="#" title="close" class="cc_close_message"><i class="icon-remove"></i></a><span class="cc_ajax_message">' + response + '</span></span></div>');
        $('.cart66-button').trigger('CC:item_added');
        refresh_widget();
      },
      error: function(response) {
        if(response.status == 500) {
          form.append('<div class="ajax_add_to_cart_message"><span class="alert alert-error ajax_button_notice"><a href="#" title="close" class="cc_close_message"><i class="icon-remove"></i></a><span class="cc_ajax_message">The product was not added to your cart. Please try again.</span></span></div>');
        }
        else {
          var order_form = form.closest('.cart66');
          order_form.replaceWith(response.responseText);
        }
      }
    });

    return false;
  });

  function refresh_widget() {
    if($('#cc_cart_widget').length > 0) {
      $.post(cc_cart.ajax_url, {action: 'render_cart66_cart_widget'}, function(response) {
        $('#cc_cart_widget').html(response);
      });
    }
  }

  $('.cc_close_message').live('click', function() {
    $(this).parent().hide();
    return false;
  });

});
