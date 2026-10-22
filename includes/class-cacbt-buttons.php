<?php
/**
 * Replaces the add to cart button texts.
 *
 * @package Custom_Add_To_Cart_Button_Text
 */

defined( 'ABSPATH' ) || exit;

/**
 * Filters WooCommerce's button texts.
 *
 * Texts are chosen in this order: the product's own text, the text of its first
 * category that has one (categories are ordered by name), the text for its product
 * type, and finally WooCommerce's default. On shop pages, products that cannot be
 * added to the cart (out of stock or not purchasable) only use the "unavailable"
 * text, because their button links to the product page rather than adding to cart.
 *
 * The same filters drive the classic templates, the Product Button block, the
 * All Products block (through the Store API), and shortcodes.
 */
final class CACBT_Buttons {

	/**
	 * Registers hooks.
	 */
	public static function init() {
		add_filter( 'woocommerce_product_single_add_to_cart_text', array( __CLASS__, 'single_text' ), 20, 2 );
		add_filter( 'woocommerce_product_add_to_cart_text', array( __CLASS__, 'archive_text' ), 20, 2 );
		add_filter( 'woocommerce_product_add_to_cart_description', array( __CLASS__, 'archive_description' ), 20, 2 );
	}

	/**
	 * Single product page button text.
	 *
	 * @param string     $text    Default text.
	 * @param WC_Product $product Product.
	 * @return string
	 */
	public static function single_text( $text, $product = null ) {
		$custom = self::resolve( $product, 'single' );
		return null === $custom ? $text : $custom;
	}

	/**
	 * Shop, category, and block button text.
	 *
	 * @param string     $text    Default text.
	 * @param WC_Product $product Product.
	 * @return string
	 */
	public static function archive_text( $text, $product = null ) {
		$custom = self::resolve( $product, 'archive' );
		return null === $custom ? $text : $custom;
	}

	/**
	 * Keeps the shop button's accessible name in line with its visible text, so
	 * voice control users can say the text they see.
	 *
	 * @param string     $description Default description.
	 * @param WC_Product $product     Product.
	 * @return string
	 */
	public static function archive_description( $description, $product = null ) {
		$custom = self::resolve( $product, 'archive' );
		if ( null === $custom ) {
			return $description;
		}
		/* translators: 1: button text, 2: product name. */
		return sprintf( __( '%1$s: &ldquo;%2$s&rdquo;', 'custom-add-to-cart-button-text' ), $custom, $product->get_name() );
	}

	/**
	 * Finds the custom text for a product, or null to keep WooCommerce's text.
	 *
	 * @param WC_Product|null $product Product.
	 * @param string          $context "single" or "archive".
	 * @return string|null
	 */
	public static function resolve( $product, $context ) {
		if ( ! $product instanceof WC_Product ) {
			return null;
		}

		$type = $product->is_type( 'variation' ) ? 'simple' : $product->get_type();

		if ( 'archive' === $context && ! self::can_add_from_archive( $product ) ) {
			$unavailable = CACBT_Settings::text( CACBT_Settings::UNAVAILABLE_KEY );
			return '' !== $unavailable ? $unavailable : null;
		}

		$owner_id = $product->get_parent_id() ? $product->get_parent_id() : $product->get_id();
		$owner    = $owner_id === $product->get_id() ? $product : wc_get_product( $owner_id );

		$candidates = array(
			$owner ? (string) $owner->get_meta( 'single' === $context ? CACBT_Settings::PRODUCT_SINGLE : CACBT_Settings::PRODUCT_ARCHIVE ) : '',
			self::category_text( $owner_id, $context ),
			in_array( $type, CACBT_Settings::TYPES, true ) ? CACBT_Settings::text( $context . '_' . $type ) : '',
		);

		foreach ( $candidates as $candidate ) {
			$candidate = trim( $candidate );
			if ( '' !== $candidate ) {
				/**
				 * Filters the custom button text before it is shown.
				 *
				 * @param string     $candidate Button text.
				 * @param WC_Product $product   Product.
				 * @param string     $context   "single" or "archive".
				 */
				return (string) apply_filters( 'cacbt_button_text', $candidate, $product, $context );
			}
		}
		return null;
	}

	/**
	 * Whether WooCommerce shows a real add to cart (or select options) button on shop pages,
	 * matching the checks in WooCommerce's own add_to_cart_text() methods.
	 *
	 * @param WC_Product $product Product.
	 * @return bool
	 */
	private static function can_add_from_archive( $product ) {
		if ( $product->is_type( 'variable' ) ) {
			return $product->is_purchasable();
		}
		if ( $product->is_type( array( 'simple', 'variation' ) ) ) {
			return $product->is_purchasable() && $product->is_in_stock();
		}
		return true;
	}

	/**
	 * Text from the first of the product's categories that sets one.
	 *
	 * @param int    $product_id Product ID.
	 * @param string $context    "single" or "archive".
	 * @return string
	 */
	private static function category_text( $product_id, $context ) {
		$key = 'single' === $context ? CACBT_Settings::TERM_SINGLE : CACBT_Settings::TERM_ARCHIVE;
		foreach ( wc_get_product_term_ids( $product_id, 'product_cat' ) as $term_id ) {
			$text = trim( (string) get_term_meta( $term_id, $key, true ) );
			if ( '' !== $text ) {
				return $text;
			}
		}
		return '';
	}
}
