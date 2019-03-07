<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              https://wpzen.ru
 * @since             1.0.0
 * @package           Thumbs_Rating_System
 *
 * @wordpress-plugin
 * Plugin Name:       Thumbs Rating System
 * Plugin URI:        https://github.com/wpzen/thumbs-rating-system
 * Description:       Simple and easy plugin to add content rating to the site.
 * Version:           1.0.0
 * Author:            Pleshakov Valery
 * Author URI:        https://wpzen.ru
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       thumbs-rating-system
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * Currently plugin version.
 * Start at version 1.0.0 and use SemVer - https://semver.org
 * Rename this for your plugin and update it as you release new versions.
 */
define( 'THUMBS_RATING_SYSTEM_VERSION', '1.0.0' );

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-thumbs-rating-system-activator.php
 */
function activate_thumbs_rating_system() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-thumbs-rating-system-activator.php';
	Thumbs_Rating_System_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-thumbs-rating-system-deactivator.php
 */
function deactivate_thumbs_rating_system() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-thumbs-rating-system-deactivator.php';
	Thumbs_Rating_System_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_thumbs_rating_system' );
register_deactivation_hook( __FILE__, 'deactivate_thumbs_rating_system' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-thumbs-rating-system.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_thumbs_rating_system() {

	$plugin = new Thumbs_Rating_System();
	$plugin->run();

}
run_thumbs_rating_system();
