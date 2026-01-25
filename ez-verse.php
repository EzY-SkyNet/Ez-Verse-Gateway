<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              https://ezverse.in/
 * @since             1.0.1
 * @package           Ezverse_WooCommerce
 *
 * @wordpress-plugin
 * Plugin Name:           EZ-VERSE 
 * Plugin URI:        https://ezverse.in
 * Description:       EZ-VERSE lets you accept UPI payments directly into your bank account with zero transaction fees. 
 * Version:           1.0.1
 * Author:            Akash Chakraborty
 * Author URI:        https://ezverse.in/
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       ezverse-woocommerce
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

if ( ! in_array( 'woocommerce/woocommerce.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) ) ) return;

/**
 * Currently plugin version.
 * Start at version 1.0.1 and use SemVer - https://semver.org
 * Rename this for your plugin and update it as you release new versions.
 */
define( 'Ezverse_WooCommerce_VERSION', '1.0.1' );

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-ez-verse-activator.php
 */

/**
 * Custom function to declare compatibility with cart_checkout_blocks feature 
*/
function declare_cart_checkout_blocks_compatibility() {
    // Check if the required class exists
    if (class_exists('\Automattic\WooCommerce\Utilities\FeaturesUtil')) {
        // Declare compatibility for 'cart_checkout_blocks'
        \Automattic\WooCommerce\Utilities\FeaturesUtil::declare_compatibility('cart_checkout_blocks', __FILE__, true);
    }
}
// Hook the custom function to the 'before_woocommerce_init' action
add_action('before_woocommerce_init', 'declare_cart_checkout_blocks_compatibility');

function activate_Ezverse_WooCommerce() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-ez-verse-activator.php';
	Ezverse_WooCommerce_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-ez-verse-deactivator.php
 */
function deactivate_Ezverse_WooCommerce() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-ez-verse-deactivator.php';
	Ezverse_WooCommerce_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_Ezverse_WooCommerce' );
register_deactivation_hook( __FILE__, 'deactivate_Ezverse_WooCommerce' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-ez-verse.php';
add_action( 'woocommerce_blocks_loaded', 'register_order_approval_payment_method_type' );

function register_order_approval_payment_method_type() {
    if ( ! class_exists( 'Automattic\WooCommerce\Blocks\Payments\Integrations\AbstractPaymentMethodType' ) ) {
        return;
    }

    require_once plugin_dir_path(__FILE__) . 'includes/class-ez-verse-blocks.php';

    add_action(
        'woocommerce_blocks_payment_method_type_registration',
        function( Automattic\WooCommerce\Blocks\Payments\PaymentMethodRegistry $payment_method_registry ) {
            $payment_method_registry->register( new Ezverse_WooCommerce_Blocks );
        }
    );
}


/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.1
 */
function run_Ezverse_WooCommerce() {

	$plugin = new Ezverse_WooCommerce();
	$plugin->run();

}
run_Ezverse_WooCommerce();
