jQuery(document).ready(function($) {

    $('.cc-gallery-thumb-link').on( 'click', function() {
        var ref = $(this).attr('data-ref');
        $('.cc-gallery-full-image').hide();
        $('#' + ref).show();
    });

});
