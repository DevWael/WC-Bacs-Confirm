<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              https://github.com/devwael
 * @since             1.0.0
 * @package           Wbc
 *
 * @wordpress-plugin
 * Plugin Name:       WC Bacs Confirm
 * Plugin URI:        https://innoshop.co/
 * Description:       add a form to help customers upload image for confirming their bank transfers
 * Version:           1.1.0
 * Author:            AhmadWael
 * Author URI:        https://github.com/devwael
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       wbc
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
define( 'WBC_VERSION', '1.1.0' );
define( 'WBC_DIR', plugin_dir_path( __FILE__ ) );

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-wbc-activator.php
 */
function activate_wbc() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-wbc-activator.php';
	Wbc_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-wbc-deactivator.php
 */
function deactivate_wbc() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-wbc-deactivator.php';
	Wbc_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_wbc' );
register_deactivation_hook( __FILE__, 'deactivate_wbc' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-wbc.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_wbc() {

	$plugin = new Wbc();
	$plugin->run();

}

run_wbc();
