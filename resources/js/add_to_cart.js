jQuery(document).ready(function($) {
  $('.cloudswipe-button').live("click", function() {
    var form = $(this).closest('form');
    var query_string = form.serialize();
    var data = 'action=cs_ajax_add_to_cart&' + query_string;
    $('.alert').hide();
    $.ajax({
      type: 'POST',
      url: cs_cart.ajax_url,
      data: data,
      dataType: 'html',
      success: function(response) {
        form.append('<span class="alert alert-success ajax_button_notice">' + response + '</span>');
        refresh_widget();
      },
      error: function(response) {
        if(response.status == 500) {
          form.append('<span class="alert alert-error ajax_button_notice">The product was not added to your cart. Please try again.</span>');
        }
        else {
          var order_form = form.closest('.cloudswipe');
          order_form.replaceWith(response.responseText);
        }
      }
    });

    return false;
  });

  function refresh_widget() {
    if($('#cs_cart_widget').length > 0) {
      $.post(cs_cart.ajax_url, {action: 'render_cloudswipe_cart_widget'}, function(response) {
        $('#cs_cart_widget').html(response);
      });
    }
  }

});
