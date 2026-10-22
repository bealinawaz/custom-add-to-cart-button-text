=== Custom Add to Cart Button Text for WooCommerce ===
Contributors: imalinawaz
Donate link: https://www.buymeacoffee.com/bealinawaz
Tags: add to cart text, add to cart button, woocommerce button, buy now button, button text
Requires at least: 6.2
Tested up to: 7.1
Requires PHP: 7.4
Stable tag: 2.0.0
License: GPLv2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html

Change the WooCommerce "Add to cart" button text, such as "Buy Now" or "Pre-order", per product type, category, or product. No code needed.

== Description ==

Custom Add to Cart Button Text for WooCommerce lets you change the "Add to cart" button text without editing your theme or adding code snippets. Use "Buy Now", "Pre-order", "Book now", "Get it today", or anything else that fits your store, in any language.

Set a different text for product pages and for shop pages, for each product type, and override it for a whole product category or a single product. Empty fields keep WooCommerce's own text, so you only change what you need.

**Features**

* **Product page and shop page texts:** change the single product button and the buttons on shop, category, tag, and search pages separately.
* **Per product type:** simple, variable, grouped, and external/affiliate products each get their own text. Replace "Select options" on variable products and "View products" on grouped products too.
* **Out of stock and unavailable products:** change the "Read more" text shown when a product cannot be bought.
* **Category overrides:** give every product in a category its own button text, for example "Pre-order" for a Coming soon category.
* **Product overrides:** set a button text on any single product, under Product data → General.
* **Works everywhere WooCommerce shows the button:** classic themes, block themes, the Product Collection and Product Button blocks, the All Products block, and product shortcodes.
* **Accessible:** the button's screen reader label is updated to match the new text.
* **Translation ready:** default texts come from WooCommerce, so they stay translated. Your own texts can be translated with multilingual plugins that translate options and post meta.

**Custom development**

Need a "Request a quote" flow instead of checkout, custom product page buttons, or a tailored WooCommerce store? The plugin is built and maintained by Ali Nawaz, a WordPress and WooCommerce developer. [Get in touch for custom work](https://bealinawaz.com/contact-me/?utm_source=wordpress.org&utm_medium=plugin-readme&utm_campaign=custom-add-to-cart-button-text).

== Installation ==

1. Install and activate WooCommerce, then this plugin.
2. Go to **WooCommerce → Settings → Products → Add to cart buttons**.
3. Type the texts you want and save. Leave a field empty to keep WooCommerce's default.
4. Optional: set texts for a category under **Products → Categories**, or for one product under **Product data → General**.

== Frequently Asked Questions ==

= How do I change "Add to cart" to "Buy Now" in WooCommerce? =

Go to WooCommerce → Settings → Products → Add to cart buttons and type "Buy Now" in the Simple products fields under both Product page buttons and Shop page buttons. Add it for the other product types as well if you sell them.

= How do I change the "Select options" button text on variable products? =

Use the Variable products field under Shop page buttons. The Variable products field under Product page buttons changes the button shown after a shopper picks a variation.

= Which text wins when a product has several? =

The product's own text first, then the text of its category (if it is in several categories with texts, the first one in alphabetical order), then the text for its product type, then WooCommerce's default.

= Why does an out of stock product still say "Read more"? =

Products that cannot be added to the cart link to the product page instead, so they use the "Out of stock or not for sale" text rather than your add to cart text. Set that field to change it.

= Does it work with block themes and the Product Collection block? =

Yes. The Product Button block, the All Products block, and the classic templates all read the same WooCommerce texts that this plugin changes.

= Can I change the text on external/affiliate products? =

Each external product already has a Button text field. Leave the plugin's External/Affiliate fields empty to keep those, or fill them in to use the same text for every external product.

= Does it change the button on the cart or checkout pages? =

No. It changes the product buttons only. The cart and checkout buttons are part of WooCommerce's cart and checkout.

== Changelog ==

= 2.0.0 =
* Complete rewrite. Version 1.0 always showed "Buy Now"; the text is now configurable and nothing changes until you set it. To keep "Buy Now", enter it in the Simple products fields after updating.
* New: settings under WooCommerce → Settings → Products → Add to cart buttons.
* New: separate texts for product pages and shop pages, per product type, plus the out of stock "Read more" text.
* New: per-category and per-product button texts.
* New: support for block themes, the Product Button block, and the All Products block.
* New: the button's accessible label matches the custom text.
* Fixed: removed filters for WooCommerce versions older than 2.1 and the wrong text domain.
* Declares compatibility with WooCommerce High-Performance Order Storage and the block-based cart and checkout.

= 1.0 =
* Initial release.

== Upgrade Notice ==

= 2.0.0 =
Complete rewrite with settings. The fixed "Buy Now" text is gone: after updating, enter "Buy Now" (or any text) under WooCommerce → Settings → Products → Add to cart buttons.
