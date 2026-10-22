<?php
/**
 * Stored button texts.
 *
 * @package Custom_Add_To_Cart_Button_Text
 */

defined( 'ABSPATH' ) || exit;

/**
 * Names and reads the plugin's settings and meta keys.
 */
final class CACBT_Settings {

	const OPTION          = 'cacbt_texts';
	const PRODUCT_SINGLE  = '_cacbt_single_text';
	const PRODUCT_ARCHIVE = '_cacbt_archive_text';
	const TERM_SINGLE     = 'cacbt_single_text';
	const TERM_ARCHIVE    = 'cacbt_archive_text';
	const TYPES           = array( 'simple', 'variable', 'grouped', 'external' );
	const UNAVAILABLE_KEY = 'archive_unavailable';

	/**
	 * Saved texts, keyed like "single_simple" or "archive_variable".
	 *
	 * @return array
	 */
	public static function texts() {
		$saved = get_option( self::OPTION, array() );
		return is_array( $saved ) ? $saved : array();
	}

	/**
	 * One saved text, or an empty string.
	 *
	 * @param string $key Setting key.
	 * @return string
	 */
	public static function text( $key ) {
		$texts = self::texts();
		return isset( $texts[ $key ] ) ? trim( (string) $texts[ $key ] ) : '';
	}

	/**
	 * Cleans a button text typed by an admin.
	 *
	 * @param mixed $value Raw value.
	 * @return string
	 */
	public static function clean( $value ) {
		return is_string( $value ) ? trim( sanitize_text_field( $value ) ) : '';
	}
}
