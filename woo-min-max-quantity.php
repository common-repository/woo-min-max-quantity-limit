<?php
/*
  Plugin Name: Simple Minimum and Maximum Quantity Limit for WooCommerce
  Plugin URI:  https://ashokg.in/
  Description: Allow the site admin to enable the feature of minimum and maximum purchase of a particular product in each product.
  Version: 2.1.9
  Author: Ashok G
  Text Domain: wcmmax
  Author URI: https://ashokg.in
  WC tested up to: 7.8
  Copyright: © 2023 Ashok G.
  License: GNU General Public License v3.0
  License URI: http://www.gnu.org/licenses/gpl-3.0.html
 */


add_action('add_meta_boxes', 'wc_mmax_meta_box_create');
add_action('save_post', 'wc_mmax_save_meta_box');

function wc_mmax_meta_box_create() {
    add_meta_box('wc_mmax_enable', __('Min Max Quantity', 'wcmmax'), 'wc_mmax_meta_box', 'product', 'side');
}

function wc_mmax_meta_box($post) {
    wp_nonce_field('wc_mmax_cst_prd_nonce', 'wc_mmax_cst_prd_nonce');

    echo '<table>';
    echo '<tr><td><label for="_wc_mmax_prd_opt_enable">' . __('Enable', 'wcmmax') . '</label>';
    echo '<input type="hidden" name="_wc_mmax_prd_opt_enable" value="0" /></td>';
    echo '<td><input type="checkbox" id="_wc_mmax_prd_opt_enable" class="checkbox" name="_wc_mmax_prd_opt_enable" value="1" ' . checked(get_post_meta($post->ID, '_wc_mmax_prd_opt_enable', true), 1, false) . ' /></td>';
    echo '</tr>';
    echo '<tr>';
    $wc_mmax_max = get_post_meta($post->ID, '_wc_mmax_max', true) ? get_post_meta($post->ID, '_wc_mmax_max', true) : 1;
    $wc_mmax_min = get_post_meta($post->ID, '_wc_mmax_min', true) ? get_post_meta($post->ID, '_wc_mmax_min', true) : 1;
    $wc_mmax_increment = get_post_meta($post->ID, '_wc_mmax_increment', true) ? get_post_meta($post->ID, '_wc_mmax_increment', true) : 1;
    echo '<td><label for="_wc_mmax_min">' . __('Min Quantity', 'wcmmax') . '</label></td>';
    echo '<td><input type="number" id="_wc_mmax_min" class="short" name="_wc_mmax_min" value="' . $wc_mmax_min . '" /></td>';
    echo '</tr>';
    echo '<tr>';
    echo '<td><label for="_wc_mmax_max">' . __('Max Quantity', 'wcmmax') . '</label></td>';
    echo '<td><input type="number" id="_wc_mmax_max" class="short" name="_wc_mmax_max" value="' . $wc_mmax_max . '" /></td>';
    echo '</tr>';
    echo '<tr>';
    echo '<td><label for="_wc_mmax_increment">' . __('Increment', 'wcmmax') . '</label></td>';
    echo '<td><input type="number" id="_wc_mmax_increment" class="short" name="_wc_mmax_increment" value="' . $wc_mmax_increment . '" /></td>';
    echo '</tr></table>';
}

function wc_mmax_save_meta_box($post_id) {
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE)
        return;
    if (!isset($_POST['_wc_mmax_prd_opt_enable']) || !wp_verify_nonce($_POST['wc_mmax_cst_prd_nonce'], 'wc_mmax_cst_prd_nonce'))
        return;
    update_post_meta($post_id, '_wc_mmax_prd_opt_enable', (int) $_POST['_wc_mmax_prd_opt_enable']);
    update_post_meta($post_id, '_wc_mmax_max', (int) $_POST['_wc_mmax_max']);
    update_post_meta($post_id, '_wc_mmax_min', (int) $_POST['_wc_mmax_min']);
    update_post_meta($post_id, '_wc_mmax_increment', (int) $_POST['_wc_mmax_increment']);
}

/** Functions For Custom Option Page for Plugin * */
function _wcmmax_options_register_settings() {
    add_option('_wcmmax_options_option_name', '');
    add_option('_wcmmax_cart_error_message', '');
    register_setting('_wcmmax_options_group', '_wcmmax_options_option_name', '_wcmmax_options_callback');
    register_setting('_wcmmax_options_group', '_wcmmax_cart_error_message', '_wcmmax_options_callback');
}

add_action('admin_init', '_wcmmax_options_register_settings');

function _wcmmax_register_options_page() {
    add_options_page('WooCommerce Minimum & Maximum Quantity Limit Settings', 'WooCommerce Minimum & Maximum Quantity Limit', 'manage_options', '_wcmmax_', '_wcmmax_options_page');
}

add_action('admin_menu', '_wcmmax_register_options_page');

function _wcmmax_options_page() {
    ?>
    <div>

        <h2>WooCommerce Minimum & Maximum Quantity Limit Settings</h2>
        <form method="post" action="options.php">
            <?php settings_fields('_wcmmax_options_group'); ?>

            <table>
                <tr valign="top">
                    <th scope="row"><label for="_wcmmax_options_option_name">Custom alert message for maximum Quantity limit </label></th>
                </tr>
                <tr>
                    <td><textarea cols="60" rows="5" id="_wcmmax_options_option_name" name="_wcmmax_options_option_name" ><?php echo get_option('_wcmmax_options_option_name'); ?> </textarea></td>
                </tr>
                <tr valign="top">
                    <th scope="row"><label for="_wcmmax_cart_error_message">Cart Page Custom alert message for quantity update voiding the increment rules </label></th>
                </tr>
                <tr>
                    <td><textarea cols="60" rows="5" id="_wcmmax_cart_error_message" placeholder="Enter Custom Error Message to be shown in the cart page for voiding the product quantity increment rules" name="_wcmmax_cart_error_message" ><?php echo get_option('_wcmmax_cart_error_message'); ?> </textarea></td>
                </tr>

            </table>
            <?php submit_button(); ?>
        </form>
    </div>
    <?php
}

/** Functions For Custom Option Page for Plugin * */
/* Function to manipulate custom minimum and maximum purchase */
add_filter('woocommerce_quantity_input_args', 'wc_mmax_quantity_input_args', 10, 2);

function wc_mmax_quantity_input_args($args, $product) {
    if (function_exists('icl_object_id')) {
        $default_language = wpml_get_default_language();
        $prodid = icl_object_id($product->get_id(), 'product', true, $default_language);
    } else {
        $prodid = $product->get_id();
    }
    $mmaxEnable = get_post_meta($prodid, '_wc_mmax_prd_opt_enable', true);
    $minQty = get_post_meta($prodid, '_wc_mmax_min', true);
    $maxQty = get_post_meta($prodid, '_wc_mmax_max', true);
    $incrementbyQty = get_post_meta($prodid, '_wc_mmax_increment', true);
    $incrementbyQty = $incrementbyQty ? $incrementbyQty : 1;
    if ($minQty > 0 && $maxQty > 0 && $mmaxEnable == 1) {
        $args['min_value'] = $minQty; // Starting value
        $args['max_value'] = $maxQty; // Ending value
        $args['step'] = $incrementbyQty; // Increase by value
    }
    return $args;
}

/* Function to check weather the maximum quantity is already existing in the cart */

add_action('woocommerce_add_to_cart', 'wc_mmax_custom_add_to_cart', 10, 2);

function wc_mmax_custom_add_to_cart($args, $product) {
    $orderQTY = isset($_POST['quantity']) ? $_POST['quantity'] : 1;
    $mmaxEnable = get_post_meta($product, '_wc_mmax_prd_opt_enable', true);
    $minQty = get_post_meta($product, '_wc_mmax_min', true);
    $maxQty = get_post_meta($product, '_wc_mmax_max', true);
    $incrementbyQty = get_post_meta($product, '_wc_mmax_increment', true);
    $incrementbyQty = $incrementbyQty ? $incrementbyQty : 1;
    $cartQty = wc_mmax_woo_in_cart($product);
    if (get_option('_wcmmax_options_option_name') != NULL && get_option('_wcmmax_options_option_name') != '') {
        $maxQTYMsg = get_option('_wcmmax_options_option_name');
    } else {
        $maxQTYMsg = 'You have already added the maximum Quantity for the product for the current purchase';
    }
    if ($maxQty < $cartQty && $mmaxEnable == 1) {

        wc_add_notice($maxQTYMsg, 'error');
        exit(wp_redirect(get_permalink($product)));
    }
    if ((($orderQTY + $cartQty) < $minQty || ($orderQTY + $cartQty) % $incrementbyQty > 0) && $mmaxEnable == 1) {

        wc_add_notice(__('You have ordered ' . $orderQTY . '  which is less than the allowed Minimum Quantity ' . $minQty . ' or its not incremented in the specific format of x ' . $incrementbyQty, 'wcmax'), 'error');
        exit(wp_redirect(get_permalink($product)));
    }
}

function wc_mmax_woo_in_cart($product_id) {
    global $woocommerce;
    foreach ($woocommerce->cart->get_cart() as $key => $val) {

        $_product = $val['data'];
        if ($product_id == $_product->get_id()) {


            return $val['quantity'];
        }
    }

    return 0;
}

#filter hook to remove extra add to cart button in the shop and category pages
add_filter('woocommerce_loop_add_to_cart_link', '_wcmmax_add2cart');

function _wcmmax_add2cart($link) {
    global $product;
    $product_id = $product->get_id();
    $product_sku = $product->get_sku();
    $product_type = $product->get_type();
    $qtylink = '';
    $ajax_class = '';
    $mmaxEnable = get_post_meta($product_id, '_wc_mmax_prd_opt_enable', true);
    $minQty = get_post_meta($product_id, '_wc_mmax_min', true);

    if ($product_type != 'simple' || $mmaxEnable != 1) {
        return $link;
    }
    $qtylink = '&quantity=' . $minQty;
    $ajax_cart_en = 'yes' === get_option('woocommerce_enable_ajax_add_to_cart');
    if ($ajax_cart_en && $mmaxEnable == 0 && $product_type != 'variable') { //solution by @tigerrockmartialarts https://wordpress.org/support/topic/add-to-cart-on-variable-products/
        $ajax_class = 'ajax_add_to_cart';
    }
    $link = sprintf('<a href="%s" rel="nofollow" data-product_id="%s" data-product_sku="%s" data-quantity="%s" class="button %s product_type_%s %s">%s</a>',
            esc_url($product->add_to_cart_url() . $qtylink),
            esc_attr($product_id),
            esc_attr($product->get_sku()),
            esc_attr(isset($minQty) ? $minQty : 1),
            $product->is_purchasable() && $product->is_in_stock() ? 'add_to_cart_button' : '',
            esc_attr($product->get_type()),
            esc_attr($ajax_class),
            esc_html($product->add_to_cart_text())
    );
    return $link;
}

add_action('woocommerce_check_cart_items', 'wc_mmxax_limit_cart_item_quantity_rule', 99);

function wc_mmxax_limit_cart_item_quantity_rule() {
    if (!is_cart() || !isset($_POST))
        return; // Only on cart page

    foreach (WC()->cart->cart_contents as $cart_items => $ci) {
        $minQty = get_post_meta($ci['product_id'], '_wc_mmax_min', true);
        $maxQty = get_post_meta($ci['product_id'], '_wc_mmax_max', true);
        $mmaxEnable = get_post_meta($ci['product_id'], '_wc_mmax_prd_opt_enable', true);
        $incrementbyQty = get_post_meta($ci['product_id'], '_wc_mmax_increment', true);
        $incrementbyQty = $incrementbyQty ? $incrementbyQty : 1;
        $factory = new WC_Product_Factory();
        $product = $factory->get_product($ci['product_id']);
        $mod = $ci['quantity'] % $incrementbyQty;
        $qty = wc_mmax_closestCartQuantity($ci['quantity'], $incrementbyQty);
        if ($mod > 0 && $mmaxEnable == 1) {
            WC()->cart->set_quantity($cart_items, $qty);
            if (get_option('_wcmmax_cart_error_message') != NULL && get_option('_wcmmax_cart_error_message') != '') {
                $cartQtyRuleError = get_option('_wcmmax_cart_error_message');
            } else {
                $cartQtyRuleError = __('The Quantity that you try to set voids the Increment rule for the product <strong>' . $product->get_title() . '</strong>, so the cart is updated  with possible nearest Quantity.', 'wcmmax');
            }
            wc_clear_notices();
            wc_add_notice($cartQtyRuleError, 'error');
            break;
        }
    }
}

function wc_mmax_closestCartQuantity($n, $m) {
    // find the quotient
    $q = (int) ($n / $m);

    // 1st possible closest number
    $n1 = $m * $q;

    // 2nd possible closest number
    $n2 = ($n * $m) > 0 ?
            ($m * ($q + 1)) : ($m * ($q - 1));

    // if true, then n1 is the
    // required closest number
    if (abs($n - $n1) < abs($n - $n2))
        return $n1;

    // else n2 is the required
    // closest number
    return $n2;
}
