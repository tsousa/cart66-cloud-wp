<?php 
    wp_nonce_field( 'cc_product_meta_box', 'cc_product_meta_box_nonce' ); 
    $product_id = get_post_meta( $object->ID, 'cc_product_id', true ); // get a single value back as a string
?>

<select name="cc_product_id">
    <option value="one">Product One</option>
    <option value="two">Product Two</option>
    <option value="three" <?php echo 'three' == $product_id ? 'selected="selected"' : ''; ?>>Product Three</option>
</select>
