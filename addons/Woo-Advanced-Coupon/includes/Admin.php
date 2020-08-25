<?php

namespace springdevs\WooAdvanceCoupon;

use springdevs\WooAdvanceCoupon\Admin\sdwac_Coupon;
use springdevs\WooAdvanceCoupon\Admin\sdwac_Panels;
use springdevs\WooAdvanceCoupon\Admin\sdwac_Setting;

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
        new sdwac_Coupon;
        new sdwac_Panels;
        new sdwac_Setting;
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
        if (!get_option("sdwac_first_time_purchase_coupon")) {
            add_option("sdwac_first_time_purchase_coupon", 0);
        }

        if (!get_option("sdwac_first_time_purchase_coupon_label")) {
            add_option("sdwac_first_time_purchase_coupon_label", "Discounted Amount");
        }

        if (!get_option("sdwac_show_product_discount")) {
            add_option("sdwac_show_product_discount", "yes");
        }

        if (!get_option("sdwac_multi")) {
            add_option("sdwac_multi", "yes");
        }

        if (!get_option("sdwac_url")) {
            add_option("sdwac_url", "coupon");
        }
    }
}
