<?php
/**
 * Front page template.
 *
 * @package EstatePress
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

get_header();

$hero_title    = get_theme_mod( 'estatepress_hero_title', __( 'Find Your Dream Property in Bangladesh', 'estatepress' ) );
$hero_subtitle = get_theme_mod( 'estatepress_hero_subtitle', __( 'Apartments, houses, offices and land across Dhaka, Chattogram and Sylhet.', 'estatepress' ) );
$hero_image    = get_theme_mod( 'estatepress_hero_image', ESTATEPRESS_URI . '/assets/images/demo/hero.svg' );
$archive       = get_post_type_archive_link( 'property' );

$hero_pins = array(
	array( 'slug' => 'gulshan', 'label' => __( 'Gulshan', 'estatepress' ), 'price' => '৳42M', 'top' => '30%', 'left' => '70%' ),
	array( 'slug' => 'banani', 'label' => __( 'Banani', 'estatepress' ), 'price' => '৳75M', 'top' => '47%', 'left' => '80%' ),
	array( 'slug' => 'uttara', 'label' => __( 'Uttara', 'estatepress' ), 'price' => '৳45K/mo', 'top' => '24%', 'left' => '52%' ),
	array( 'slug' => 'dhanmondi', 'label' => __( 'Dhanmondi', 'estatepress' ), 'price' => '৳18.5M', 'top' => '62%', 'left' => '66%' ),
	array( 'slug' => 'chattogram', 'label' => __( 'Chattogram', 'estatepress' ), 'price' => '৳15.8M', 'top' => '74%', 'left' => '82%' ),
);
?>

<section id="hero" class="ep-hero" data-nav="<?php esc_attr_e( 'Home', 'estatepress' ); ?>">
	<div class="ep-hero-media"<?php echo $hero_image ? ' style="background-image:url(' . esc_url( $hero_image ) . ');"' : ''; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>></div>
	<div class="ep-hero-overlay"></div>
	<div class="ep-hero-spotlight" aria-hidden="true"></div>
	<div class="ep-hero-pins" aria-hidden="false">
		<?php
		foreach ( $hero_pins as $pin ) :
			$term = get_term_by( 'slug', $pin['slug'], 'property_location' );
			$href = ( $term && ! is_wp_error( $term ) ) ? get_term_link( $term ) : $archive;
			?>
			<a class="ep-pin" href="<?php echo esc_url( $href ); ?>" style="top:<?php echo esc_attr( $pin['top'] ); ?>;left:<?php echo esc_attr( $pin['left'] ); ?>">
				<?php echo esc_html( $pin['label'] ); ?> <small><?php echo esc_html( $pin['price'] ); ?></small>
			</a>
		<?php endforeach; ?>
	</div>
	<div class="ep-container ep-hero-inner">
		<span class="ep-eyebrow" style="color:#f2b705"><?php esc_html_e( 'Trusted since 2014', 'estatepress' ); ?></span>
		<h1 class="ep-hero-title" data-reveal><?php echo esc_html( $hero_title ); ?></h1>
		<p class="ep-hero-subtitle" data-reveal data-delay="1"><?php echo esc_html( $hero_subtitle ); ?></p>

		<form class="ep-search" method="get" action="<?php echo esc_url( $archive ); ?>" data-reveal data-delay="2">
			<div class="ep-search-row">
				<label class="ep-field ep-field-grow">
					<span class="ep-field-label"><?php esc_html_e( 'Keyword', 'estatepress' ); ?></span>
					<input type="text" name="s" placeholder="<?php esc_attr_e( 'Search by title or area…', 'estatepress' ); ?>" value="<?php echo esc_attr( get_search_query() ); ?>" />
				</label>
				<input type="hidden" name="post_type" value="property" />
				<?php
				estatepress_tax_dropdown( 'property_type', __( 'Property Type', 'estatepress' ), 'property_type' );
				estatepress_tax_dropdown( 'property_location', __( 'Location', 'estatepress' ), 'property_location' );
				estatepress_tax_dropdown( 'property_status', __( 'Status', 'estatepress' ), 'property_status' );
				?>
				<button type="submit" class="ep-btn ep-btn-primary ep-search-btn" data-magnetic>
					<svg viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2"><circle cx="11" cy="11" r="7"/><path d="m20 20-3.5-3.5"/></svg>
					<?php esc_html_e( 'Search', 'estatepress' ); ?>
				</button>
			</div>
		</form>

		<ul class="ep-hero-stats" data-reveal data-delay="3">
			<?php $count = wp_count_posts( 'property' ); ?>
			<li><strong><?php echo esc_html( isset( $count->publish ) ? $count->publish : 0 ); ?>+</strong> <?php esc_html_e( 'Listings', 'estatepress' ); ?></li>
			<li><strong>8</strong> <?php esc_html_e( 'Prime Locations', 'estatepress' ); ?></li>
			<li><strong>24/7</strong> <?php esc_html_e( 'Support', 'estatepress' ); ?></li>
		</ul>
	</div>
</section>

<div class="ep-trustbar">
	<div class="ep-container ep-trustbar-inner">
		<span><?php esc_html_e( 'Verified Listings', 'estatepress' ); ?></span>
		<span><?php esc_html_e( 'Legal Support', 'estatepress' ); ?></span>
		<span><?php esc_html_e( 'Trusted Agents', 'estatepress' ); ?></span>
		<span><?php esc_html_e( 'Fair Pricing', 'estatepress' ); ?></span>
		<span><?php esc_html_e( 'Nationwide', 'estatepress' ); ?></span>
	</div>
</div>

<?php
$types = get_terms( array( 'taxonomy' => 'property_type', 'hide_empty' => false ) );
if ( ! is_wp_error( $types ) && ! empty( $types ) ) :
	?>
	<section id="types" class="ep-section ep-types" data-nav="<?php esc_attr_e( 'Types', 'estatepress' ); ?>">
		<div class="ep-container">
			<header class="ep-section-head" data-reveal>
				<span class="ep-eyebrow"><?php esc_html_e( 'Categories', 'estatepress' ); ?></span>
				<h2><?php esc_html_e( 'Browse by Property Type', 'estatepress' ); ?></h2>
				<p><?php esc_html_e( 'Whatever you are looking for, start here.', 'estatepress' ); ?></p>
			</header>
			<div class="ep-type-grid">
				<?php foreach ( $types as $type ) : ?>
					<a class="ep-type-card" href="<?php echo esc_url( get_term_link( $type ) ); ?>" data-reveal>
						<span class="ep-type-name"><?php echo esc_html( $type->name ); ?></span>
						<span class="ep-type-count"><?php echo esc_html( $type->count ); ?> <?php esc_html_e( 'listings', 'estatepress' ); ?></span>
					</a>
				<?php endforeach; ?>
			</div>
		</div>
	</section>
<?php endif; ?>

<section id="featured" class="ep-section ep-featured" data-nav="<?php esc_attr_e( 'Featured', 'estatepress' ); ?>">
	<div class="ep-container">
		<header class="ep-section-head ep-section-head-row" data-reveal>
			<div>
				<span class="ep-eyebrow"><?php esc_html_e( 'Handpicked', 'estatepress' ); ?></span>
				<h2><?php esc_html_e( 'Featured Properties', 'estatepress' ); ?></h2>
				<p><?php esc_html_e( 'Hand-picked homes and spaces from our latest listings.', 'estatepress' ); ?></p>
			</div>
			<a class="ep-btn ep-btn-outline" href="<?php echo esc_url( $archive ); ?>"><?php esc_html_e( 'View All', 'estatepress' ); ?></a>
		</header>

		<div class="ep-carousel-wrap" data-carousel role="region" aria-label="<?php esc_attr_e( 'Featured properties', 'estatepress' ); ?>">
			<div class="ep-carousel-nav">
				<button type="button" class="ep-carousel-btn" data-carousel-prev aria-label="<?php esc_attr_e( 'Previous properties', 'estatepress' ); ?>">&larr;</button>
				<button type="button" class="ep-carousel-btn" data-carousel-next aria-label="<?php esc_attr_e( 'Next properties', 'estatepress' ); ?>">&rarr;</button>
			</div>
			<div class="ep-carousel" tabindex="0">
				<?php
				$featured = new WP_Query( array( 'post_type' => 'property', 'posts_per_page' => 8, 'post_status' => 'publish' ) );
				if ( $featured->have_posts() ) :
					while ( $featured->have_posts() ) :
						$featured->the_post();
						estatepress_property_card();
					endwhile;
					wp_reset_postdata();
				else :
					?>
					<p class="ep-empty"><?php esc_html_e( 'No properties yet. Add your first property from the WordPress dashboard.', 'estatepress' ); ?></p>
				<?php endif; ?>
			</div>
		</div>
	</div>
</section>

<?php $stats = estatepress_get_stats(); ?>
<?php if ( ! empty( $stats ) ) : ?>
<section id="stats" class="ep-stats-band" data-nav="<?php esc_attr_e( 'Numbers', 'estatepress' ); ?>">
	<div class="ep-container">
		<div class="ep-countup-grid">
			<?php
			foreach ( $stats as $stat ) :
				$numeric = preg_replace( '/[^0-9.]/', '', $stat['value'] );
				$animate = ( '' !== $numeric && is_numeric( $numeric ) );
				?>
				<div class="ep-countup">
					<strong <?php echo $animate ? 'data-count="' . esc_attr( $numeric ) . '"' : ''; ?>><?php echo esc_html( $animate ? $numeric : $stat['value'] ); ?></strong>
					<span><?php echo esc_html( $stat['label'] ); ?></span>
					<?php if ( ! empty( $stat['hint'] ) ) : ?>
						<span class="ep-countup-hint"><?php echo esc_html( $stat['hint'] ); ?></span>
					<?php endif; ?>
				</div>
			<?php endforeach; ?>
		</div>
		<?php if ( get_theme_mod( 'estatepress_stats_demo', true ) ) : ?>
			<p class="ep-stats-note"><?php echo esc_html( get_theme_mod( 'estatepress_stats_note', __( 'Sample data — replace with your real numbers.', 'estatepress' ) ) ); ?></p>
		<?php endif; ?>
	</div>
</section>
<?php endif; ?>

<?php
$locations = get_terms( array( 'taxonomy' => 'property_location', 'hide_empty' => false ) );
if ( ! is_wp_error( $locations ) && ! empty( $locations ) ) :
	?>
	<section id="locations" class="ep-section ep-bg-alt" data-nav="<?php esc_attr_e( 'Locations', 'estatepress' ); ?>">
		<div class="ep-container">
			<header class="ep-section-head" data-reveal>
				<span class="ep-eyebrow"><?php esc_html_e( 'Where we operate', 'estatepress' ); ?></span>
				<h2><?php esc_html_e( 'Popular Locations', 'estatepress' ); ?></h2>
			</header>
			<div class="ep-locations">
				<?php
				$i = 0;
				foreach ( $locations as $location ) :
					$i++;
					$img = ESTATEPRESS_URI . '/assets/images/demo/prop-' . ( ( $i % 8 ) + 1 ) . '.svg';
					?>
					<a class="ep-location" href="<?php echo esc_url( get_term_link( $location ) ); ?>" data-reveal>
						<img src="<?php echo esc_url( $img ); ?>" alt="<?php echo esc_attr( $location->name ); ?>" width="600" height="450" loading="lazy" decoding="async" />
						<span class="ep-location-name"><?php echo esc_html( $location->name ); ?></span>
						<span class="ep-location-meta">
							<?php echo esc_html( $location->count ); ?> <?php esc_html_e( 'listings', 'estatepress' ); ?>
							<em><?php esc_html_e( 'Explore', 'estatepress' ); ?> &rarr;</em>
						</span>
					</a>
				<?php endforeach; ?>
			</div>
		</div>
	</section>
<?php endif; ?>

<section id="why" class="ep-section ep-why" data-nav="<?php esc_attr_e( 'Why Us', 'estatepress' ); ?>">
	<div class="ep-container">
		<header class="ep-section-head" data-reveal>
			<span class="ep-eyebrow"><?php esc_html_e( 'Why us', 'estatepress' ); ?></span>
			<h2><?php esc_html_e( 'Why Choose Us', 'estatepress' ); ?></h2>
			<p><?php esc_html_e( 'Trusted guidance from search to handover.', 'estatepress' ); ?></p>
		</header>
		<div class="ep-grid ep-grid-3">
			<div class="ep-why-card" data-reveal><span class="ep-why-icon">✓</span><h3><?php esc_html_e( 'Verified Listings', 'estatepress' ); ?></h3><p><?php esc_html_e( 'Every property is checked for accurate details, legal documents and fair pricing.', 'estatepress' ); ?></p></div>
			<div class="ep-why-card" data-reveal data-delay="1"><span class="ep-why-icon">৳</span><h3><?php esc_html_e( 'Transparent Pricing', 'estatepress' ); ?></h3><p><?php esc_html_e( 'Clear prices with no hidden charges, plus honest market advice.', 'estatepress' ); ?></p></div>
			<div class="ep-why-card" data-reveal data-delay="2"><span class="ep-why-icon">☎</span><h3><?php esc_html_e( 'Dedicated Support', 'estatepress' ); ?></h3><p><?php esc_html_e( 'Talk to a real agent by phone, email or WhatsApp whenever you need.', 'estatepress' ); ?></p></div>
		</div>
	</div>
</section>

<?php $testimonials = estatepress_get_testimonials(); ?>
<?php if ( ! empty( $testimonials ) ) : ?>
<section id="testimonials" class="ep-section ep-testimonials" data-nav="<?php esc_attr_e( 'Reviews', 'estatepress' ); ?>">
	<div class="ep-container">
		<header class="ep-section-head" data-reveal>
			<span class="ep-eyebrow"><?php esc_html_e( 'Testimonials', 'estatepress' ); ?></span>
			<h2><?php esc_html_e( 'What Our Clients Say', 'estatepress' ); ?></h2>
		</header>

		<div class="ep-slider" data-slider data-autoplay="7000" aria-roledescription="carousel" aria-label="<?php esc_attr_e( 'Client testimonials', 'estatepress' ); ?>">
			<div class="ep-slider-viewport">
				<div class="ep-slider-track" data-slider-track>
					<?php foreach ( $testimonials as $index => $t ) : ?>
						<blockquote class="ep-testimonial ep-slide" data-reveal data-delay="<?php echo esc_attr( $index % 3 ); ?>" role="group" aria-roledescription="slide" aria-label="<?php echo esc_attr( sprintf( __( 'Testimonial %d of %d', 'estatepress' ), $index + 1, count( $testimonials ) ) ); ?>">
							<div class="ep-stars" aria-hidden="true">★★★★★</div>
							<p><?php echo esc_html( $t['quote'] ); ?></p>
							<div class="ep-testimonial-author">
								<img src="<?php echo esc_url( $t['avatar'] ); ?>" alt="" width="48" height="48" loading="lazy" decoding="async" />
								<div><strong><?php echo esc_html( $t['name'] ); ?></strong><span><?php echo esc_html( $t['role'] ); ?></span></div>
							</div>
						</blockquote>
					<?php endforeach; ?>
				</div>
			</div>
			<div class="ep-slider-controls">
				<button type="button" class="ep-slider-btn" data-slider-prev aria-label="<?php esc_attr_e( 'Previous testimonial', 'estatepress' ); ?>">&larr;</button>
				<div class="ep-slider-dots" data-slider-dots role="tablist" aria-label="<?php esc_attr_e( 'Choose testimonial', 'estatepress' ); ?>"></div>
				<button type="button" class="ep-slider-btn" data-slider-next aria-label="<?php esc_attr_e( 'Next testimonial', 'estatepress' ); ?>">&rarr;</button>
			</div>
		</div>
	</div>
</section>
<?php endif; ?>

<?php $agents = estatepress_get_agents(); ?>
<?php if ( ! empty( $agents ) ) : ?>
<section id="agents" class="ep-section" data-nav="<?php esc_attr_e( 'Agents', 'estatepress' ); ?>">
	<div class="ep-container">
		<header class="ep-section-head" data-reveal>
			<span class="ep-eyebrow"><?php esc_html_e( 'Our team', 'estatepress' ); ?></span>
			<h2><?php esc_html_e( 'Meet Our Agents', 'estatepress' ); ?></h2>
		</header>
		<div class="ep-agent-grid">
			<?php foreach ( $agents as $index => $agent ) : ?>
				<div class="ep-agent" data-reveal data-delay="<?php echo esc_attr( $index % 4 ); ?>">
					<img src="<?php echo esc_url( $agent['avatar'] ); ?>" alt="<?php echo esc_attr( $agent['name'] ); ?>" width="96" height="96" loading="lazy" decoding="async" />
					<h3><?php echo esc_html( $agent['name'] ); ?></h3>
					<p><?php echo esc_html( $agent['role'] ); ?></p>
					<?php $contact_page = get_page_by_path( 'contact' ); ?>
					<a class="ep-agent-contact" href="<?php echo esc_url( $contact_page ? get_permalink( $contact_page->ID ) : home_url( '/' ) ); ?>"><?php esc_html_e( 'Get in touch', 'estatepress' ); ?> &rarr;</a>
				</div>
			<?php endforeach; ?>
		</div>
	</div>
</section>
<?php endif; ?>

<section id="contact-cta" class="ep-cta" data-nav="<?php esc_attr_e( 'Contact', 'estatepress' ); ?>">
	<div class="ep-cta-spotlight" aria-hidden="true"></div>
	<div class="ep-container ep-cta-inner">
		<div>
			<h2><?php esc_html_e( 'Looking to sell or rent your property?', 'estatepress' ); ?></h2>
			<p><?php esc_html_e( 'List with us and reach thousands of serious buyers and tenants.', 'estatepress' ); ?></p>
		</div>
		<?php $contact = get_page_by_path( 'contact' ); ?>
		<a class="ep-btn ep-btn-light ep-btn-lg" href="<?php echo esc_url( $contact ? get_permalink( $contact->ID ) : home_url( '/' ) ); ?>" data-magnetic><?php esc_html_e( 'Get in Touch', 'estatepress' ); ?></a>
	</div>
</section>

<?php
get_footer();
