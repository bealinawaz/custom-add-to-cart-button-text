<?php
/**
 * Settings section, product and category fields, and plugin links.
 *
 * @package Custom_Add_To_Cart_Button_Text
 */

defined( 'ABSPATH' ) || exit;

/**
 * Admin hooks.
 */
final class CACBT_Admin {

	const SECTION     = 'cacbt';
	const TERM_NONCE  = 'cacbt_term_nonce';
	const AUTHOR_URL  = 'https://bealinawaz.com/';
	const CONTACT_URL = 'https://bealinawaz.com/contact-me/';
	const DONATE_URL  = 'https://www.buymeacoffee.com/bealinawaz';

	/**
	 * Registers hooks.
	 */
	public static function init() {
		add_filter( 'woocommerce_get_sections_products', array( __CLASS__, 'add_section' ) );
		add_filter( 'woocommerce_get_settings_products', array( __CLASS__, 'settings' ), 10, 2 );
		add_action( 'woocommerce_after_settings_products', array( __CLASS__, 'maybe_render_support' ) );

		add_action( 'woocommerce_product_options_general_product_data', array( __CLASS__, 'render_product_fields' ) );
		add_action( 'woocommerce_admin_process_product_object', array( __CLASS__, 'save_product_fields' ) );

		add_action( 'product_cat_add_form_fields', array( __CLASS__, 'render_add_term_fields' ) );
		add_action( 'product_cat_edit_form_fields', array( __CLASS__, 'render_edit_term_fields' ) );
		add_action( 'created_product_cat', array( __CLASS__, 'save_term_fields' ) );
		add_action( 'edited_product_cat', array( __CLASS__, 'save_term_fields' ) );

		add_action( 'admin_enqueue_scripts', array( __CLASS__, 'enqueue' ) );
		add_filter( 'plugin_action_links_' . plugin_basename( CACBT_FILE ), array( __CLASS__, 'action_links' ) );
		add_filter( 'plugin_row_meta', array( __CLASS__, 'row_meta' ), 10, 2 );
	}

	/**
	 * Settings screen URL.
	 *
	 * @return string
	 */
	public static function settings_url() {
		return admin_url( 'admin.php?page=wc-settings&tab=products&section=' . self::SECTION );
	}

	/**
	 * Adds the "Add to cart buttons" section under WooCommerce → Settings → Products.
	 *
	 * @param array $sections Sections.
	 * @return array
	 */
	public static function add_section( $sections ) {
		$sections[ self::SECTION ] = __( 'Add to cart buttons', 'custom-add-to-cart-button-text' );
		return $sections;
	}

	/**
	 * A text field stored in the plugin's settings array. WooCommerce saves it and
	 * cleans it with wc_clean().
	 *
	 * @param string $key         Setting key.
	 * @param string $title       Field label.
	 * @param string $placeholder Default shown when empty.
	 * @return array
	 */
	private static function field( $key, $title, $placeholder ) {
		return array(
			'title'       => $title,
			'id'          => CACBT_Settings::OPTION . '[' . $key . ']',
			'type'        => 'text',
			'default'     => '',
			'placeholder' => $placeholder,
		);
	}

	/**
	 * Settings fields for the section.
	 *
	 * @param array  $settings        Settings of the current section.
	 * @param string $current_section Current section.
	 * @return array
	 */
	public static function settings( $settings, $current_section ) {
		if ( self::SECTION !== $current_section ) {
			return $settings;
		}
		$add_to_cart = __( 'Add to cart', 'custom-add-to-cart-button-text' );
		$own_text    = __( 'The product\'s own button text', 'custom-add-to-cart-button-text' );

		return array(
			array(
				'title' => __( 'Product page buttons', 'custom-add-to-cart-button-text' ),
				'type'  => 'title',
				'desc'  => __( 'The button on single product pages. Leave a field empty to keep the WooCommerce default shown in grey. You can also set texts for a product category (Products → Categories) or a single product (Product data → General).', 'custom-add-to-cart-button-text' ),
				'id'    => 'cacbt_single',
			),
			self::field( 'single_simple', __( 'Simple products', 'custom-add-to-cart-button-text' ), $add_to_cart ),
			self::field( 'single_variable', __( 'Variable products', 'custom-add-to-cart-button-text' ), $add_to_cart ),
			self::field( 'single_grouped', __( 'Grouped products', 'custom-add-to-cart-button-text' ), $add_to_cart ),
			self::field( 'single_external', __( 'External/Affiliate products', 'custom-add-to-cart-button-text' ), $own_text ),
			array(
				'type' => 'sectionend',
				'id'   => 'cacbt_single',
			),
			array(
				'title' => __( 'Shop page buttons', 'custom-add-to-cart-button-text' ),
				'type'  => 'title',
				'desc'  => __( 'The buttons on the shop, category, tag, and search pages, in product blocks, and in product shortcodes.', 'custom-add-to-cart-button-text' ),
				'id'    => 'cacbt_archive',
			),
			self::field( 'archive_simple', __( 'Simple products', 'custom-add-to-cart-button-text' ), $add_to_cart ),
			self::field( 'archive_variable', __( 'Variable products', 'custom-add-to-cart-button-text' ), __( 'Select options', 'custom-add-to-cart-button-text' ) ),
			self::field( 'archive_grouped', __( 'Grouped products', 'custom-add-to-cart-button-text' ), __( 'View products', 'custom-add-to-cart-button-text' ) ),
			self::field( 'archive_external', __( 'External/Affiliate products', 'custom-add-to-cart-button-text' ), $own_text ),
			array_merge(
				self::field( CACBT_Settings::UNAVAILABLE_KEY, __( 'Out of stock or not for sale', 'custom-add-to-cart-button-text' ), __( 'Read more', 'custom-add-to-cart-button-text' ) ),
				array(
					'desc_tip' => __( 'Shown instead of the texts above when a simple or variable product cannot be added to the cart, for example when it is out of stock or has no price. The button opens the product page.', 'custom-add-to-cart-button-text' ),
				)
			),
			array(
				'type' => 'sectionend',
				'id'   => 'cacbt_archive',
			),
		);
	}

	/**
	 * Prints the support box below the settings section.
	 */
	public static function maybe_render_support() {
		global $current_section;
		if ( self::SECTION !== $current_section ) {
			return;
		}
		?>
		<div class="cacbt-support">
			<h2><?php esc_html_e( 'Need help or a custom version?', 'custom-add-to-cart-button-text' ); ?></h2>
			<p>
				<?php
				printf(
					/* translators: %s: link to the author's website. */
					esc_html__( 'Custom Add to Cart Button Text for WooCommerce is free and built by Ali Nawaz, a WordPress and WooCommerce developer at %s. Need custom buttons, quote requests instead of checkout, or a tailored product page layout?', 'custom-add-to-cart-button-text' ),
					'<a href="' . esc_url( self::AUTHOR_URL ) . '" target="_blank" rel="noopener">bealinawaz.com</a>'
				);
				?>
			</p>
			<p class="cacbt-support__actions">
				<a class="button button-primary" href="<?php echo esc_url( self::CONTACT_URL ); ?>" target="_blank" rel="noopener"><?php esc_html_e( 'Hire me for custom work', 'custom-add-to-cart-button-text' ); ?></a>
				<a class="button" href="<?php echo esc_url( self::AUTHOR_URL ); ?>" target="_blank" rel="noopener"><?php esc_html_e( 'Visit bealinawaz.com', 'custom-add-to-cart-button-text' ); ?></a>
				<a class="button" href="<?php echo esc_url( self::DONATE_URL ); ?>" target="_blank" rel="noopener">&#9749; <?php esc_html_e( 'Buy me a coffee', 'custom-add-to-cart-button-text' ); ?></a>
			</p>
		</div>
		<?php
	}

	/**
	 * Adds the per-product fields to Product data → General.
	 */
	public static function render_product_fields() {
		echo '<div class="options_group">';
		woocommerce_wp_text_input(
			array(
				'id'          => CACBT_Settings::PRODUCT_SINGLE,
				'label'       => __( 'Product page button text', 'custom-add-to-cart-button-text' ),
				'placeholder' => __( 'Store default', 'custom-add-to-cart-button-text' ),
				'desc_tip'    => true,
				'description' => __( 'Overrides the add to cart button text on this product\'s page. Leave empty to use the category or store setting.', 'custom-add-to-cart-button-text' ),
			)
		);
		woocommerce_wp_text_input(
			array(
				'id'          => CACBT_Settings::PRODUCT_ARCHIVE,
				'label'       => __( 'Shop page button text', 'custom-add-to-cart-button-text' ),
				'placeholder' => __( 'Store default', 'custom-add-to-cart-button-text' ),
				'desc_tip'    => true,
				'description' => __( 'Overrides the button text for this product on shop and category pages and in product blocks. Leave empty to use the category or store setting.', 'custom-add-to-cart-button-text' ),
			)
		);
		echo '</div>';
	}

	/**
	 * Saves the per-product fields. WooCommerce has already checked the user's
	 * permission; the nonce is checked again here before reading the fields.
	 *
	 * @param WC_Product $product Product being saved.
	 */
	public static function save_product_fields( $product ) {
		if ( ! isset( $_POST['woocommerce_meta_nonce'] ) || ! wp_verify_nonce( sanitize_key( wp_unslash( $_POST['woocommerce_meta_nonce'] ) ), 'woocommerce_save_data' ) ) {
			return;
		}
		foreach ( array( CACBT_Settings::PRODUCT_SINGLE, CACBT_Settings::PRODUCT_ARCHIVE ) as $key ) {
			$value = isset( $_POST[ $key ] ) ? CACBT_Settings::clean( wp_unslash( $_POST[ $key ] ) ) : ''; // phpcs:ignore WordPress.Security.ValidatedSanitizedInput.InputNotSanitized -- Sanitized by CACBT_Settings::clean().
			if ( '' === $value ) {
				$product->delete_meta_data( $key );
			} else {
				$product->update_meta_data( $key, $value );
			}
		}
	}

	/**
	 * Category fields on the Add new category form.
	 */
	public static function render_add_term_fields() {
		wp_nonce_field( 'cacbt_save_term', self::TERM_NONCE );
		?>
		<div class="form-field">
			<label for="cacbt-term-single"><?php esc_html_e( 'Product page button text', 'custom-add-to-cart-button-text' ); ?></label>
			<input type="text" id="cacbt-term-single" name="<?php echo esc_attr( CACBT_Settings::TERM_SINGLE ); ?>" value="" />
			<p class="description"><?php esc_html_e( 'Add to cart button text on the pages of products in this category. Leave empty to use the store setting.', 'custom-add-to-cart-button-text' ); ?></p>
		</div>
		<div class="form-field">
			<label for="cacbt-term-archive"><?php esc_html_e( 'Shop page button text', 'custom-add-to-cart-button-text' ); ?></label>
			<input type="text" id="cacbt-term-archive" name="<?php echo esc_attr( CACBT_Settings::TERM_ARCHIVE ); ?>" value="" />
			<p class="description"><?php esc_html_e( 'Button text for products in this category on shop and category pages. Leave empty to use the store setting.', 'custom-add-to-cart-button-text' ); ?></p>
		</div>
		<?php
	}

	/**
	 * Category fields on the Edit category screen.
	 *
	 * @param WP_Term $term Category.
	 */
	public static function render_edit_term_fields( $term ) {
		?>
		<tr class="form-field">
			<th scope="row"><label for="cacbt-term-single"><?php esc_html_e( 'Product page button text', 'custom-add-to-cart-button-text' ); ?></label></th>
			<td>
				<?php wp_nonce_field( 'cacbt_save_term', self::TERM_NONCE ); ?>
				<input type="text" id="cacbt-term-single" name="<?php echo esc_attr( CACBT_Settings::TERM_SINGLE ); ?>" value="<?php echo esc_attr( get_term_meta( $term->term_id, CACBT_Settings::TERM_SINGLE, true ) ); ?>" />
				<p class="description"><?php esc_html_e( 'Add to cart button text on the pages of products in this category. Leave empty to use the store setting.', 'custom-add-to-cart-button-text' ); ?></p>
			</td>
		</tr>
		<tr class="form-field">
			<th scope="row"><label for="cacbt-term-archive"><?php esc_html_e( 'Shop page button text', 'custom-add-to-cart-button-text' ); ?></label></th>
			<td>
				<input type="text" id="cacbt-term-archive" name="<?php echo esc_attr( CACBT_Settings::TERM_ARCHIVE ); ?>" value="<?php echo esc_attr( get_term_meta( $term->term_id, CACBT_Settings::TERM_ARCHIVE, true ) ); ?>" />
				<p class="description"><?php esc_html_e( 'Button text for products in this category on shop and category pages. Leave empty to use the store setting.', 'custom-add-to-cart-button-text' ); ?></p>
			</td>
		</tr>
		<?php
	}

	/**
	 * Saves the category fields.
	 *
	 * @param int $term_id Category ID.
	 */
	public static function save_term_fields( $term_id ) {
		if ( ! isset( $_POST[ self::TERM_NONCE ] ) || ! wp_verify_nonce( sanitize_key( wp_unslash( $_POST[ self::TERM_NONCE ] ) ), 'cacbt_save_term' ) ) {
			return;
		}
		if ( ! current_user_can( 'edit_term', $term_id ) ) {
			return;
		}
		foreach ( array( CACBT_Settings::TERM_SINGLE, CACBT_Settings::TERM_ARCHIVE ) as $key ) {
			$value = isset( $_POST[ $key ] ) ? CACBT_Settings::clean( wp_unslash( $_POST[ $key ] ) ) : ''; // phpcs:ignore WordPress.Security.ValidatedSanitizedInput.InputNotSanitized -- Sanitized by CACBT_Settings::clean().
			if ( '' === $value ) {
				delete_term_meta( $term_id, $key );
			} else {
				update_term_meta( $term_id, $key, $value );
			}
		}
	}

	/**
	 * Loads the admin styles on the settings section.
	 *
	 * @param string $hook Current admin page.
	 */
	public static function enqueue( $hook ) {
		// phpcs:ignore WordPress.Security.NonceVerification.Recommended -- Only reads which settings section is open.
		if ( 'woocommerce_page_wc-settings' !== $hook || ! isset( $_GET['section'] ) || self::SECTION !== sanitize_key( wp_unslash( $_GET['section'] ) ) ) {
			return;
		}
		wp_enqueue_style( 'cacbt-admin', plugin_dir_url( CACBT_FILE ) . 'assets/admin.css', array(), CACBT_VERSION );
	}

	/**
	 * Adds a Settings link on the Plugins screen.
	 *
	 * @param array $links Links.
	 * @return array
	 */
	public static function action_links( $links ) {
		array_unshift( $links, '<a href="' . esc_url( self::settings_url() ) . '">' . esc_html__( 'Settings', 'custom-add-to-cart-button-text' ) . '</a>' );
		return $links;
	}

	/**
	 * Adds custom development and donation links under the plugin description.
	 *
	 * @param array  $links Plugin meta links.
	 * @param string $file  Plugin file.
	 * @return array
	 */
	public static function row_meta( $links, $file ) {
		if ( plugin_basename( CACBT_FILE ) !== $file ) {
			return $links;
		}
		$links[] = '<a href="' . esc_url( self::CONTACT_URL ) . '" target="_blank" rel="noopener">' . esc_html__( 'Custom development', 'custom-add-to-cart-button-text' ) . '</a>';
		$links[] = '<a href="' . esc_url( self::DONATE_URL ) . '" target="_blank" rel="noopener">' . esc_html__( 'Buy me a coffee', 'custom-add-to-cart-button-text' ) . '</a>';
		return $links;
	}
}
