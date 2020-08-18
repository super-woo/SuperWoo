<?php

namespace superwoo_coupon\superwoo_coupon_Coupon\Frontend;

/**
 * Class superwoo_coupon_url
 * control url apply coupon system
 */
class superwoo_coupon_url
{
    public function __construct()
    {
        // set coupon on session via GET method
        add_action('init', [$this, "superwoo_coupon_set_coupon_url"]);
        // apply when add to cart
        add_action("woocommerce_add_to_cart", [$this, "superwoo_coupon_apply_coupon"]);
        // apply coupon on session via GET method
        add_action("woocommerce_before_cart", [$this, "superwoo_coupon_apply_coupon_via_url"]);
    }

    /**
     * WooCommerce set coupon on session
     *
     **/
    public function superwoo_coupon_set_coupon_url()
    {
        $url = get_option("superwoo_coupon_woo_setting_url");
        if (isset($_GET[$url])) {
            $coupon_code = esc_attr($_GET[$url]);
            WC()->session->set('coupon_code', $coupon_code);
        }
    }

    /**
     * WooCommerce apply coupon when add-to-cart
     *
     **/
    public function superwoo_coupon_apply_coupon()
    {
        $this->superwoo_coupon_apply_coupon_via_url();
    }

    /**
     * WooCommerce apply coupon from session
     *
     **/
    public function superwoo_coupon_apply_coupon_via_url()
    {
        $coupons = WC()->cart->get_applied_coupons();
        $code = WC()->session->get('coupon_code');
        if ($code) {
            if (in_array($code, $coupons)) {
                WC()->session->__unset('coupon_code');
            } else {
                WC()->cart->apply_coupon($code);
                WC()->session->__unset('coupon_code');
            }
        }
    }
}
