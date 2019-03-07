<?php

/**
 * Provide a public-facing view for the plugin
 *
 * This file is used to markup the public-facing aspects of the plugin.
 *
 * @link       https://wpzen.ru
 * @since      1.0.0
 *
 * @package    Thumbs_Rating_System
 * @subpackage Thumbs_Rating_System/public/partials
 */
?>

<!-- This file should primarily consist of HTML with a little bit of PHP. -->

<div data-id="<?php echo self::get_post_id(); ?>" rel="v:rating" id="thumbs-rating-system">

	<div typeof="v:Rating">
		<div itemprop="aggregateRating" itemscope itemtype="http://schema.org/AggregateRating">
			<meta itemprop="bestRating" content="5">
			<meta property="v:rating" content="<?php echo self::get_average_rating(); ?>">
			<meta itemprop="ratingValue" content="<?php echo self::get_average_rating(); ?>">
			<meta itemprop="ratingCount" property="v:votes" content="<?php echo self::get_total_thumbs(); ?>">
		</div>
	</div>

	<div class="rating__question">
		<?php esc_html_e( 'Was this article helpful?', 'thumbs-up-down-rating' ); ?>	
	</div>

	<div id="rating__vote" class="rating__vote">
		
		<div class="rating__scale">
			
			<button class="rating__btn rating__btn_like" data-type="like">
				<svg viewBox="0 0 24 24" preserveAspectRatio="xMidYMid meet" focusable="false">
					<g>
						<path d="M1 21h4V9H1v12zm22-11c0-1.1-.9-2-2-2h-6.31l.95-4.57.03-.32c0-.41-.17-.79-.44-1.06L14.17 1 7.59 7.59C7.22 7.95 7 8.45 7 9v10c0 1.1.9 2 2 2h9c.83 0 1.54-.5 1.84-1.22l3.02-7.05c.09-.23.14-.47.14-.73v-1.91l-.01-.01L23 10z"></path>
					</g>
				</svg>
				<?php esc_html_e( 'Yes', 'thumbs-up-down-rating' ); ?>
			</button><!-- .rating__btn_like -->

			<button class="rating__btn rating__btn_unlike" data-type="unlike">
				<svg viewBox="0 0 24 24" preserveAspectRatio="xMidYMid meet" focusable="false">
					<g>
						<path d="M15 3H6c-.83 0-1.54.5-1.84 1.22l-3.02 7.05c-.09.23-.14.47-.14.73v1.91l.01.01L1 14c0 1.1.9 2 2 2h6.31l-.95 4.57-.03.32c0 .41.17.79.44 1.06L9.83 23l6.59-6.59c.36-.36.58-.86.58-1.41V5c0-1.1-.9-2-2-2zm4 0v12h4V3h-4z"></path>
					</g>
      			</svg>
      			<?php esc_html_e( 'No', 'thumbs-up-down-rating' ); ?>
			</button><!-- .rating__btn_unlike -->

		</div><!-- .rating__scale -->

	</div><!-- #rating__vote -->

	<div class="rating__stats">
		<span class="rating__count-positive"><?php echo self::get_thumbs_up(); ?></span>
		<div class="rating__line">
			<div class="rating__line-positive"></div>
			<div class="rating__line-negative" style="width:<?php echo self::get_negative_rating(); ?>%"></div>
		</div>
		<span class="rating__count-negative"><?php echo self::get_thumbs_down(); ?></span>
	</div>

</div><!-- #thumbs-rating-system -->