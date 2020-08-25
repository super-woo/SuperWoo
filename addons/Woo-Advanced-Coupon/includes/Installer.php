<?php

namespace springdevs\WooAdvanceCoupon;

/**
 * Class Installer
 * @package WPGenerator
 */
class Installer
{
    /**
     * Run the installer
     *
     * @return void
     */
    public function run()
    {
        $this->checkPlugin();
        $this->create_options();
    }

    /**
     * Check if WooCommerce Exixts
     */
    public function checkPlugin()
    {
        if (!in_array('woocommerce/woocommerce.php', apply_filters('active_plugins', get_option('active_plugins')))) {
            wp_die(__("You Need to install wooCommerce for use These Plugin !!", "sdwac_coupon"), null, ['back_link' => 1]);
        }
    }

    /**
     * Create necessary database tables
     *
     * @return void
     */
    public function create_tables()
    {
        if (!function_exists('dbDelta')) {
            require_once ABSPATH . 'wp-admin/includes/upgrade.php';
        }
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
