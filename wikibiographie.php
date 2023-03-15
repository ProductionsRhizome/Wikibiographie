<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              https://www.productionsrhizome.org
 * @since             1.0.0
 * @package           Wikibiographie
 *
 * @wordpress-plugin
 * Plugin Name:       WikiBiographie
 * Plugin URI:        https://github.com/ProductionsRhizome/Wikibiographie
 * Description:       Fetch and manage biographies from Wikipedia.
 * Version:           1.0.5
 * Author:            Productions Rhizome
 * Author URI:        https://www.productionsrhizome.org
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       wikibiographie
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if (! defined('WPINC')) {
    die;
}

/**
 * Currently plugin version.
 * Start at version 1.0.0 and use SemVer - https://semver.org
 * Rename this for your plugin and update it as you release new versions.
 */
define('WIKIBIOGRAPHIE_VERSION', '1.0.5');

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-wikibiographie-activator.php
 */
function activate_wikibiographie()
{
    require_once plugin_dir_path(__FILE__) . 'includes/class-wikibiographie-activator.php';
    Wikibiographie_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-wikibiographie-deactivator.php
 */
function deactivate_wikibiographie()
{
    require_once plugin_dir_path(__FILE__) . 'includes/class-wikibiographie-deactivator.php';
    Wikibiographie_Deactivator::deactivate();
}

register_activation_hook(__FILE__, 'activate_wikibiographie');
register_deactivation_hook(__FILE__, 'deactivate_wikibiographie');

/**
 * The code and shortcode to display a biographie widget.
 */
function biographie($url, $post_id = null)
{
    require_once plugin_dir_path(__FILE__) . 'includes/class-wikibiographie-helper.php';
    Wikibiographie_Helper::load($url, $post_id);
}
add_shortcode('biographie', 'biographie');

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path(__FILE__) . 'includes/class-wikibiographie.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_wikibiographie()
{
    $plugin = new Wikibiographie();
    $plugin->run();
}
run_wikibiographie();
