<?php

/**
 * Provide a admin area view for the plugin
 *
 * This file is used to markup the admin-facing aspects of the plugin.
 *
 * @link       https://wpzen.ru
 * @since      1.0.0
 *
 * @package    Thumbs_Rating_System
 * @subpackage Thumbs_Rating_System/admin/partials
 */
?>

<!-- This file should primarily consist of HTML with a little bit of PHP. -->

<table class="form-table">
	<tbody>
		<tr>
			<th scope="row">
				<label for="thumbs_rating_likes">
					<?php esc_html_e( 'The number of likes', 'thumbs_rating_system' ); ?>	
				</label>
			</th>
			<td>
				<input type="number" id="thumbs_rating_likes" name="thumbs_rating_likes" value="<?php echo $thumbs_likes; ?>">
			</td>
		</tr>
		<tr>
			<th scope="row">
				<label for="thumbs_rating_dislikes">
					<?php esc_html_e( 'The number of dislikes', 'thumbs_rating_system' ); ?>		
				</label>
			</th>
			<td>
				<input type="number" id="thumbs_rating_dislikes" name="thumbs_rating_dislikes" value="<?php echo $thumbs_dislikes; ?>">
			</td>
		</tr>
	</tbody>
</table>