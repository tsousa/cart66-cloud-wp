jQuery(document).ready(function($) {
  $('.cloudswipe-button').live("click", function() {
    var form = $(this).closest('form');
    var query_string = form.serialize();
    var data = 'action=cs_ajax_add_to_cart&' + query_string;
    $('.alert').hide();
    $.ajax({
      type: 'POST',
      url: cs_widget.ajax_url, 
      data: data,
      dataType: 'html',
      success: function(response) {
        console.log('Product added to cart');
        form.append('<span style="margin-top: 20px; display: inline-block;" class="alert alert-success">Product added to cart</span>');
        refresh_widget();
      },
      error: function(response) {
        if(response.status == 500) {
          form.append('<span style="margin-top: 20px; display: inline-block;" class="alert alert-error">The product was not added to your cart. Please try again.</span>');
        }
        else {
          var order_form = form.closest('.cloudswipe');
          order_form.replaceWith(response.responseText);
          console.log(response.responseText);
        }
      }
    });

    return false;
  });

  function refresh_widget() {
    $.post(cs_widget.ajax_url, {action: 'render_cloudswipe_cart_widget'}, function(response) {
      $('#cs_cart_widget').html(response);
    });
  }

});
