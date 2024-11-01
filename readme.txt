=== Simple Minimum and Maximum Quantity Limit for WooCommerce ===
Contributors: wpashokg 
Donate link: https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=RTAAFGGL53DMG
Tags: woocommerce, minimum, maximum, quantity, minimum purchase, maximum purchase, purchase
Requires at least: 3.3.1
Tested up to: 6.2.2
Stable tag: 2.1.9
WC tested up to: 7.8
License: GNU General Public License v3.0
License URI: http://www.gnu.org/licenses/gpl-3.0.html


== Description ==
Hello Friends,<br>
This is a WordPress WooCommerce support plugin which will Allow the site admin to enable the feature of minimum and maximum purchase of a particular product in each product.

[youtube https://www.youtube.com/watch?v=5uNf6ADOutM]

Features:

    Admin can enable / disable this feature on each product
    Admin can manage the minimum and maximum limit for each product.
    Added feature to do increments based on the increment set by admin
    

== Installation ==
	
 * Upload the plugin files to the '/wp-content/plugins/' directory
 * Activate the plugin through the 'Plugins' menu in WordPress
 * Select a particular product from the product list for which you need to enable this feature.
 * Enable this feature and add the minimum and maximum allowed quantity in the respective boxes
 * Setting up the increment, if the increment by is set other than one, the minimum order quantity should be the value of the increment set.
 * Setting custom error notice / messages, in the admin panel go to Settings --> WooCommerce Minimum and Maximum Quantity Limit, there you will find 2 text area, there you can set the custom error message which will override the default error message in the product and cart page
 
== Frequently Asked Questions ==

= Does it support category based minimum and maximum purchase limit ? =

Currently it does not support this feature.

= Does it support a central place to maintain the minimum and maximum limit for each product ? =

Currently it does not support this feature, to enable this feature we need to configure these values on each product.

== Changelog ==
= 2.1.9 =
Name change Compliance
= 2.1.8 =
Bug Fix: Bug fix for division or modulo by zero
= 2.1.7 =
Bug Fix: To set the default increment by value @tigerrockmartialarts
= 2.1.6 =
Bug Fix: If quantity item manually changed in the product page / cart page and if the changed quantity voids the minimum / maximum order quantity rule / the quantity increment rule then it throws error which was not earlier.
= 2.1.5 =
Added feature to do increments based on the increment set by admin
= 2.1.4 =
Added fix for wrong quantity issue in the shop page and and category pages. Thanks for the solution from @tigerrockmartialarts, @galbaras https://wordpress.org/support/topic/add-to-cart-on-variable-products/, https://wordpress.org/support/topic/minimum-qty-no-longer-working/
=  2.1.3 =
Changed the condition to check the product type is "simple" since we don't support variable / grouped product
= 2.1.2 =
Bug fix for variable product view link
= 2.1.1 =
Bug fix for ajax add to cart.
= 2.1.0 =
Fixed the bug of double add to cart buttons
= 2.0.9 =
Added a condition to check the product type in the shop /  category pages since it was creating problems for non supported product types.
= 2.0.8 =
Removed Javascript alert and added woocommerce notice functionality, now also added the feature, to add product to cart from all pages.
= 2.0.7 =
Minor Bug Fix to overcome the double add-to-cart button in the shop and category pages.
= 2.0.6 = 
Minor Bug Fix to fix the issue in the custom add to cart alert message section. In which single quote created problem.
= 2.0.5 = 
Minor Bug Fix to fix double add to cart buttons in the category and shop pages.
= 2.0.4 =
Most awaited feature of enabling add to cart button in the shop / category page. Also added a customizable options page to display custom add to cart messages.
= 2.0.3 =
Minor Bug Pointed By @shibi https://wordpress.org/support/topic/error-in-the-function-wc_mmax_quantity_input_args/
= 2.0.2 =
Minor Bug fix pointed by @leemon
= 2.0.1 =
Minor Bug fix added order quantity to fix the ajax issue.
= 2.0-beta =
Minor Bug fix on Quantity
= 1.1.6 =
Minor Bug fix
= 1.1.5 =
WPML support addition. Suggested by @maxgx
= 1.1.4 =
Bug fixes related to minimum product quantity selection in the product page.

= 1.1.3 =
Removed the view product link.

= 1.1.2 =
Made corrections for plugin activation error.

= 1.1.1 =
Typo  Corrections and standardizations. Bug fix for managed inventory products

= 1.1 =
Fixed the bug for adding same product multiple times which was adding the
product even if the maximum quantity for the product quantity is reached. Now
if the product is added multiple times and if it reached the maximum limit it
shows a popup that you have reached maximum limit for the product and exits
back to the product page
 
= 1.0.0 =
First Release