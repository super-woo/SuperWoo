<?php

namespace superwoo_coupon\superwoo_coupon_Coupon\Admin;

/**
 * superwoo_coupon_Panel class
 * Woocommerce Custom Tabs
 */
class superwoo_coupon_Panels
{
    public function __construct()
    {
        add_filter('woocommerce_coupon_data_tabs', [$this, 'superwoo_coupon_data_tabs'], 100, 1);
        add_filter('woocommerce_coupon_data_panels', [$this, 'superwoo_coupon_tabs_screen']);
        add_action('save_post', [$this, 'superwoo_coupon_save_coupon_data']);
    }

    public function superwoo_coupon_save_coupon_data($post_id)
    {
        if (isset($_POST["post_type"])) {
            if (!isset($_POST["superwoo_coupon_feature"]) & $_POST["post_type"] != "shop_coupon") {
                return;
            }
            $superwoo_coupon_data = [
                "list_id" => $_POST["superwoo_coupon_feature"],
                "overwrite_discount" => $_POST["overwrite_discount"]
            ];
            update_post_meta($post_id, "superwoo_coupon_coupon_panel", $superwoo_coupon_data);
        }
    }

    public function superwoo_coupon_data_tabs($tabs)
    {
        $tabs['superwoo_coupon_features'] = array(
            'label'     => __('Woo Coupon', 'superwoo_coupon'),
            'class'  => 'superwoo_coupon_coupon_panel',
            'target'     => 'superwoo_coupon_tabs_screen'
        );
        return $tabs;
    }

    public function superwoo_coupon_tabs_screen()
    {
?>
        <div id="superwoo_coupon_tabs_screen" class="panel woocommerce_options_panel">
            <div id="post">
                <superwoo_coupontabs />
            </div>
        </div>
<?php
    }
}
