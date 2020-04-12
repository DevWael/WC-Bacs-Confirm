<?php

/**
 * The public-facing functionality of the plugin.
 *
 * @link       https://github.com/devwael
 * @since      1.0.0
 *
 * @package    Wbc
 * @subpackage Wbc/public
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the public-facing stylesheet and JavaScript.
 *
 * @package    Wbc
 * @subpackage Wbc/public
 * @author     AhmadWael <dev.ahmedwael@gmail.com>
 */
class Wbc_Public {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string $plugin_name The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string $version The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @param string $plugin_name The name of the plugin.
	 * @param string $version The version of this plugin.
	 *
	 * @since    1.0.0
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version     = $version;

	}

	/**
	 * Register the stylesheets for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Wbc_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Wbc_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/wbc-public.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Wbc_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Wbc_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/wbc-public.js', array( 'jquery' ), $this->version, false );
		wp_localize_script( $this->plugin_name, 'wbc_object', array(
			'ajax_url'          => admin_url( 'admin-ajax.php' ),
			'max_file_size_msg' => __( 'Allowed file size exceeded. (Max. 2 MB)', 'wbc' ),
			'request_received_msg' => __( 'Your request has been received', 'wbc' ),
		) );
	}

	public function display_form( $order_id ) {
		include WBC_DIR . 'public/partials/wbc-public-display.php';
	}

}
