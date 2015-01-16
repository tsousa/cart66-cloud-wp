<?php
/**
 * Main admin settings page
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}

settings_errors( 'cart66_main_settings_group' );
?>

<div class="wrap">
    <h2>Cart66 Settings</h2>
    <form method="post" action="options.php">
        <?php
        // Output the settings sections. The parameter should match the menu_slug (4th parameter).
        do_settings_sections('cart66_main');

        // Output the hidden fields, nonce, etc. Should be the group name used in register_setting()
        settings_fields('cart66_main_settings');

        // Submit button.
        submit_button();
    ?>
    </form>
</div>
