<?php

/**
 * Fired during plugin activation
 *
 * @link       https://www.cmsminds.com
 * @since      1.0.0
 *
 * @package    Custom_Contact_Form
 * @subpackage Custom_Contact_Form/includes
 */

/**
 * Fired during plugin activation.
 *
 * This class defines all code necessary to run during the plugin's activation.
 *
 * @since      1.0.0
 * @package    Custom_Contact_Form
 * @subpackage Custom_Contact_Form/includes
 * @author     Rizwan Shaikh <rizwan@cmsminds.com>
 */
class Custom_Contact_Form_Activator {

	/**
	 * Short Description. (use period)
	 *
	 * Long Description.
	 *
	 * @since    1.0.0
	 */
	public static function activate() {

		global $wpdb;
  		$charset_collate = $wpdb->get_charset_collate();
  		require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );

  		//* Create the teams table
  		$table_name = $wpdb->prefix . 'custom_form_data';
  		$sql = "CREATE TABLE $table_name (
    		id INTEGER NOT NULL AUTO_INCREMENT,
    		form TEXT NOT NULL,
    		first_name TEXT NOT NULL,
    		last_name TEXT NOT NULL,
    		email TEXT NOT NULL,
    		contact TEXT NULL,
    		message TEXT NULL,
    		date datetime,
    		PRIMARY KEY (id)
  		) $charset_collate;";
  		dbDelta( $sql );
	}
}
