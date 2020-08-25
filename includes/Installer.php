<?php

namespace SpringDevs\WcMissingAddons;

/**
 * Class Installer
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
        $this->add_version();
        $this->create_tables();
    }

    /**
     * Add time and version on DB
     */
    public function add_version()
    {
        $installed = get_option('springdevs_wma_installed');

        if (!$installed) {
            update_option('springdevs_wma_installed', time());
        }

        update_option('springdevs_wma_version', springdevs_wma_ASSETS_VERSION);
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
}
