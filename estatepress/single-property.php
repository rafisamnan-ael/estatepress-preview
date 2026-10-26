<?php
/**
 * Single property template.
 *
 * @package EstatePress
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

get_header();

while ( have_posts() ) :
	the_post();

	$gallery = estatepress_get_gallery_ids();
	$price   = estatepress_get_price();
	$area    = estatepress_meta( '_ep_area' );
	$beds    = estatepress_meta( '_ep_beds' );
	$baths   = estatepress_meta( '_ep_baths' );
	$parking = estatepress_meta( '_ep_parking' );
	$year    = estatepress_meta( '_ep_year' );
	$address = estatepress_meta( '_ep_address' );
	$city    = estatepress_meta( '_ep_city' );
	$ref     = estatepress_meta( '_ep_reference' );
	$map     = estatepress_meta( '_ep_map' );
	$features= estatepress_get_features();

	$status  = get_the_terms( get_the_ID(), 'property_status' );
	$types   = get_the_terms( get_the_ID(), 'property_type' );
	$agent   = estatepress_meta( '_ep_agent_name', 0, get_theme_mod( 'estatepress_company', get_bloginfo( 'name' ) ) );
	$phone   = estatepress_meta( '_ep_agent_phone', 0, get_theme_mod( 'estatepress_phone', '' ) );
	$email   = estatepress_meta( '_ep_agent_email', 0, get_theme_mod( 'estatepress_email', '' ) );
	$wa      = estatepress_whatsapp_link();
	?>
	<div class="ep-page-head ep-page-head-dark">
		<div class="ep-container">
			<?php echo estatepress_breadcrumbs(); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
		</div>
	</div>

	<article class="ep-single-property">
		<div class="ep-container">

			<header class="ep-property-head">
				<div class="ep-property-head-main">
					<?php if ( $status && ! is_wp_error( $status ) ) : ?>
						<span class="ep-badge ep-badge-lg"><?php echo esc_html( $status[0]->name ); ?></span>
					<?php endif; ?>
					<h1 class="ep-property-title"><?php the_title(); ?></h1>
					<p class="ep-property-location">
						<svg viewBox="0 0 24 24" width="16" height="16" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 21s-7-5.2-7-11a7 7 0 1 1 14 0c0 5.8-7 11-7 11z"/><circle cx="12" cy="10" r="2.5"/></svg>
						<?php echo esc_html( trim( $address . ( $city ? ', ' . $city : '' ), ', ' ) ); ?>
					</p>
				</div>
				<div class="ep-property-head-price">
					<span class="ep-price"><?php echo esc_html( $price ); ?></span>
					<span class="ep-price-unit"><?php echo esc_html( estatepress_get_price_unit() ); ?></span>
					<?php if ( $ref ) : ?>
						<span class="ep-ref"><?php esc_html_e( 'Ref:', 'estatepress' ); ?> <?php echo esc_html( $ref ); ?></span>
					<?php endif; ?>
				</div>
			</header>

			<?php if ( ! empty( $gallery ) ) : ?>
				<div class="ep-gallery" data-gallery>
					<div class="ep-gallery-main">
						<?php
						$first = array_shift( $gallery );
						echo wp_get_attachment_image( $first, 'estatepress-hero', false, array( 'class' => 'ep-gallery-hero', 'data-ep-gallery-main' => '1' ) );
						?>
					</div>
					<?php if ( ! empty( $gallery ) ) : ?>
						<div class="ep-gallery-thumbs">
							<?php foreach ( $gallery as $id ) : ?>
								<button type="button" class="ep-gallery-thumb" data-ep-gallery-thumb="<?php echo esc_attr( wp_get_attachment_image_url( $id, 'estatepress-hero' ) ); ?>">
									<?php echo wp_get_attachment_image( $id, 'estatepress-thumb' ); ?>
								</button>
							<?php endforeach; ?>
						</div>
					<?php endif; ?>
				</div>
			<?php endif; ?>

			<div class="ep-property-layout">
				<div class="ep-property-content">
					<ul class="ep-key-facts">
						<?php if ( $beds ) : ?>
							<li><span class="ep-fact-value"><?php echo esc_html( $beds ); ?></span><span class="ep-fact-label"><?php esc_html_e( 'Bedrooms', 'estatepress' ); ?></span></li>
						<?php endif; ?>
						<?php if ( $baths ) : ?>
							<li><span class="ep-fact-value"><?php echo esc_html( $baths ); ?></span><span class="ep-fact-label"><?php esc_html_e( 'Bathrooms', 'estatepress' ); ?></span></li>
						<?php endif; ?>
						<?php if ( $area ) : ?>
							<li><span class="ep-fact-value"><?php echo esc_html( $area ); ?></span><span class="ep-fact-label"><?php esc_html_e( 'Sqft', 'estatepress' ); ?></span></li>
						<?php endif; ?>
						<?php if ( $parking ) : ?>
							<li><span class="ep-fact-value"><?php echo esc_html( $parking ); ?></span><span class="ep-fact-label"><?php esc_html_e( 'Parking', 'estatepress' ); ?></span></li>
						<?php endif; ?>
						<?php if ( $year ) : ?>
							<li><span class="ep-fact-value"><?php echo esc_html( $year ); ?></span><span class="ep-fact-label"><?php esc_html_e( 'Year Built', 'estatepress' ); ?></span></li>
						<?php endif; ?>
					</ul>

					<section class="ep-block">
						<h2><?php esc_html_e( 'Description', 'estatepress' ); ?></h2>
						<div class="ep-entry-content"><?php the_content(); ?></div>
					</section>

					<?php if ( ! empty( $features ) ) : ?>
						<section class="ep-block">
							<h2><?php esc_html_e( 'Features & Amenities', 'estatepress' ); ?></h2>
							<ul class="ep-feature-list">
								<?php foreach ( $features as $feature ) : ?>
									<li><?php echo esc_html( $feature ); ?></li>
								<?php endforeach; ?>
							</ul>
						</section>
					<?php endif; ?>

					<?php if ( $map ) : ?>
						<section class="ep-block">
							<h2><?php esc_html_e( 'Location', 'estatepress' ); ?></h2>
							<div class="ep-map">
								<iframe src="<?php echo esc_url( $map ); ?>" width="100%" height="380" style="border:0;" loading="lazy" referrerpolicy="no-referrer-when-downgrade" title="<?php esc_attr_e( 'Property location', 'estatepress' ); ?>"></iframe>
							</div>
						</section>
					<?php endif; ?>
				</div>

				<aside class="ep-property-sidebar">
					<div class="ep-agent-card">
						<h3 class="ep-agent-name"><?php echo esc_html( $agent ); ?></h3>
						<p class="ep-agent-role"><?php esc_html_e( 'Property Consultant', 'estatepress' ); ?></p>

						<?php if ( $phone ) : ?>
							<a class="ep-contact-line" href="tel:<?php echo esc_attr( preg_replace( '/[^0-9+]/', '', $phone ) ); ?>">
								<svg viewBox="0 0 24 24" width="16" height="16" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 16.9v3a2 2 0 0 1-2.2 2 19.8 19.8 0 0 1-8.6-3.1 19.5 19.5 0 0 1-6-6A19.8 19.8 0 0 1 2 4.2 2 2 0 0 1 4 2h3a2 2 0 0 1 2 1.7c.1 1 .4 1.9.7 2.8a2 2 0 0 1-.5 2.1L8.1 9.9a16 16 0 0 0 6 6l1.3-1.1a2 2 0 0 1 2.1-.5c.9.3 1.8.6 2.8.7a2 2 0 0 1 1.7 2z"/></svg>
								<?php echo esc_html( $phone ); ?>
							</a>
						<?php endif; ?>

						<?php if ( $email ) : ?>
							<a class="ep-contact-line" href="mailto:<?php echo esc_attr( $email ); ?>">
								<svg viewBox="0 0 24 24" width="16" height="16" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="5" width="18" height="14" rx="2"/><path d="m3 7 9 6 9-6"/></svg>
								<?php echo esc_html( $email ); ?>
							</a>
						<?php endif; ?>

						<?php if ( $wa ) : ?>
							<a class="ep-btn ep-btn-whatsapp ep-btn-block" href="<?php echo esc_url( $wa ); ?>" target="_blank" rel="noopener">
								<svg viewBox="0 0 24 24" width="18" height="18" fill="currentColor"><path d="M12 2a10 10 0 0 0-8.6 15l-1.3 4.8 4.9-1.3A10 10 0 1 0 12 2zm0 18a8 8 0 0 1-4.1-1.1l-.3-.2-2.9.8.8-2.8-.2-.3A8 8 0 1 1 12 20zm4.4-6c-.2-.1-1.4-.7-1.6-.8-.2-.1-.4-.1-.5.1l-.7.9c-.1.2-.3.2-.5.1a6.5 6.5 0 0 1-3.2-2.8c-.1-.2 0-.4.1-.5l.5-.6c.1-.2.1-.3 0-.5l-.8-1.9c-.1-.3-.3-.3-.5-.3h-.5c-.2 0-.5.1-.7.4-.2.3-.8 1-.8 2.3s.9 2.6 1 2.8c.1.2 1.8 2.9 4.4 4 .6.3 1.1.4 1.5.5.6.1 1.2.1 1.6 0 .5-.1 1.4-.6 1.6-1.1.2-.5.2-1 .1-1.1-.1-.1-.2-.2-.4-.3z"/></svg>
								<?php esc_html_e( 'WhatsApp', 'estatepress' ); ?>
							</a>
						<?php endif; ?>
					</div>

					<div class="ep-inquiry-card">
						<h3><?php esc_html_e( 'Request Information', 'estatepress' ); ?></h3>
						<?php estatepress_inquiry_form(); ?>
					</div>
				</aside>
			</div>

			<?php
			$related = new WP_Query(
				array(
					'post_type'      => 'property',
					'posts_per_page' => 3,
					'post__not_in'   => array( get_the_ID() ),
					'post_status'    => 'publish',
					'orderby'        => 'rand',
				)
			);
			if ( $related->have_posts() ) :
				?>
				<section class="ep-section ep-related">
					<h2><?php esc_html_e( 'Similar Properties', 'estatepress' ); ?></h2>
					<div class="ep-grid ep-grid-3">
						<?php
						while ( $related->have_posts() ) :
							$related->the_post();
							estatepress_property_card();
						endwhile;
						wp_reset_postdata();
						?>
					</div>
				</section>
			<?php endif; ?>

		</div>
	</article>
	<?php
endwhile;

get_footer();
