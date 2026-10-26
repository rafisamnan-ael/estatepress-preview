<?php
/**
 * Template tags and helpers.
 *
 * @package EstatePress
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Get formatted price for a property.
 *
 * @param int $post_id Post ID.
 * @return string
 */
function estatepress_get_price( $post_id = 0 ) {
	$post_id = $post_id ? $post_id : get_the_ID();
	$price   = get_post_meta( $post_id, '_ep_price', true );
	if ( '' === $price ) {
		return '';
	}
	return '৳' . $price;
}

/**
 * Get the price suffix.
 *
 * @param int $post_id Post ID.
 * @return string
 */
function estatepress_get_price_unit( $post_id = 0 ) {
	$post_id = $post_id ? $post_id : get_the_ID();
	$unit    = get_post_meta( $post_id, '_ep_price_unit', true );
	return $unit ? $unit : 'BDT';
}

/**
 * Get a single meta value with a default.
 *
 * @param string $key     Meta key.
 * @param int    $post_id Post ID.
 * @param string $default Default value.
 * @return string
 */
function estatepress_meta( $key, $post_id = 0, $default = '' ) {
	$post_id = $post_id ? $post_id : get_the_ID();
	$value   = get_post_meta( $post_id, $key, true );
	return '' !== $value ? $value : $default;
}

/**
 * Get features as an array.
 *
 * @param int $post_id Post ID.
 * @return array
 */
function estatepress_get_features( $post_id = 0 ) {
	$post_id  = $post_id ? $post_id : get_the_ID();
	$features = get_post_meta( $post_id, '_ep_features', true );
	if ( ! $features ) {
		return array();
	}
	return array_filter( array_map( 'trim', explode( "\n", $features ) ) );
}

/**
 * Get gallery image IDs (including featured image).
 *
 * @param int $post_id Post ID.
 * @return array
 */
function estatepress_get_gallery_ids( $post_id = 0 ) {
	$post_id = $post_id ? $post_id : get_the_ID();
	$ids     = array();
	if ( has_post_thumbnail( $post_id ) ) {
		$ids[] = get_post_thumbnail_id( $post_id );
	}
	$gallery = get_post_meta( $post_id, '_ep_gallery', true );
	if ( $gallery ) {
		$ids = array_merge( $ids, array_filter( array_map( 'absint', explode( ',', $gallery ) ) ) );
	}
	return array_unique( $ids );
}

/**
 * Property thumbnail or placeholder.
 *
 * @param int    $post_id Post ID.
 * @param string $size    Image size.
 */
function estatepress_thumbnail( $post_id = 0, $size = 'estatepress-card' ) {
	$post_id = $post_id ? $post_id : get_the_ID();
	if ( has_post_thumbnail( $post_id ) ) {
		echo get_the_post_thumbnail( $post_id, $size, array( 'class' => 'ep-thumb-img', 'loading' => 'lazy' ) );
	} elseif ( 'property' === get_post_type( $post_id ) ) {
		$n   = ( absint( $post_id ) % 10 ) + 1;
		$img = ESTATEPRESS_URI . '/assets/images/demo/prop-' . $n . '.svg';
		echo '<img src="' . esc_url( $img ) . '" class="ep-thumb-img" alt="" loading="lazy" />';
	} else {
		echo '<span class="ep-thumb-placeholder" aria-hidden="true"><svg viewBox="0 0 24 24" width="48" height="48" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M3 10.5 12 3l9 7.5"/><path d="M5 9.5V21h14V9.5"/><path d="M9 21v-6h6v6"/></svg></span>';
	}
}

/**
 * WhatsApp link for a property.
 *
 * @param int $post_id Post ID.
 * @return string
 */
function estatepress_whatsapp_link( $post_id = 0 ) {
	$post_id  = $post_id ? $post_id : get_the_ID();
	$number   = estatepress_meta( '_ep_agent_whatsapp', $post_id );
	if ( ! $number ) {
		$number = get_theme_mod( 'estatepress_whatsapp', '' );
	}
	$number = preg_replace( '/[^0-9]/', '', $number );
	if ( ! $number ) {
		return '';
	}
	$message = sprintf(
		/* translators: %s: property title. */
		__( 'Hello, I am interested in this property: %s (%s)', 'estatepress' ),
		get_the_title( $post_id ),
		get_permalink( $post_id )
	);
	return 'https://wa.me/' . $number . '?text=' . rawurlencode( $message );
}

/**
 * Render the property card.
 *
 * @param int $post_id Post ID.
 */
function estatepress_property_card( $post_id = 0 ) {
	$post_id = $post_id ? $post_id : get_the_ID();
	$price   = estatepress_get_price( $post_id );
	$area    = estatepress_meta( '_ep_area', $post_id );
	$beds    = estatepress_meta( '_ep_beds', $post_id );
	$baths   = estatepress_meta( '_ep_baths', $post_id );
	$status  = get_the_terms( $post_id, 'property_status' );
	$city    = estatepress_meta( '_ep_city', $post_id );
	$address = estatepress_meta( '_ep_address', $post_id );
	?>
	<article class="ep-card">
		<a class="ep-card-media" href="<?php the_permalink( $post_id ); ?>">
			<?php estatepress_thumbnail( $post_id ); ?>
			<?php if ( $status && ! is_wp_error( $status ) ) : ?>
				<span class="ep-badge"><?php echo esc_html( $status[0]->name ); ?></span>
			<?php endif; ?>
			<?php if ( $price ) : ?>
				<span class="ep-card-price"><?php echo esc_html( $price ); ?><small><?php echo esc_html( estatepress_get_price_unit( $post_id ) ); ?></small></span>
			<?php endif; ?>
		</a>
		<div class="ep-card-body">
			<?php
			$types = get_the_terms( $post_id, 'property_type' );
			if ( $types && ! is_wp_error( $types ) ) :
				?>
				<span class="ep-card-type"><?php echo esc_html( $types[0]->name ); ?></span>
			<?php endif; ?>
			<h3 class="ep-card-title"><a href="<?php the_permalink( $post_id ); ?>"><?php echo esc_html( get_the_title( $post_id ) ); ?></a></h3>
			<p class="ep-card-location">
				<svg viewBox="0 0 24 24" width="14" height="14" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 21s-7-5.2-7-11a7 7 0 1 1 14 0c0 5.8-7 11-7 11z"/><circle cx="12" cy="10" r="2.5"/></svg>
				<?php echo esc_html( trim( $city . ( $address ? ', ' . $address : '' ), ', ' ) ); ?>
			</p>
			<ul class="ep-card-meta">
				<?php if ( $beds ) : ?>
					<li><?php esc_html_e( 'Beds', 'estatepress' ); ?> <strong><?php echo esc_html( $beds ); ?></strong></li>
				<?php endif; ?>
				<?php if ( $baths ) : ?>
					<li><?php esc_html_e( 'Baths', 'estatepress' ); ?> <strong><?php echo esc_html( $baths ); ?></strong></li>
				<?php endif; ?>
				<?php if ( $area ) : ?>
					<li><strong><?php echo esc_html( $area ); ?></strong> <?php esc_html_e( 'sqft', 'estatepress' ); ?></li>
				<?php endif; ?>
			</ul>
		</div>
	</article>
	<?php
}

/**
 * Pagination.
 */
function estatepress_pagination() {
	the_posts_pagination(
		array(
			'mid_size'  => 1,
			'prev_text' => '&larr;',
			'next_text' => '&rarr;',
			'class'     => 'ep-pagination',
		)
	);
}

/**
 * Posted on meta.
 */
function estatepress_posted_on() {
	printf(
		'<span class="posted-on">%s</span>',
		esc_html( get_the_date() )
	);
}

/**
 * Demo avatar fallback URL.
 *
 * @param int $index Avatar number (1-based).
 * @return string
 */
function estatepress_demo_avatar( $index ) {
	return ESTATEPRESS_URI . '/assets/images/demo/avatar-' . ( ( ( (int) $index ) - 1 ) % 4 + 1 ) . '.svg';
}

/**
 * Get the homepage stat counters from the Customizer.
 *
 * @return array
 */
function estatepress_get_stats() {
	$defaults = array(
		1 => array( '1200', __( 'Properties Sold', 'estatepress' ), __( 'Total successful sales to date', 'estatepress' ) ),
		2 => array( '500', __( 'Happy Clients', 'estatepress' ), __( 'Buyers, sellers and tenants served', 'estatepress' ) ),
		3 => array( '10', __( 'Years Experience', 'estatepress' ), __( 'Active in the Bangladesh market', 'estatepress' ) ),
		4 => array( '8', __( 'Locations', 'estatepress' ), __( 'Cities and neighbourhoods covered', 'estatepress' ) ),
	);
	$stats = array();
	foreach ( $defaults as $i => $d ) {
		$value = get_theme_mod( "estatepress_stat{$i}_value", $d[0] );
		$label = get_theme_mod( "estatepress_stat{$i}_label", $d[1] );
		$hint  = get_theme_mod( "estatepress_stat{$i}_hint", $d[2] );
		if ( '' === $value && '' === $label ) {
			continue;
		}
		$stats[] = array( 'value' => $value, 'label' => $label, 'hint' => $hint );
	}
	return $stats;
}

/**
 * Get testimonials from the Customizer.
 *
 * @return array
 */
function estatepress_get_testimonials() {
	$defaults = array(
		1 => array( __( 'They found us the perfect family apartment in Dhanmondi within two weeks. Completely transparent throughout.', 'estatepress' ), __( 'Rafi Samnan', 'estatepress' ), __( 'Home Buyer', 'estatepress' ) ),
		2 => array( __( 'Professional, responsive and honest. The paperwork support saved us a lot of time.', 'estatepress' ), __( 'Ayesha Haque', 'estatepress' ), __( 'Investor', 'estatepress' ) ),
		3 => array( __( 'Rented out my Gulshan office within days. Great reach and quality leads.', 'estatepress' ), __( 'Mahmud Karim', 'estatepress' ), __( 'Landlord', 'estatepress' ) ),
	);
	$items = array();
	foreach ( $defaults as $i => $d ) {
		$quote = get_theme_mod( "estatepress_testimonial{$i}_quote", $d[0] );
		$name  = get_theme_mod( "estatepress_testimonial{$i}_name", $d[1] );
		$role  = get_theme_mod( "estatepress_testimonial{$i}_role", $d[2] );
		$img   = get_theme_mod( "estatepress_testimonial{$i}_avatar", '' );
		if ( '' === $quote ) {
			continue;
		}
		$items[] = array(
			'quote'  => $quote,
			'name'   => $name,
			'role'   => $role,
			'avatar' => $img ? $img : estatepress_demo_avatar( $i ),
		);
	}
	return $items;
}

/**
 * Get agents from the Customizer.
 *
 * @return array
 */
function estatepress_get_agents() {
	$defaults = array(
		1 => array( __( 'Rafi Samnan', 'estatepress' ), __( 'Senior Consultant', 'estatepress' ) ),
		2 => array( __( 'Ayesha Haque', 'estatepress' ), __( 'Residential Expert', 'estatepress' ) ),
		3 => array( __( 'Mahmud Karim', 'estatepress' ), __( 'Commercial Advisor', 'estatepress' ) ),
		4 => array( __( 'Tanvir Nabil', 'estatepress' ), __( 'Client Relations', 'estatepress' ) ),
	);
	$items = array();
	foreach ( $defaults as $i => $d ) {
		$name = get_theme_mod( "estatepress_agent{$i}_name", $d[0] );
		$role = get_theme_mod( "estatepress_agent{$i}_role", $d[1] );
		$img  = get_theme_mod( "estatepress_agent{$i}_avatar", '' );
		if ( '' === $name ) {
			continue;
		}
		$items[] = array(
			'name'   => $name,
			'role'   => $role,
			'avatar' => $img ? $img : estatepress_demo_avatar( $i ),
		);
	}
	return $items;
}

/**
 * Fallback primary menu.
 */
function estatepress_default_menu() {
	$items = array(
		home_url( '/' )            => __( 'Home', 'estatepress' ),
		get_post_type_archive_link( 'property' ) => __( 'Properties', 'estatepress' ),
	);

	$listings = get_page_by_path( 'listings' );
	if ( $listings ) {
		$items[ get_permalink( $listings->ID ) ] = __( 'Listings', 'estatepress' );
	}
	$about = get_page_by_path( 'about' );
	if ( $about ) {
		$items[ get_permalink( $about->ID ) ] = __( 'About', 'estatepress' );
	}
	$contact = get_page_by_path( 'contact' );
	if ( $contact ) {
		$items[ get_permalink( $contact->ID ) ] = __( 'Contact', 'estatepress' );
	}

	echo '<ul class="ep-menu">';
	foreach ( $items as $url => $label ) {
		printf( '<li><a href="%s">%s</a></li>', esc_url( $url ), esc_html( $label ) );
	}
	echo '</ul>';
}

/**
 * Simple breadcrumbs.
 *
 * @return string
 */
function estatepress_breadcrumbs() {
	$out = '<nav class="ep-breadcrumbs"><a href="' . esc_url( home_url( '/' ) ) . '">' . esc_html__( 'Home', 'estatepress' ) . '</a>';

	if ( is_singular( 'property' ) ) {
		$out .= ' <span>/</span> <a href="' . esc_url( get_post_type_archive_link( 'property' ) ) . '">' . esc_html__( 'Properties', 'estatepress' ) . '</a>';
		$out .= ' <span>/</span> <span>' . esc_html( get_the_title() ) . '</span>';
	} elseif ( is_post_type_archive( 'property' ) ) {
		$out .= ' <span>/</span> <span>' . esc_html__( 'Properties', 'estatepress' ) . '</span>';
	} elseif ( is_tax() ) {
		$out .= ' <span>/</span> <a href="' . esc_url( get_post_type_archive_link( 'property' ) ) . '">' . esc_html__( 'Properties', 'estatepress' ) . '</a>';
		$out .= ' <span>/</span> <span>' . esc_html( single_term_title( '', false ) ) . '</span>';
	} elseif ( is_singular() ) {
		$out .= ' <span>/</span> <span>' . esc_html( get_the_title() ) . '</span>';
	} elseif ( is_search() ) {
		$out .= ' <span>/</span> <span>' . esc_html__( 'Search', 'estatepress' ) . '</span>';
	} elseif ( is_404() ) {
		$out .= ' <span>/</span> <span>' . esc_html__( 'Not Found', 'estatepress' ) . '</span>';
	}

	$out .= '</nav>';
	return $out;
}

/**
 * Build a taxonomy dropdown for search filters.
 *
 * @param string $taxonomy Taxonomy name.
 * @param string $label    Field label.
 * @param string $name     Field name.
 */
function estatepress_tax_dropdown( $taxonomy, $label, $name ) {
	$terms    = get_terms( array( 'taxonomy' => $taxonomy, 'hide_empty' => false ) );
	$selected = isset( $_GET[ $name ] ) ? sanitize_text_field( wp_unslash( $_GET[ $name ] ) ) : '';
	if ( is_wp_error( $terms ) || empty( $terms ) ) {
		return;
	}
	echo '<label class="ep-field">';
	echo '<span class="ep-field-label">' . esc_html( $label ) . '</span>';
	echo '<select name="' . esc_attr( $name ) . '">';
	echo '<option value="">' . esc_html__( 'Any', 'estatepress' ) . '</option>';
	foreach ( $terms as $term ) {
		printf(
			'<option value="%s" %s>%s</option>',
			esc_attr( $term->slug ),
			selected( $selected, $term->slug, false ),
			esc_html( $term->name )
		);
	}
	echo '</select>';
	echo '</label>';
}
