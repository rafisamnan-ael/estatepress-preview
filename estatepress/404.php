<?php
/**
 * 404 template.
 *
 * @package EstatePress
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

get_header();
?>
<div class="ep-404">
	<div class="ep-container">
		<p class="ep-404-code">404</p>
		<h1 class="ep-404-title"><?php esc_html_e( 'Page Not Found', 'estatepress' ); ?></h1>
		<p class="ep-404-text"><?php esc_html_e( 'The page you are looking for may have been moved or no longer exists.', 'estatepress' ); ?></p>
		<div class="ep-404-actions">
			<a class="ep-btn ep-btn-primary" href="<?php echo esc_url( home_url( '/' ) ); ?>"><?php esc_html_e( 'Back to Home', 'estatepress' ); ?></a>
			<a class="ep-btn ep-btn-outline" href="<?php echo esc_url( get_post_type_archive_link( 'property' ) ); ?>"><?php esc_html_e( 'Browse Properties', 'estatepress' ); ?></a>
		</div>
		<div class="ep-404-search"><?php get_search_form(); ?></div>
	</div>
</div>
<?php
get_footer();
