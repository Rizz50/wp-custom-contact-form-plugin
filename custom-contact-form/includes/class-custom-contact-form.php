<?php

/**
 * The file that defines the core plugin class
 *
 * A class definition that includes attributes and functions used across both the
 * public-facing side of the site and the admin area.
 *
 * @link       https://www.cmsminds.com
 * @since      1.0.0
 *
 * @package    Custom_Contact_Form
 * @subpackage Custom_Contact_Form/includes
 */

/**
 * The core plugin class.
 *
 * This is used to define internationalization, admin-specific hooks, and
 * public-facing site hooks.
 *
 * Also maintains the unique identifier of this plugin as well as the current
 * version of the plugin.
 *
 * @since      1.0.0
 * @package    Custom_Contact_Form
 * @subpackage Custom_Contact_Form/includes
 * @author     Rizwan Shaikh <rizwan@cmsminds.com>
 */
class Custom_Contact_Form {

	/**
	 * The loader that's responsible for maintaining and registering all hooks that power
	 * the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      Custom_Contact_Form_Loader    $loader    Maintains and registers all hooks for the plugin.
	 */
	protected $loader;

	/**
	 * The unique identifier of this plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $plugin_name    The string used to uniquely identify this plugin.
	 */
	protected $plugin_name;

	/**
	 * The current version of the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $version    The current version of the plugin.
	 */
	protected $version;

	/**
	 * Define the core functionality of the plugin.
	 *
	 * Set the plugin name and the plugin version that can be used throughout the plugin.
	 * Load the dependencies, define the locale, and set the hooks for the admin area and
	 * the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function __construct() {
		if ( defined( 'CUSTOM_CONTACT_FORM_VERSION' ) ) {
			$this->version = CUSTOM_CONTACT_FORM_VERSION;
		} else {
			$this->version = '1.0.0';
		}
		$this->plugin_name = 'custom-contact-form';

		$this->load_dependencies();
		$this->set_locale();
		$this->define_admin_hooks();
		$this->define_public_hooks();

	}

	/**
	 * Load the required dependencies for this plugin.
	 *
	 * Include the following files that make up the plugin:
	 *
	 * - Custom_Contact_Form_Loader. Orchestrates the hooks of the plugin.
	 * - Custom_Contact_Form_i18n. Defines internationalization functionality.
	 * - Custom_Contact_Form_Admin. Defines all hooks for the admin area.
	 * - Custom_Contact_Form_Public. Defines all hooks for the public side of the site.
	 *
	 * Create an instance of the loader which will be used to register the hooks
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function load_dependencies() {

		/**
		 * The class responsible for orchestrating the actions and filters of the
		 * core plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-custom-contact-form-loader.php';

		/**
		 * The class responsible for defining internationalization functionality
		 * of the plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-custom-contact-form-i18n.php';

		/**
		 * The class responsible for defining all actions that occur in the admin area.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-custom-contact-form-admin.php';

		/**
		 * The class responsible for defining all actions that occur in the public-facing
		 * side of the site.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'public/class-custom-contact-form-public.php';

		$this->loader = new Custom_Contact_Form_Loader();

	}

	/**
	 * Define the locale for this plugin for internationalization.
	 *
	 * Uses the Custom_Contact_Form_i18n class in order to set the domain and to register the hook
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function set_locale() {

		$plugin_i18n = new Custom_Contact_Form_i18n();

		$this->loader->add_action( 'plugins_loaded', $plugin_i18n, 'load_plugin_textdomain' );

	}

	/**
	 * Register all of the hooks related to the admin area functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_admin_hooks() {

		$plugin_admin = new Custom_Contact_Form_Admin( $this->get_plugin_name(), $this->get_version() );

		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_styles' );
		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_scripts' );

		$this->loader->add_ajax_action( 'admin_menu', array( $this, 'my_add_menu_items' ) );
	}

	/**
	 * Register all of the hooks related to the public-facing functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_public_hooks() {

		$plugin_public = new Custom_Contact_Form_Public( $this->get_plugin_name(), $this->get_version() );

		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_styles' );
		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_scripts' );

		$this->loader->add_ajax_action( 'wp_ajax_nopriv_save_custom_form_data', array( $this, 'save_custom_form_data' ) );

		$this->loader->add_ajax_action( 'wp_ajax_save_custom_form_data', array( $this, 'save_custom_form_data' ) );


		$this->loader->add_shortcode( 'custom_form_sc', array( $this, 'create_custom_form' ) );
	}

	/**
	 * Run the loader to execute all of the hooks with WordPress.
	 *
	 * @since    1.0.0
	 */
	public function run() {
		$this->loader->run();
	}

	/**
	 * The name of the plugin used to uniquely identify it within the context of
	 * WordPress and to define internationalization functionality.
	 *
	 * @since     1.0.0
	 * @return    string    The name of the plugin.
	 */
	public function get_plugin_name() {
		return $this->plugin_name;
	}

	/**
	 * The reference to the class that orchestrates the hooks with the plugin.
	 *
	 * @since     1.0.0
	 * @return    Custom_Contact_Form_Loader    Orchestrates the hooks of the plugin.
	 */
	public function get_loader() {
		return $this->loader;
	}

	/**
	 * Retrieve the version number of the plugin.
	 *
	 * @since     1.0.0
	 * @return    string    The version number of the plugin.
	 */
	public function get_version() {
		return $this->version;
	}

	public function create_custom_form() {
		$custom_html = "
			<form id='custom-form' name='custom-form'>
				<div class='form-group'>
				 	<label for='firstName'>First Name</label>
				    <input type='text' class='form-control' id='firstName' aria-describedby='' placeholder='First Name'>
				</div>
				<div class='form-group'>
				 	<label for='lastName'>Last Name</label>
				    <input type='text' class='form-control' id='lastName' aria-describedby='' placeholder='Last Name'>
				</div>
				<div class='form-group'>
				 	<label for='email'>Email</label>
				    <input type='text' class='form-control' id='email' aria-describedby='' placeholder='Email'>
				</div>
				<div class='form-group'>
					<label for='contact'>Contact</label>
			    	<input type='tel'  class='form-control' id='contact' placeholder='Contact'>
				</div>
				<div class='form-group'>
					<label for='message'>Message</label>
			    	<textarea class='form-control' id='message' rows='3' placeholder='Message'></textarea>
				</div>
				<div class='form-group'>
					<label for='submit'></label>
					<button type='submit' class='btn btn-primary custom-submit-btn'>Submit</button>
				</div>
				<div class='custom-success-msg'></div>
				<div class='custom-error-msg'></div>
			</form>
		";

		return $custom_html;
	}



	public function save_custom_form_data() {
		// Check for nonce security
	    $nonce = $_POST['custom_nonce'];

	    if ( ! wp_verify_nonce( $nonce, 'ajax-nonce' ) ) {
	    	$data = 'WP nonce error!!';
		} else {
			global $wpdb;
			$insert_query = $wpdb->insert( $wpdb->prefix . 'custom_form_data', 
			    array( 
			      'form'  => $_POST['form'], 
			      'first_name' => $_POST['first_name'],
			      'last_name' => $_POST['last_name'],
			      'email' => $_POST['email'],
			      'contact' => $_POST['contact'],
			      'message' => $_POST['message'],
			      'date' => date('Y-m-d H:i:s')
			    )
			);
			
			if( $insert_query ) {
				$data = 'Success';		
			}
		}
		wp_send_json_success( array(
    		'data' => $data
    	) );
		die();
	}


	// Custom post_type in menu
	public function my_add_menu_items(){
		$hook = add_menu_page( 'Custom contact form data', 'Custom contact form data list', 'activate_plugins', 'custom_contact_form', array( $this, 'render_custom_contact_form_list_page' ) );
	}


	public function render_custom_contact_form_list_page(){
	 	global $myCustomContactFormClass;
		$option = 'per_page';
		$args = array(
	    	'label' => 'Column',
	    	'default' => 10,
	    	'option' => 'columns_per_page'
	  	);
	  	add_screen_option( $option, $args );
	  	require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/custom-contact-form-class.php';
	  	$myCustomContactFormClass = new Custom_Contact_Form_Class();

	  	echo '<div class="wrap"><h2>Custom contact form data list</h2>'; 
	  	$myCustomContactFormClass->prepare_items(); 
    	$myCustomContactFormClass->display(); 
    	echo '</div>'; 
	}
}
