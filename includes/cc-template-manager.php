<?php
include_once CC_PATH . 'includes/cc-template-filters.php';
include_once CC_PATH . 'includes/cc-template-actions.php';

/**
 * Load the template for the given slug and name.
 *
 * Look in the following loactions for templates and pick the first one found:
 * - active-theme/slug-name.php
 * - active-theme/cart66/slug-name.php
 * - CC_PATH /templates/slugn-name.php
 * - active-theme/slug.php
 * - active-theme/cart66/slug.php
 * - allow 3rd party plugin to provide a template path
 */
function cc_get_template_part( $slug, $name = '' ) {
    $template = '';

    // Look in active-theme/slug-name.php and active-theme/cart66/slug-name.php
    if ( $name && ! CC_TEMPLATE_DEBUG_MODE ) {
        $template = locate_template( array( "{$slug}-{$name}.php", 'cart66/' . "{$slug}-{$name}.php" ) );
    }

    // Get default slug-name.php
    if ( ! $template && $name && file_exists( CC_PATH . "/templates/{$slug}-{$name}.php" ) ) {
        $template = CC_PATH . "/templates/{$slug}-{$name}.php";
    }

    // If template file doesn't exist, look in active-theme/slug.php and active-theme/cart66/slug.php
    if ( ! $template && ! CC_TEMPLATE_DEBUG_MODE ) {
        $template = locate_template( array( "{$slug}.php", 'cart66/' . "{$slug}.php" ) );
    }

    // Allow 3rd party plugin filter template file from their plugin
    if ( ( ! $template && CC_TEMPLATE_DEBUG_MODE ) || $template ) {
        $template = apply_filters( 'cc_get_template_part', $template, $slug, $name );
    }

    if ( $template ) {
        load_template( $template, false );
    }
}
