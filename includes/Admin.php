<?php

namespace SpringDevs\WcMissingAddons;

use SpringDevs\WcMissingAddons\Admin\Menu;

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
        new Menu;
    }

    /**
     * Dispatch and bind actions
     *
     * @return void
     */
    public function dispatch_actions()
    {
    }
}
