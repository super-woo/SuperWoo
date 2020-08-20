<?php
/*
Plugin Name: SuperWoo
Plugin URI: https://superwoo.net/
Description: Supercharge your WooCommerce powered store!
Version: 1.0.0
Author: Super Woo
Author URI: https://superwoo.net/
License: GPLv2
License URI: https://www.gnu.org/licenses/gpl-2.0.html
Text Domain: superwoo
Domain Path: /languages
*/

/**
 * Copyright (c) 2020 SuperWoo (email: contact@superwoo.net). All rights reserved.
 *
 * Released under the GPL license
 * http://www.opensource.org/licenses/gpl-license.php
 *
 * This is an add-on for WordPress
 * http://wordpress.org/
 *
 * **********************************************************************
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
 * **********************************************************************
 */

// don't call the file directly
if (!defined('ABSPATH')) {
    exit;
}

require_once __DIR__ . '/vendor/autoload.php';

/**
 * SuperWoo_Main class
 *
 * @class SuperWoo_Main The class that holds the entire SuperWoo_Main plugin
 */
final class SuperWoo_Main
{
    /**
     * Plugin version
     *
     * @var string
     */
    const version = '1.0.0';

    /**
     * Holds various class instances
     *
     * @var array
     */
    private $container = [];

    /**
     * Constructor for the SuperWoo_Main class
     *
     * Sets up all the appropriate hooks and actions
     * within our plugin.
     */
    private function __construct()
    {
        $this->define_constants();

        register_activation_hook(__FILE__, [$this, 'activate']);
        register_deactivation_hook(__FILE__, [$this, 'deactivate']);

        add_action('plugins_loaded', [$this, 'init_plugin']);
        $this->setAddons();
        $this->getAddons();
    }

    /**
     * Initializes the SuperWoo_Main() class
     *
     * Checks for an existing SuperWoo_Main() instance
     * and if it doesn't find one, creates it.
     *
     * @return SuperWoo_Main|bool
     */
    public static function init()
    {
        static $instance = false;

        if (!$instance) {
            $instance = new SuperWoo_Main();
        }

        return $instance;
    }

    /**
     * Magic getter to bypass referencing plugin.
     *
     * @param $prop
     *
     * @return mixed
     */
    public function __get($prop)
    {
        if (array_key_exists($prop, $this->container)) {
            return $this->container[$prop];
        }

        return $this->{$prop};
    }

    /**
     * Magic isset to bypass referencing plugin.
     *
     * @param $prop
     *
     * @return mixed
     */
    public function __isset($prop)
    {
        return isset($this->{$prop}) || isset($this->container[$prop]);
    }

    /**
     * Define the constants
     *
     * @return void
     */
    public function define_constants()
    {
        define('SUPERWOO_ASSETS_VERSION', self::version);
        define('SUPERWOO_ASSETS_FILE', __FILE__);
        define('SUPERWOO_ASSETS_PATH', dirname(SUPERWOO_ASSETS_FILE));
        define('SUPERWOO_ASSETS_INCLUDES', SUPERWOO_ASSETS_PATH . '/includes');
        define('SUPERWOO_ASSETS_URL', plugins_url('', SUPERWOO_ASSETS_FILE));
        define('SUPERWOO_ASSETS_ASSETS', SUPERWOO_ASSETS_URL . '/assets');
    }

    /**
     * Load the plugin after all plugins are loaded
     *
     * @return void
     */
    public function init_plugin()
    {
        $this->includes();
        $this->init_hooks();
    }

    /**
     * Placeholder for activation function
     *
     * Nothing being called here yet.
     */
    public function activate()
    {
        $installer = new WpAdroit\SuperWoo\Installer();
        $installer->run();
    }

    /**
     * Placeholder for deactivation function
     *
     * Nothing being called here yet.
     */
    public function deactivate()
    {
    }

    /**
     * Include the required files
     *
     * @return void
     */
    public function includes()
    {
        if ($this->is_request('admin')) {
            $this->container['admin'] = new WpAdroit\SuperWoo\Admin();
        }

        if ($this->is_request('frontend')) {
            $this->container['frontend'] = new WpAdroit\SuperWoo\Frontend();
        }

        if ($this->is_request('ajax')) {
            // require_once SUPERWOO_ASSETS_INCLUDES . '/class-ajax.php';
        }
    }

    /**
     * Initialize the hooks
     *
     * @return void
     */
    public function init_hooks()
    {
        add_action('init', [$this, 'init_classes']);

        // Localize our plugin
        add_action('init', [$this, 'localization_setup']);
    }

    /**
     * set Addons
     **/
    public function setAddons()
    {
        $addons = [
            "Woo-Advanced-Coupon" => "woo-advance-coupon.php"
        ];
        do_action("register_superwoo_addons", $addons);
        update_option("superwoo_addons", $addons);
        return;
    }

    /**
     * Get All Addons
     **/
    public function getAddons()
    {
        $addons = get_option("superwoo_addons");
        foreach ($addons as $key => $value) {
            require_once __DIR__ . '/addons/' . $key . '/' . $value;
        }
        return;
    }

    /**
     * Instantiate the required classes
     *
     * @return void
     */
    public function init_classes()
    {
        if ($this->is_request('ajax')) {
            // $this->container['ajax'] =  new WpAdroit\SuperWoo\Ajax();
        }

        $this->container['api']    = new WpAdroit\SuperWoo\Api();
        $this->container['assets'] = new WpAdroit\SuperWoo\Assets();
    }

    /**
     * Initialize plugin for localization
     *
     * @uses load_plugin_textdomain()
     */
    public function localization_setup()
    {
        load_plugin_textdomain('superwoo', false, dirname(plugin_basename(__FILE__)) . '/languages/');
    }

    /**
     * What type of request is this?
     *
     * @param string $type admin, ajax, cron or frontend.
     *
     * @return bool
     */
    private function is_request($type)
    {
        switch ($type) {
            case 'admin':
                return is_admin();

            case 'ajax':
                return defined('DOING_AJAX');

            case 'rest':
                return defined('REST_REQUEST');

            case 'cron':
                return defined('DOING_CRON');

            case 'frontend':
                return (!is_admin() || defined('DOING_AJAX')) && !defined('DOING_CRON');
        }
    }
} // SuperWoo_Main

/**
 * Initialize the main plugin
 *
 * @return \SuperWoo_Main|bool
 */
function superwoo_main()
{
    return SuperWoo_Main::init();
}

/**
 *  kick-off the plugin
 */
superwoo_main();
