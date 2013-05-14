jQuery(document).ready(function($) {
  var data = { 
    action: 'render_cloudswipe_account_widget',
    logged_in_message: cs_account_widget.logged_in_message,
    logged_out_message: cs_account_widget.logged_out_message,
    show_link_history: cs_account_widget.show_link_history,
    show_link_profile: cs_account_widget.show_link_profile
  };
  $.post(cs_account_widget.ajax_url, data, function(response) {
    $('#cs_account_widget').html(response);
  });
});

