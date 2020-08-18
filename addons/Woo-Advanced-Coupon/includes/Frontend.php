<?php

namespace superwoo_coupon\superwoo_coupon_Coupon;

use superwoo_coupon\superwoo_coupon_Coupon\Frontend\superwoo_coupon_auto;
use superwoo_coupon\superwoo_coupon_Coupon\Frontend\superwoo_coupon_front;
use superwoo_coupon\superwoo_coupon_Coupon\Frontend\superwoo_coupon_url;

/**
 * Frontend handler class
 */
class Frontend
{
    /**
     * Frontend constructor.
     */
    public function __construct()
    {
        new superwoo_coupon_front;
        new superwoo_coupon_url;
        new superwoo_coupon_auto;
    }
}
