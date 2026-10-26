<?php
/**
 * Post content part.
 *
 * @package EstatePress
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$is_singular = is_singular();
?>
<article id="post-<?php the_ID(); ?>" <?php post_class( $is_singular ? 'ep-post-single' : 'ep-post-card' ); ?>>
	<?php if ( has_post_thumbnail() && ! $is_singular ) : ?>
		<a class="ep-post-thumb" href="<?php the_permalink(); ?>">
			<?php the_post_thumbnail( 'estatepress-card' ); ?>
		</a>
	<?php endif; ?>
	<div class="ep-post-body">
		<?php if ( $is_singular ) : ?>
			<h1 class="ep-post-title"><?php the_title(); ?></h1>
		<?php else : ?>
			<h2 class="ep-post-title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
		<?php endif; ?>

		<div class="ep-post-meta">
			<?php estatepress_posted_on(); ?>
			<span class="byline"><?php the_author(); ?></span>
		</div>

		<?php if ( $is_singular ) : ?>
			<div class="ep-entry-content"><?php the_content(); ?></div>
			<?php
			wp_link_pages(
				array(
					'before' => '<div class="ep-page-links">',
					'after'  => '</div>',
				)
			);
			?>
		<?php else : ?>
			<div class="ep-post-excerpt"><?php the_excerpt(); ?></div>
			<a class="ep-read-more" href="<?php the_permalink(); ?>"><?php esc_html_e( 'Read more', 'estatepress' ); ?> &rarr;</a>
		<?php endif; ?>
	</div>
</article>
