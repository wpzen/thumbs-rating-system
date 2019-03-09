<?php

class Thumbs_Rating_System_Options {

	public static function get_option( $option ) {
		
		$value = get_option('thumbs_rating_system_options');

		if( isset( $value[ $option ] ) ) {
			return $value[ $option ];
		}

		return null;

	}

}