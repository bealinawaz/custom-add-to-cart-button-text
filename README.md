# Custom Add to Cart Button Text for WooCommerce

Change the WooCommerce "Add to cart" button text to "Buy Now", "Pre-order", or anything else, without code. Set separate texts for product pages and shop pages, per product type, and override them for a category or a single product.

![Tested up to WordPress 7.1](https://img.shields.io/badge/WordPress-7.1-blue)
![WooCommerce 7.1+](https://img.shields.io/badge/WooCommerce-7.1%2B-7f54b3)
![PHP 7.4 to 8.5](https://img.shields.io/badge/PHP-7.4%E2%80%938.5-777bb4)
![License GPLv2 or later](https://img.shields.io/badge/license-GPLv2%2B-green)

## Features

- **Product page and shop page texts**, set separately.
- **Per product type:** simple, variable ("Select options"), grouped ("View products"), and external/affiliate.
- **Unavailable products:** replace the "Read more" text shown for out of stock or unpurchasable products.
- **Category and product overrides:** term meta on product categories and a field in Product data → General.
- **Everywhere:** classic templates, block themes, the Product Button and All Products blocks (via the Store API), and shortcodes.
- **Accessible:** the shop button's `aria-label` follows the visible text.
- Empty fields keep WooCommerce's (translated) defaults. Compatible with HPOS and the block cart and checkout.

## Setup

1. Install WooCommerce and activate the plugin.
2. **WooCommerce → Settings → Products → Add to cart buttons**: fill in the texts you want.
3. Optional: **Products → Categories → Edit** or **Product data → General** for overrides.

Text priority: product → first category (alphabetical) with a text → product type setting → WooCommerce default. On shop pages, products that cannot be added to the cart only use the "Out of stock or not for sale" text.

## Hooks

- `cacbt_button_text` (filter): `( string $text, WC_Product $product, string $context )` where `$context` is `single` or `archive`. Runs whenever the plugin replaces a text.

The plugin itself filters `woocommerce_product_single_add_to_cart_text`, `woocommerce_product_add_to_cart_text`, and `woocommerce_product_add_to_cart_description` at priority 20.

## Data

- Option `cacbt_texts` (array of texts keyed `single_{type}`, `archive_{type}`, `archive_unavailable`).
- Product meta `_cacbt_single_text`, `_cacbt_archive_text`.
- Category term meta `cacbt_single_text`, `cacbt_archive_text`.

All are removed on uninstall.

## Project structure

```
custom-add-to-cart-button-text.php   Plugin header, bootstrap, WooCommerce compatibility
includes/class-cacbt-settings.php    Option and meta names, reading and cleaning texts
includes/class-cacbt-buttons.php     WooCommerce text filters and priority rules
includes/class-cacbt-admin.php       Settings section, product and category fields, plugin links
assets/admin.css                     Admin styles
uninstall.php                        Removes settings and meta
readme.txt                           WordPress.org readme
```

## Custom development

Need a "Request a quote" flow, custom product buttons, or a tailored WooCommerce store? The plugin is built and maintained by [Ali Nawaz](https://bealinawaz.com/?utm_source=github&utm_medium=readme&utm_campaign=custom-add-to-cart-button-text), a WordPress and WooCommerce developer. [Get in touch for custom work](https://bealinawaz.com/contact-me/?utm_source=github&utm_medium=readme&utm_campaign=custom-add-to-cart-button-text).

## License

GPLv2 or later. See [LICENSE](LICENSE).
