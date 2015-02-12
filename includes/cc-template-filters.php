<?php


/**
 * Include the appropriate templates for cart66 products
 *
 * @param string $template The template to be included
 * @return string The path to the template to be included
 */
function cc_template_include( $template ) {
    $post_type = get_post_type();

    if ( is_single() && 'cc_product' == $post_type ) {
        $template = cc_get_template_part( 'single', 'product' );
    } elseif ( is_post_type_archive( 'cc_product' ) ) {
        $template = cc_get_template_part( 'archive', 'product' );
    } elseif ( is_tax( 'product-category' ) ) {
        $template = cc_get_template_part( 'taxonomy', 'product-category' );
    }

    // CC_Log::write( "Considering which template to include:\nTemplate: " . $template . "\nPost type: " . $post_type );

    return $template;
}

add_filter( 'template_include', 'cc_template_include' );

function product_sort_order( $wp_query ) {
    if ( $wp_query->is_main_query() ) {
        $sort_method = CC_Admin_Setting::get_option( 'cart66_product_options', 'sort_method' );
        $is_product_query = false;

        if ( isset( $wp_query->query['post_type'] ) && 'cc_product' == $wp_query->query['post_type'] ) {
            $is_product_query = true;
            CC_Log::write( 'The post type is cc_product' );
        }
        elseif ( isset( $wp_query->query['product-category'] ) ) {
            $is_product_query = true;
            CC_Log::write( 'The product category is set' );
        }

        if ( $wp_query->is_main_query() && $is_product_query ) {
            // $wp_query->set('orderby', 'title');
            switch ( $sort_method ) {
                case 'price_desc':
                    $wp_query->set('orderby', 'meta_value_num');
                    $wp_query->set('meta_key', '_cc_product_price');
                    $wp_query->set('order', 'DESC');
                    break;
                case 'price_asc':
                    $wp_query->set('orderby', 'meta_value_num');
                    $wp_query->set('meta_key', '_cc_product_price');
                    $wp_query->set('order', 'ASC');
                    break;
                case 'name_desc':
                    $wp_query->set('orderby', 'title');
                    $wp_query->set('order', 'DESC');
                    break;
                case 'name_asc':
                    $wp_query->set('orderby', 'title');
                    $wp_query->set('order', 'ASC');
                    break;

            }
        }

    } // End of is_main_query

}

add_filter('pre_get_posts', 'product_sort_order');
