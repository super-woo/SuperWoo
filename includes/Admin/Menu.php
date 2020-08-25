<?php

namespace SpringDevs\WcMissingAddons\Admin;

/**
 * Admin Pages Handler
 *
 * Class Menu
 */
class Menu
{
    /**
     * Menu constructor.
     */
    public function __construct()
    {
        add_action('admin_menu', [$this, 'admin_menu']);
    }

    /**
     * Register our menu page
     *
     * @return void
     */
    public function admin_menu()
    {
        $parent_slug = 'springdevs-addons';
        $capability = 'manage_options';
        $hook = add_menu_page(__('Missing Addons', 'springdevs_wma'), __('Missing Addons', 'springdevs_wma'), $capability, $parent_slug, [$this, 'plugin_page'], 'dashicons-image-filter', 40);
        add_action('load-' . $hook, [$this, 'init_hooks']);
    }

    /**
     * Initialize our hooks for the admin page
     *
     * @return void
     */
    public function init_hooks()
    {
        add_action('admin_enqueue_scripts', [$this, 'enqueue_scripts']);
    }

    /**
     * Load scripts and styles for the app
     *
     * @return void
     */
    public function enqueue_scripts()
    {
        // wp_enqueue_style( 'admin' );
        // wp_enqueue_script( 'admin' );
    }

    /**
     * Handles the main page
     *
     * @return void
     */
    public function plugin_page()
    {
        echo '<div class="wrap">WP Generator is a plugin generator tool for developers.</div>';
    }
}
