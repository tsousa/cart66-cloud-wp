<?php 
    wp_nonce_field( 'cc_product_meta_box', 'cc_product_meta_box_nonce' ); 
    $product_id = get_post_meta( $post->ID, 'cc_product_id', true ); // get a single value back as a string
?>

<script langage="text/javascript">
    jQuery(document).ready(function($) {

        $('#cc_product_id').select2({
            width: '100%',
            minimumInputLength: 2,
            allowClear: true,
            ajax: {
                url: ajaxurl,
                dataType: 'json',
                data: function (term, page) {
                    return {
                        action: 'cc_ajax_product_search',
                        search: term
                    };
                },
                results: function (data, page) {
                  return { results: data };
                }
            }
        });


    });
</script>

<input type="hidden" id="cc_product_id" name="cc_product_id" value="" data-placeholder="<?php echo $value ?>" />
