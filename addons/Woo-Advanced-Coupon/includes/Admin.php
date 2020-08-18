<?php

namespace superwoo_coupon\superwoo_coupon_Coupon;

use superwoo_coupon\superwoo_coupon_Coupon\Admin\superwoo_coupon_Coupon;
use superwoo_coupon\superwoo_coupon_Coupon\Admin\superwoo_coupon_Panels;
use superwoo_coupon\superwoo_coupon_Coupon\Admin\superwoo_coupon_Setting;

/**
 * The admin class
 */
class Admin
{

    /**
     * Initialize the class
     */
    public function __construct()
    {
        $this->dispatch_actions();
        $this->create_options();
        new superwoo_coupon_Coupon;
        new superwoo_coupon_Panels;
        new superwoo_coupon_Setting;
    }

    /**
     * Dispatch and bind actions
     *
     * @return void
     */
    public function dispatch_actions()
    {
    }

    /**
     * create options for plugins
     **/
    public function create_options()
    {
        if (!get_option("superwoo_coupon_first_time_purchase_coupon")) {
            add_option("superwoo_coupon_first_time_purchase_coupon", 0);
        }

        if (!get_option("superwoo_coupon_first_time_purchase_coupon_label")) {
            add_option("superwoo_coupon_first_time_purchase_coupon_label", "Discounted Amount");
        }

        if (!get_option("superwoo_coupon_woo_setting_show_product_discount")) {
            add_option("superwoo_coupon_woo_setting_show_product_discount", "yes");
        }

        if (!get_option("superwoo_coupon_woo_setting_multi")) {
            add_option("superwoo_coupon_woo_setting_multi", "yes");
        }

        if (!get_option("superwoo_coupon_woo_setting_url")) {
            add_option("superwoo_coupon_woo_setting_url", "coupon");
        }
    }
}
