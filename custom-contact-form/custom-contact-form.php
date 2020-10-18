<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              https://www.cmsminds.com
 * @since             1.0.0
 * @package           Custom_Contact_Form
 *
 * @wordpress-plugin
 * Plugin Name:       Custom Contact Form
 * Plugin URI:        https://github.com/Rizz50/wp-custom-contact-form-plugin
 * Description:       Use [custom_form_sc] to display plugins custom form anywhere.
 * Version:           1.0.0
 * Author:            Rizwan Shaikh
 * Author URI:        https://www.cmsminds.com
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       custom-contact-form
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
define( 'CUSTOM_CONTACT_FORM_VERSION', '1.0.0' );

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-custom-contact-form-activator.php
 */
function activate_custom_contact_form() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-custom-contact-form-activator.php';
	Custom_Contact_Form_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-custom-contact-form-deactivator.php
 */
function deactivate_custom_contact_form() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-custom-contact-form-deactivator.php';
	Custom_Contact_Form_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_custom_contact_form' );
register_deactivation_hook( __FILE__, 'deactivate_custom_contact_form' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-custom-contact-form.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_custom_contact_form() {

	$plugin = new Custom_Contact_Form();
	$plugin->run();

}
run_custom_contact_form();
