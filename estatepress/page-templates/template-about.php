<?php
/**
 * Template Name: About
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

<div class="ep-container ep-about">
	<?php
	while ( have_posts() ) :
		the_post();
		if ( get_the_content() ) :
			?>
			<div class="ep-entry-content ep-about-intro"><?php the_content(); ?></div>
		<?php endif; ?>
	<?php endwhile; ?>

	<section class="ep-section ep-why">
		<header class="ep-section-head">
			<h2><?php esc_html_e( 'Why Choose Us', 'estatepress' ); ?></h2>
			<p><?php esc_html_e( 'Trusted guidance from search to handover.', 'estatepress' ); ?></p>
		</header>
		<div class="ep-grid ep-grid-3">
			<div class="ep-why-card">
				<span class="ep-why-icon">✓</span>
				<h3><?php esc_html_e( 'Verified Listings', 'estatepress' ); ?></h3>
				<p><?php esc_html_e( 'Every property is checked for accurate details, legal documents and fair pricing.', 'estatepress' ); ?></p>
			</div>
			<div class="ep-why-card">
				<span class="ep-why-icon">৳</span>
				<h3><?php esc_html_e( 'Transparent Pricing', 'estatepress' ); ?></h3>
				<p><?php esc_html_e( 'Clear prices with no hidden charges, plus honest market advice.', 'estatepress' ); ?></p>
			</div>
			<div class="ep-why-card">
				<span class="ep-why-icon">☎</span>
				<h3><?php esc_html_e( 'Dedicated Support', 'estatepress' ); ?></h3>
				<p><?php esc_html_e( 'Talk to a real agent by phone, email or WhatsApp whenever you need.', 'estatepress' ); ?></p>
			</div>
		</div>
	</section>

	<section class="ep-section ep-about-stats">
		<div class="ep-grid ep-grid-4">
			<div class="ep-stat"><strong>10+</strong><span><?php esc_html_e( 'Years Experience', 'estatepress' ); ?></span></div>
			<div class="ep-stat"><strong>500+</strong><span><?php esc_html_e( 'Happy Clients', 'estatepress' ); ?></span></div>
			<div class="ep-stat"><strong>1200+</strong><span><?php esc_html_e( 'Properties Sold', 'estatepress' ); ?></span></div>
			<div class="ep-stat"><strong>8</strong><span><?php esc_html_e( 'Locations', 'estatepress' ); ?></span></div>
		</div>
	</section>

	<section class="ep-cta">
		<div class="ep-cta-spotlight" aria-hidden="true"></div>
		<div class="ep-container ep-cta-inner">
			<div>
				<h2><?php esc_html_e( 'Ready to find your next property?', 'estatepress' ); ?></h2>
				<p><?php esc_html_e( 'Browse our latest listings or talk to our team.', 'estatepress' ); ?></p>
			</div>
			<a class="ep-btn ep-btn-light ep-btn-lg" href="<?php echo esc_url( get_post_type_archive_link( 'property' ) ); ?>" data-magnetic><?php esc_html_e( 'Browse Properties', 'estatepress' ); ?></a>
		</div>
	</section>
</div>

<?php
get_footer();
