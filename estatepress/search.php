<?php
/**
 * Search results template.
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
			printf(
				/* translators: %s: search query. */
				esc_html__( 'Search results for: %s', 'estatepress' ),
				'<span>' . esc_html( get_search_query() ) . '</span>'
			);
			?>
		</h1>
	</div>
</div>

<div class="ep-container ep-layout<?php echo is_active_sidebar( 'sidebar-1' ) ? ' has-sidebar' : ''; ?>">
	<main class="ep-main">
		<?php if ( have_posts() ) : ?>
			<div class="ep-grid ep-grid-3">
				<?php
				while ( have_posts() ) :
					the_post();
					if ( 'property' === get_post_type() ) {
						estatepress_property_card();
					} else {
						get_template_part( 'template-parts/content', get_post_type() );
					}
				endwhile;
				?>
			</div>
			<?php estatepress_pagination(); ?>
		<?php else : ?>
			<div class="ep-empty-state">
				<h2><?php esc_html_e( 'No results found', 'estatepress' ); ?></h2>
				<p><?php esc_html_e( 'Try another keyword.', 'estatepress' ); ?></p>
				<?php get_search_form(); ?>
			</div>
		<?php endif; ?>
	</main>
	<?php get_sidebar(); ?>
</div>

<?php
get_footer();
