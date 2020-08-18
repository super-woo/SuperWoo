<?php

namespace superwoo_coupon\superwoo_coupon_Coupon\Frontend\Helpers;

/**
 * Coupon Apply class
 */
class Apply
{
    /**
     * Apply Discount
     * $coupon is superwoo_coupon_coupon
     **/
    public function apply_discount($coupon)
    {
        $cart = WC()->cart;
        $superwoo_coupon_main = get_post_meta($coupon, "superwoo_coupon_coupon_main", true);
        if (!$superwoo_coupon_main) {
            return false;
        }
        $superwoo_coupon_discounts = get_post_meta($coupon, "superwoo_coupon_coupon_discounts", true);
        $superwoo_coupon_coupon_type = $superwoo_coupon_main["type"];
        $discount_amount = 0;
        $discount_label = get_option("superwoo_coupon_first_time_purchase_coupon_label");
        if ($superwoo_coupon_coupon_type != "product") {
            if (isset($superwoo_coupon_main["label"]) || !empty($superwoo_coupon_main["label"] || !$superwoo_coupon_main["label"] == '')) {
                $discount_label = $superwoo_coupon_main["label"];
            }
        }
        if ($superwoo_coupon_coupon_type == "cart") {
            switch ($superwoo_coupon_discounts["type"]) {
                case 'percentage':
                    $discount_total = ($superwoo_coupon_discounts["value"] / 100) * $cart->subtotal;
                    break;
                case 'fixed':
                    $discount_total = $superwoo_coupon_discounts["value"];
                    break;
            }
            $discount_amount += $discount_total;
        } else if ($superwoo_coupon_coupon_type == "product") {
            $first_coupon          = get_option("superwoo_coupon_first_time_purchase_coupon");
            $superwoo_coupon_first_coupon_main = false;
            if ($first_coupon != 0) {
                $superwoo_coupon_first_coupon_main = get_post_meta($first_coupon, "superwoo_coupon_coupon_main", true);
                if ($superwoo_coupon_first_coupon_main) {
                    if ($superwoo_coupon_first_coupon_main["type"] == "product") {
                        $superwoo_coupon_first_coupon_main = true;
                    } else {
                        $superwoo_coupon_first_coupon_main = false;
                    }
                } else {
                    update_option("superwoo_coupon_first_time_purchase_coupon", 0);
                }
            }
            if ($superwoo_coupon_first_coupon_main) {
                WC()->session->set("superwoo_coupon_product_coupon", [
                    "first_coupon" => "yes"
                ]);
            } else {
                $items = [];
                array_push($items, $coupon);
                WC()->session->set("superwoo_coupon_product_coupon", [
                    "first_coupon" => "no",
                    "items" => $items
                ]);
            }
            return false;
        } else if ($superwoo_coupon_coupon_type == "bulk") {
            foreach ($superwoo_coupon_discounts as $superwoo_coupon_discount) {
                if ($superwoo_coupon_discount["min"] <= $cart->subtotal && $superwoo_coupon_discount["max"] >= $cart->subtotal) {
                    switch ($superwoo_coupon_discount["type"]) {
                        case 'percentage':
                            $discount_total = ($superwoo_coupon_discount["value"] / 100) * $cart->subtotal;
                            break;
                        case 'fixed':
                            $discount_total = $superwoo_coupon_discount["value"];
                            break;
                    }
                    $discount_amount += $discount_total;
                }
            }
        }
        return [
            "label" => $discount_label,
            "amount" => $discount_amount
        ];
    }
}
