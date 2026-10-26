<?php
/**
 * Single post template.
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
		<?php echo estatepress_breadcrumbs(); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
	</div>
</div>

<div class="ep-container ep-layout<?php echo is_active_sidebar( 'sidebar-1' ) ? ' has-sidebar' : ''; ?>">
	<main class="ep-main">
		<?php
		while ( have_posts() ) :
			the_post();
			get_template_part( 'template-parts/content', get_post_type() );

			if ( comments_open() || get_comments_number() ) {
				comments_template();
			}

			the_post_navigation(
				array(
					'prev_text' => '&larr; %title',
					'next_text' => '%title &rarr;',
				)
			);
		endwhile;
		?>
	</main>
	<?php get_sidebar(); ?>
</div>

<?php
get_footer();
