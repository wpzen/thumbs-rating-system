<?php

/**
 * Shortcode creation class.
 *
 * @link       https://wpzen.ru
 * @since      1.0.0
 *
 * @package    Thumbs_Rating_System
 * @subpackage Thumbs_Rating_System/public
 */

/**
 * Shortcode creation class.
 *
 * @package    Thumbs_Rating_System
 * @subpackage Thumbs_Rating_System/public
 * @author     Pleshakov Valery <pleshakov.valery@gmail.com>
 */
class Thumbs_Rating_System_Shortcode {

	/**
	 * The unique ID for the current post.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      integer    $post_id    Current post id.
	 */
	protected $post_id;

	/**
	 * Number of likes.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      integer    $thumbs_up    Total likes.
	 */
	protected $thumbs_up;

	/**
	 * Number of dislikes.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      integer    $thumbs_down    Total dislikes.
	 */
	protected $thumbs_down;

	/**
	 * The number of voters.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      integer    $total_thumbs    Total votes.
	 */
	protected $total_thumbs;

	/**
	 * Average rating.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      integer    $average_rating    Average rating.
	 */
	protected $average_rating;

	/**
	 * The percentage of negative votes to positive.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      integer    $post_id    Percentage of negative votes.
	 */
	protected $negative_rating;

	/**
	 * Get the number of likes and update variable value.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function update_thumbs_up() {

		$this->thumbs_up = (int) get_post_meta( $this->post_id, 'thumbs_rating_likes', true );

	}

	/**
	 * Get the number of dislikes and update variable value.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function update_thumbs_down() {

		$this->thumbs_down = (int) get_post_meta( $this->post_id, 'thumbs_rating_dislikes', true );

	}

	/**
	 * Get the number of votes and update variable value.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function update_total_thumbs() {

		$this->total_thumbs = $this->thumbs_up + $this->thumbs_down;

	}

	/**
	 * Get average rating and update variable value.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function update_average_rating() {

		$average_rating = 0;

		if( 0 !== $this->total_thumbs ) {
			$average_rating = round((((($this->thumbs_up *100)/$this->total_thumbs)*5)/100),2);
		}

		$this->average_rating = $average_rating;

	}

	/**
	 * Get the percentage of negative votes and update variable value.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function update_negative_rating() {

		$negative_rating = 0;

		if( 0 !== $this->total_thumbs ) {	
			$negative_rating = round((($this->thumbs_down*100)/$this->total_thumbs),2);
		}

		$this->negative_rating = $negative_rating;

	}

	/**
	 * Get current post id.
	 *
	 * @since    1.0.0
	 * @return   integer    Current post id.
	 */
	public function get_post_id() {
		return $this->post_id;
	}

	/**
	 * Get number of likes.
	 *
	 * @since    1.0.0
	 * @return   integer    Number of likes.
	 */
	public function get_thumbs_up() {
		return $this->thumbs_up;
	}

	/**
	 * Get number of dislikes.
	 *
	 * @since    1.0.0
	 * @return   integer    Number of dislikes.
	 */
	public function get_thumbs_down() {
		return $this->thumbs_down;
	}

	/**
	 * Get total votes.
	 *
	 * @since    1.0.0
	 * @return   integer    Total votes.
	 */
	public function get_total_thumbs() {
		return $this->total_thumbs;
	}

	/**
	 * Get average rating.
	 *
	 * @since    1.0.0
	 * @return   integer    Average rating.
	 */
	public function get_average_rating() {
		return $this->average_rating;
	}

	/**
	 * Get percentage of negative votes.
	 *
	 * @since    1.0.0
	 * @return   integer    Percentage of negative votes.
	 */
	public function get_negative_rating() {
		return $this->negative_rating;
	}

	/**
	 * Output html rating code.
	 *
	 * @since    1.0.0
	 * @return   string   Html rating code.
	 */
	public function render( $post_id ) {

		$this->post_id = $post_id;

		$this->update_thumbs_up();
		$this->update_thumbs_down();
		$this->update_total_thumbs();
		$this->update_average_rating();
		$this->update_negative_rating();

		$template_path = plugin_dir_path( dirname( __FILE__ ) ) . 'partials/thumbs-rating-system-public-display.php';

		ob_start();

		require( $template_path );

		return ob_get_clean(); 

	}

}