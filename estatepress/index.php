<?php
/**
 * Main index template.
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
		<h1 class="ep-page-title">
			<?php
			if ( is_home() && ! is_front_page() ) {
				single_post_title();
			} else {
				esc_html_e( 'Latest News & Insights', 'estatepress' );
			}
			?>
		</h1>
	</div>
</div>

<div class="ep-container ep-layout<?php echo is_active_sidebar( 'sidebar-1' ) ? ' has-sidebar' : ''; ?>">
	<main class="ep-main">
		<?php if ( have_posts() ) : ?>
			<div class="ep-post-list">
				<?php
				while ( have_posts() ) :
					the_post();
					get_template_part( 'template-parts/content', get_post_type() );
				endwhile;
				?>
			</div>
			<?php estatepress_pagination(); ?>
		<?php else : ?>
			<div class="ep-empty-state">
				<h2><?php esc_html_e( 'Nothing found', 'estatepress' ); ?></h2>
				<p><?php esc_html_e( 'Try a different search.', 'estatepress' ); ?></p>
				<?php get_search_form(); ?>
			</div>
		<?php endif; ?>
	</main>
	<?php get_sidebar(); ?>
</div>

<?php
get_footer();
