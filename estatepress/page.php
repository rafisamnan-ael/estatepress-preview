<?php
/**
 * Page template.
 *
 * @package EstatePress
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

get_header();
?>
<div class="ep-page-head">
	<div class="ep-container">
		<h1 class="ep-page-title"><?php the_title(); ?></h1>
		<?php echo estatepress_breadcrumbs(); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
	</div>
</div>

<div class="ep-container ep-layout">
	<main class="ep-main ep-page-main">
		<?php
		while ( have_posts() ) :
			the_post();
			?>
			<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
				<div class="ep-entry-content"><?php the_content(); ?></div>
				<?php
				wp_link_pages(
					array(
						'before' => '<div class="ep-page-links">',
						'after'  => '</div>',
					)
				);
				?>
			</article>
			<?php
			if ( comments_open() || get_comments_number() ) {
				comments_template();
			}
		endwhile;
		?>
	</main>
</div>

<?php
get_footer();
