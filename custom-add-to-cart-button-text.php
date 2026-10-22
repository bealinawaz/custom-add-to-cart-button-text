<?php
/**
 * Plugin Name:          Custom Add to Cart Button Text for WooCommerce
 * Plugin URI:           https://github.com/bealinawaz/custom-add-to-cart-button-text
 * Description:          Change the WooCommerce "Add to cart" button text for each product type, on product pages and shop pages, with per-category and per-product overrides.
 * Version:              2.0.0
 * Requires at least:    6.2
 * Requires PHP:         7.4
 * Requires Plugins:     woocommerce
 * Author:               Ali Nawaz
 * Author URI:           https://bealinawaz.com
 * License:              GPLv2 or later
 * License URI:          https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain:          custom-add-to-cart-button-text
 * WC requires at least: 7.1
 * WC tested up to:      11.2
 *
 * @package Custom_Add_To_Cart_Button_Text
 */

defined( 'ABSPATH' ) || exit;

define( 'CACBT_VERSION', '2.0.0' );
define( 'CACBT_FILE', __FILE__ );

require_once __DIR__ . '/includes/class-cacbt-settings.php';
require_once __DIR__ . '/includes/class-cacbt-buttons.php';

// Button text only changes product display, so the plugin works with both order storage types and block checkout.
add_action(
	'before_woocommerce_init',
	function () {
		if ( class_exists( \Automattic\WooCommerce\Utilities\FeaturesUtil::class ) ) {
			\Automattic\WooCommerce\Utilities\FeaturesUtil::declare_compatibility( 'custom_order_tables', CACBT_FILE, true );
			\Automattic\WooCommerce\Utilities\FeaturesUtil::declare_compatibility( 'cart_checkout_blocks', CACBT_FILE, true );
		}
	}
);

CACBT_Buttons::init();

if ( is_admin() ) {
	require_once __DIR__ . '/includes/class-cacbt-admin.php';
	CACBT_Admin::init();
}
