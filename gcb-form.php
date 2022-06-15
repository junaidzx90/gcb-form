<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              https://www.fiverr.com/junaidzx90
 * @since             1.0.0
 * @package           Gcb_Form
 *
 * @wordpress-plugin
 * Plugin Name:       GCB Form
 * Plugin URI:        https://www.fiverr.com
 * Description:       This is a short description of what the plugin does. It's displayed in the WordPress admin area.
 * Version:           1.0.1
 * Author:            Developer Junayed
 * Author URI:        https://www.fiverr.com/junaidzx90
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       gcb-form
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

$gcbRegAlerts = null;
$gcbloginAlerts = null;
/**
 * Currently plugin version.
 * Start at version 1.0.0 and use SemVer - https://semver.org
 * Rename this for your plugin and update it as you release new versions.
 */
define( 'GCB_FORM_VERSION', '1.0.1' );

function get_page_url_by_shortcode($shortcode){
	global $wpdb;
	return $wpdb->get_var("SELECT ID FROM {$wpdb->prefix}posts WHERE post_content LIKE '%[$shortcode]%' AND post_type = 'page'");
}
/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-gcb-form-activator.php
 */
function activate_gcb_form() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-gcb-form-activator.php';
	Gcb_Form_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-gcb-form-deactivator.php
 */
function deactivate_gcb_form() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-gcb-form-deactivator.php';
	Gcb_Form_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_gcb_form' );
register_deactivation_hook( __FILE__, 'deactivate_gcb_form' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-gcb-form.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_gcb_form() {

	$plugin = new Gcb_Form();
	$plugin->run();

}
run_gcb_form();
