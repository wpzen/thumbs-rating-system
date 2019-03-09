<?php

/**
 * The public-facing functionality of the plugin.
 *
 * @link       https://wpzen.ru
 * @since      1.0.0
 *
 * @package    Thumbs_Rating_System
 * @subpackage Thumbs_Rating_System/public
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the public-facing stylesheet and JavaScript.
 *
 * @package    Thumbs_Rating_System
 * @subpackage Thumbs_Rating_System/public
 * @author     Pleshakov Valery <pleshakov.valery@gmail.com>
 */
class Thumbs_Rating_System_Public {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	private $shortcode;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $plugin_name       The name of the plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;

		$this->load_dependencies();

		add_shortcode( 'thumbs_rating_system', array( $this, 'shortcode' ) );

	}

	/**
	 * Load the required dependencies for this plugin.
	 *
	 * Include the following files that make up the plugin:
	 *
	 * - Thumbs_Rating_System_Shortcode. Create shortcode.
	 *
	 * Create an instance of the shortcode which will be used to display the rating.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function load_dependencies() {

		/**
		 * The class widget popular posts.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'public/includes/class-thumbs-rating-system-widget-top-posts.php';

		/**
		 * The class responsible for displaying the rating.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'public/includes/class-thumbs-rating-system-shortcode.php';

		$this->shortcode = new Thumbs_Rating_System_Shortcode;
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
		 * defined in Thumbs_Rating_System_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Thumbs_Rating_System_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/thumbs-rating-system-public.css', array(), $this->version, 'all' );

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
		 * defined in Thumbs_Rating_System_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Thumbs_Rating_System_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_script( 'notify', plugin_dir_url( __FILE__ ) . 'js/notify.min.js', array( 'jquery' ), '1.0.0', true );

		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/thumbs-rating-system-public.js', array( 'jquery', 'notify' ), $this->version, true );

		wp_localize_script( $this->plugin_name, 'thumbs_rating_system_ajax', array(
			'url' => admin_url( 'admin-ajax.php' ),
			'nonce' => wp_create_nonce( 'thumbs-rating-system-nonce' ),
			'message' => array(
				'success' => esc_html__('Thanks for rating', 'thumbs-rating-system'),
				'error' => esc_html__('You already voted', 'thumbs-rating-system')
			)
		) );

	}

	/**
	 * Shortcode to display rating.
	 *
	 * @since    1.0.0
	 */
	public function shortcode() {

		$post_id = get_the_ID();

		echo $this->shortcode->render( $post_id );

	}

	/**
	 * Ajax vote handler.
	 *
	 * @since    1.0.0
	 */
	public function ajax_callback() {

		// Check the nonce - security
		check_ajax_referer( 'thumbs-rating-system-nonce', 'nonce' );

		// Get the POST values
		$post_id = intval( $_POST['post_id'] );
		$type_of_vote = $_POST['type'];

		$meta_name = 'thumbs_rating_' . $type_of_vote . 's';

		// Retrieve the meta value from the DB

		$thumbs_count = get_post_meta( $post_id, $meta_name, true );
		$thumbs_count = $thumbs_count ? (int) $thumbs_count + 1 : 1;

		// Update the meta value
		update_post_meta( $post_id, $meta_name, $thumbs_count );

		$shortcode = $this->shortcode->render( $post_id );

		wp_send_json( $shortcode );
		
	}

	public function register_widget_top_posts() {

		register_widget( 'Thumbs_Rating_System_Widget_Top_Posts' );

	}

	public function content_filter( $content ) {
		
		$options = get_option('thumbs_rating_system_options');

		if ( is_single() && isset( $options['show_rating'] ) && 1 === $options['show_rating'] ) {
			$content .= $this->shortcode->render( get_the_ID() );
		}

		return $content;

	}
}
