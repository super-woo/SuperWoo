<?php

namespace springdevs\WooAdvanceCoupon;

use springdevs\WooAdvanceCoupon\Frontend\sdwac_auto;
use springdevs\WooAdvanceCoupon\Frontend\sdwac_front;
use springdevs\WooAdvanceCoupon\Frontend\sdwac_url;

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
        new sdwac_front;
        new sdwac_url;
        new sdwac_auto;
    }
}
