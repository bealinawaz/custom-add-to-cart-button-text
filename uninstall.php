<?php
/**
 * Removes the plugin's settings, product meta, and category meta.
 *
 * @package Custom_Add_To_Cart_Button_Text
 */

defined( 'WP_UNINSTALL_PLUGIN' ) || exit;

delete_option( 'cacbt_texts' );

foreach ( array( '_cacbt_single_text', '_cacbt_archive_text' ) as $cacbt_key ) {
	delete_post_meta_by_key( $cacbt_key );
}

foreach ( array( 'cacbt_single_text', 'cacbt_archive_text' ) as $cacbt_key ) {
	delete_metadata( 'term', 0, $cacbt_key, '', true );
}
