<?php
/**
 * Comments template.
 *
 * @package EstatePress
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( post_password_required() ) {
	return;
}
?>
<div id="comments" class="ep-comments">
	<?php if ( have_comments() ) : ?>
		<h2 class="ep-comments-title">
			<?php
			$count = get_comments_number();
			printf(
				/* translators: %s: comment count. */
				esc_html( _n( '%s Comment', '%s Comments', $count, 'estatepress' ) ),
				esc_html( number_format_i18n( $count ) )
			);
			?>
		</h2>
		<ol class="ep-comment-list">
			<?php
			wp_list_comments(
				array(
					'style'      => 'ol',
					'short_ping' => true,
				)
			);
			?>
		</ol>
		<?php the_comments_navigation(); ?>
	<?php endif; ?>

	<?php
	comment_form(
		array(
			'title_reply' => __( 'Leave a Comment', 'estatepress' ),
		)
	);
	?>
</div>
