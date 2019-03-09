<?php

/**
 * Fired during plugin activation
 *
 * @link       https://wpzen.ru
 * @since      1.0.0
 *
 * @package    Thumbs_Rating_System
 * @subpackage Thumbs_Rating_System/includes
 */

/**
 * Fired during plugin activation.
 *
 * This class defines all code necessary to run during the plugin's activation.
 *
 * @since      1.0.0
 * @package    Thumbs_Rating_System
 * @subpackage Thumbs_Rating_System/includes
 * @author     Pleshakov Valery <pleshakov.valery@gmail.com>
 */
class Thumbs_Rating_System_Activator {

	/**
	 * Default settings
	 *
	 * @since    1.0.0
	 */
	public static function activate() {

		$default_options = array(
			'title_text'			=> esc_html__( 'Was this article helpful?', 'thumbs-up-down-rating' ),
			'enable_rich_snippets'	=> 1,
			'show_rating'			=> 0
		);

		add_option( 'thumbs_rating_system_options', $default_options );

	}

}
