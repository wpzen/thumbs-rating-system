<?php

class Thumbs_Rating_System_Widget_Top_Posts extends WP_Widget {

	/**
	 * Sets up a new Top Posts widget instance.
	 */
	public function __construct() {
		$widget_ops = array(
			'classname' => 'widget_top_entries',
			'description' => esc_html__( 'Your site&#8217;s most top Posts.', 'thumbs-rating-system' ),
			'customize_selective_refresh' => true,
		);
		parent::__construct( 'top-posts', esc_html__( 'Top Posts', 'thumbs-rating-system' ), $widget_ops );
		$this->alt_option_name = 'widget_top_entries';
	}

	/**
	 * Outputs the content for the current Top Posts widget instance.
	 *
	 * @param array $args     Display arguments including 'before_title', 'after_title',
	 *                        'before_widget', and 'after_widget'.
	 * @param array $instance Settings for the current Top Posts widget instance.
	 */
	public function widget( $args, $instance ) {
		if ( ! isset( $args['widget_id'] ) ) {
			$args['widget_id'] = $this->id;
		}

		$title = ( ! empty( $instance['title'] ) ) ? $instance['title'] : esc_html__( 'Top Posts', 'thumbs-up-down-rating' );

		/** This filter is documented in wp-includes/widgets/class-wp-widget-pages.php */
		$title = apply_filters( 'widget_title', $title, $instance, $this->id_base );

		$number = ( ! empty( $instance['number'] ) ) ? absint( $instance['number'] ) : 5;
		if ( ! $number ) {
			$number = 5;
		}
		$show_date = isset( $instance['show_date'] ) ? $instance['show_date'] : false;

		$post_type = isset( $instance['post_type'] ) ? $instance['post_type'] : 'post';

		/**
		 * Filters the arguments for the Top Posts widget.
		 */
		$r = new WP_Query( array(
			'post_type'				=> $post_type,
			'posts_per_page'		=> $number,
			'post_status'			=> 'publish',
			'order'					=> 'DESC',
			'orderby'				=> 'meta_value_num',
			'meta_key'				=> 'thumbs_rating_likes',
			'pagination'			=> false,
			'cache_results'			=> true,
			'ignore_sticky_posts'	=> true
		) );

		if ( ! $r->have_posts() ) {
			return;
		}
		?>
		<?php echo $args['before_widget']; ?>
		<?php
		if ( $title ) {
			echo $args['before_title'] . $title . $args['after_title'];
		}
		?>
		<ul>
			<?php foreach ( $r->posts as $top_post ) : ?>
				<?php
				$post_title = get_the_title( $top_post->ID );
				$title      = ( ! empty( $post_title ) ) ? $post_title : esc_html__( '(no title)', 'thumbs-up-down-rating' );
				?>
				<li>
					<a href="<?php the_permalink( $top_post->ID ); ?>"><?php echo $title ; ?></a>
					<?php if ( $show_date ) : ?>
						<span class="post-date"><?php echo get_the_date( '', $top_post->ID ); ?></span>
					<?php endif; ?>
				</li>
			<?php endforeach; ?>
		</ul>
		<?php
		echo $args['after_widget'];
	}

	/**
	 * Handles updating the settings for the current Top Posts widget instance.
	 *
	 * @param array $new_instance New settings for this instance as input by the user via
	 *                            WP_Widget::form().
	 * @param array $old_instance Old settings for this instance.
	 * @return array Updated settings to save.
	 */
	public function update( $new_instance, $old_instance ) {
		$instance = $old_instance;
		$instance['title'] = sanitize_text_field( $new_instance['title'] );
		$instance['number'] = (int) $new_instance['number'];
		$instance['show_date'] = isset( $new_instance['show_date'] ) ? (bool) $new_instance['show_date'] : false;
		$instance['post_type'] = $new_instance['post_type'];
		return $instance;
	}

	/**
	 * Outputs the settings form for the Top Posts widget.
	 *
	 * @param array $instance Current settings.
	 */
	public function form( $instance ) {
		$title     = isset( $instance['title'] ) ? esc_attr( $instance['title'] ) : '';
		$number    = isset( $instance['number'] ) ? absint( $instance['number'] ) : 5;
		$show_date = isset( $instance['show_date'] ) ? (bool) $instance['show_date'] : false;
		$post_type = isset( $instance['post_type'] ) ? $instance['post_type'] : 'post';
		$post_types = get_post_types( array(
			'public' => true
		), 'objects' );
?>
		<p><label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Title:' ); ?></label>
		<input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo $title; ?>" /></p>

		<p>
			<label for="<?php echo $this->get_field_id( 'post_type' ); ?>"><?php _e( 'Post type:', 'thumbs-rating-system' ); ?></label>
			<select class="tiny-text" id="<?php echo $this->get_field_id( 'post_type' ); ?>" name="<?php echo $this->get_field_name( 'post_type' ); ?>">
				<?php foreach( $post_types as $type ) : ?>
					<option value="<?php echo $type->name; ?>"<?php selected( $type->name, $post_type ); ?>><?php echo $type->label; ?></option>
				<?php endforeach; ?>
			</select>
		</p>

		<p><label for="<?php echo $this->get_field_id( 'number' ); ?>"><?php _e( 'Number of posts to show:' ); ?></label>
		<input class="tiny-text" id="<?php echo $this->get_field_id( 'number' ); ?>" name="<?php echo $this->get_field_name( 'number' ); ?>" type="number" step="1" min="1" value="<?php echo $number; ?>" size="3" /></p>

		<p><input class="checkbox" type="checkbox"<?php checked( $show_date ); ?> id="<?php echo $this->get_field_id( 'show_date' ); ?>" name="<?php echo $this->get_field_name( 'show_date' ); ?>" />
		<label for="<?php echo $this->get_field_id( 'show_date' ); ?>"><?php _e( 'Display post date?' ); ?></label></p>
<?php
	}
}
