<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       https://wpzen.ru
 * @since      1.0.0
 *
 * @package    Thumbs_Rating_System
 * @subpackage Thumbs_Rating_System/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Thumbs_Rating_System
 * @subpackage Thumbs_Rating_System/admin
 * @author     Pleshakov Valery <pleshakov.valery@gmail.com>
 */
class Thumbs_Rating_System_Admin {

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

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $plugin_name       The name of this plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;

	}

	/**
	 * Register the stylesheets for the admin area.
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

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/thumbs-rating-system-admin.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the admin area.
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

		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/thumbs-rating-system-admin.js', array( 'jquery' ), $this->version, false );

	}

	/**
	 * Аdd rating columns.
	 *
	 * @since    1.0.0
	 */
	public function add_columns( $columns ) {

		return array_merge( $columns, array(
			'thumbs_rating_likes' =>  esc_html__( 'Likes', 'thumbs-rating-system' ),
	        'thumbs_rating_dislikes' => esc_html__( 'Dislikes', 'thumbs-rating-system' )
	    ) );

	}

	/**
	 * Аdd values ​​to the rating columns.
	 *
	 * @since    1.0.0
	 */
	public function add_column_values( $column, $post_id ) {
		
		if ( 'thumbs_rating_likes' == $column ) {
			echo (int) get_post_meta( $post_id, 'thumbs_rating_likes', true );
		} else if ( 'thumbs_rating_dislikes' == $column ) {
			echo (int) get_post_meta( $post_id, 'thumbs_rating_dislikes', true );
		}

	}

	/**
	 * Add action to all post types to make the columns sortable.
	 * 
	 * @since    1.0.0
	 */
	public function sort_all_public_post_types() {

		$post_types = get_post_types( array(
			'public' => true
		), 'names' );
		
		foreach ( $post_types as $post_type ) {

			add_action( 'manage_edit-' . $post_type . '_sortable_columns', array( $this, 'add_sortable_columns' ) );
		
		}

	}

	/**
	 * Make our columns are sortable.
	 * 
	 * @since    1.0.0
	 */
	public function add_sortable_columns( $columns ) {

		$columns['thumbs_rating_likes'] = 'thumbs_rating_likes';
		$columns['thumbs_rating_dislikes'] = 'thumbs_rating_dislikes';
		
		return $columns;

	}

	/**
	 * Change query arguments to sort by custom field.
	 * 
	 * @since    1.0.0
	 */
	public function add_request_columns( $vars ) {

		if ( isset( $vars['orderby'] ) && 'thumbs_rating_likes' == $vars['orderby'] ) {
			$vars['meta_key'] = 'thumbs_rating_likes';
			$vars['orderby'] = 'meta_value_num';
		}

		if ( isset( $vars['orderby'] ) && 'thumbs_rating_dislikes' == $vars['orderby'] ) {
			$vars['meta_key'] = 'thumbs_rating_dislikes';
			$vars['orderby'] = 'meta_value_num';
		}

		return $vars;

	}

	/**
	 * Add meta box to the post editing page.
	 * 
	 * @since    1.0.0
	 */
	public function add_custom_meta_box() {

		add_meta_box(
			'thumbs_rating_system_default_values',
			'Thumbs Rating System',
			array( $this, 'meta_box_callback' )
		);

	}

	/**
	 * Add fields to the meta box.
	 * 
	 * @since    1.0.0
	 * @param    WP_Post    $post    Post object.
	 */
	public function meta_box_callback( $post, $meta ) {

		$screens = $meta['args'];

		$thumbs_likes = (int) get_post_meta( $post->ID, 'thumbs_rating_likes', true );
		$thumbs_dislikes = (int) get_post_meta( $post->ID, 'thumbs_rating_dislikes', true );

		// Use nonce for verification
		wp_nonce_field( plugin_basename(__FILE__), 'thumbs_rating_system_nonce' );

		$template_path = plugin_dir_path( dirname( __FILE__ ) ) . 'admin/partials/thumbs-rating-system-admin-display.php';

		require( $template_path );

	}

	/**
	 * Save meta box settings.
	 * 
	 * @since    1.0.0
	 * @param    integer    $post_id    Post ID.
	 */
	public function save_meta_box( $post_id ) {

		if ( ! isset( $_POST['thumbs_rating_likes'] ) )
			return;

		if ( ! isset( $_POST['thumbs_rating_dislikes'] ) )
			return;

		// check the nonce of our page because save_post can be called from another location
		if ( ! wp_verify_nonce( $_POST['thumbs_rating_system_nonce'], plugin_basename(__FILE__) ) )
			return;

		// if it is a auto save do nothing
		if ( defined('DOING_AUTOSAVE') && DOING_AUTOSAVE ) 
			return;

		// check the rights of the user
		if( ! current_user_can( 'edit_post', $post_id ) )
			return;

		// It's OK. Now we need to find and save the data
		// Clear the value of the input field
		$thumbs_likes = sanitize_text_field( $_POST['thumbs_rating_likes'] );
		$thumbs_dislikes = sanitize_text_field( $_POST['thumbs_rating_dislikes'] );

		// Update the data in the database
		update_post_meta( $post_id, 'thumbs_rating_likes', $thumbs_likes );
		update_post_meta( $post_id, 'thumbs_rating_dislikes', $thumbs_dislikes );
	}

	/**
	 * Create a plugin settings page.
	 *
	 * @since    1.0.0
	 */
	public function add_plugin_page() {
		add_options_page(
			esc_html__( 'Thumbs Rating System Settings', 'thumbs-rating-system' ),
			'Thumbs Rating System',
			'manage_options',
			'thumbs-rating-system',
			array( $this, 'options_page_output' )
		);
	}

	/**
	 * Add output to settings page.
	 *
	 * @since    1.0.0
	 */
	public function options_page_output() {
	?>
		<div class="wrap">
			<h1><?php echo get_admin_page_title() ?></h1>

			<form action="options.php" method="POST">
				<?php
					settings_fields( 'thumbs_rating_system_options_group' );
					do_settings_sections( 'thumbs_rating_system_admin' );
					submit_button();
				?>
			</form>
		</div>
		<?php
	}

	/**
	 * Register settings and add fields to them.
	 *
	 * @since    1.0.0
	 */
	public function plugin_settings() {
		
		register_setting(
			'thumbs_rating_system_options_group',
			'thumbs_rating_system_options',
			array( $this, 'sanitize_callback' )
		);

		add_settings_section( 'thumbs_rating_system_default', '', '', 'thumbs_rating_system_admin' ); 

		add_settings_field(
			'title_text',
			esc_html__( 'Title text', 'thumbs-rating-system' ),
			array( $this, 'field_title_text' ),
			'thumbs_rating_system_admin',
			'thumbs_rating_system_default'
		);
		
		add_settings_field(
			'enable_rich_snippets',
			esc_html__( 'Enable Google Rich Snippets?', 'thumbs-rating-system' ),
			array( $this, 'field_enable_rich_snippets' ),
			'thumbs_rating_system_admin',
			'thumbs_rating_system_default'
		);

		add_settings_field(
			'enable_stats',
			esc_html__( 'Enable rating statistics?', 'thumbs-rating-system' ),
			array( $this, 'field_enable_stats' ),
			'thumbs_rating_system_admin',
			'thumbs_rating_system_default'
		);

		add_settings_field(
			'show_rating',
			esc_html__( 'Where to display rating?', 'thumbs-rating-system' ),
			array( $this, 'field_show_rating' ),
			'thumbs_rating_system_admin',
			'thumbs_rating_system_default'
		);
	}

	/**
	 *  Add field output.
	 *
	 * @since    1.0.0
	 */
	function field_title_text() {
		$val = $this->get_field_option( 'title_text' );
		?>
		<input type="text" name="thumbs_rating_system_options[title_text]" value="<?php echo esc_attr( $val ) ?>" />
		<?php
	}

	/**
	 *  Add field output.
	 *
	 * @since    1.0.0
	 */
	function field_enable_rich_snippets() {
		$val = $this->get_field_option( 'enable_rich_snippets' );
		?>
		<label>
			<input type="checkbox" name="thumbs_rating_system_options[enable_rich_snippets]" value="1" <?php checked( 1, $val ) ?>>
			<?php esc_html_e( 'Show stars in snippet', 'thumbs-rating-system' ); ?> 
		</label>
		<?php
	}

	/**
	 *  Add field output.
	 *
	 * @since    1.0.0
	 */
	function field_enable_stats() {
		$val = $this->get_field_option( 'enable_stats' );
		?>
		<label>
			<input type="checkbox" name="thumbs_rating_system_options[enable_stats]" value="1" <?php checked( 1, $val ) ?>>
			<?php esc_html_e( 'Show stats under rating', 'thumbs-rating-system' ); ?> 
		</label>
		<?php
	}

	/**
	 * Add field output.
	 *
	 * @since    1.0.0
	 */
	function field_show_rating() {
		$val = $this->get_field_option( 'show_rating' );
		?>
		<select name="thumbs_rating_system_options[show_rating]">
			<option value="0" <?php selected( 0, $val ) ?>><?php esc_html_e( 'Nowhere', 'thumbs-rating-system' ); ?></option>
			<option value="1" <?php selected( 1, $val ) ?>><?php esc_html_e( 'End of posts', 'thumbs-rating-system' ); ?></option>
		</select>
		<?php
	}

	/**
	 * Cleaning data.
	 *
	 * @since    1.0.0
	 * @param    array    $options    Plugin settings.
	 */
	function sanitize_callback( $options ) {

		foreach( $options as $name => & $val ){
			if( 'title_text' == $name  )
				$val = strip_tags( $val );

			if( 'enable_rich_snippets' == $name || 'show_rating' == $name )
				$val = intval( $val );
		}

		return $options;
	}

	/**
	 * Get field option.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @param    string    $option    Option name.
	 */
	private function get_field_option( $option ) {
		$value = get_option('thumbs_rating_system_options');

		if( isset( $value[ $option ] ) ) {
			return $value[ $option ];
		}

		return null;
	}

}
