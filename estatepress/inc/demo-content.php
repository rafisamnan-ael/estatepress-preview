<?php
/**
 * One-time demo content importer.
 *
 * @package EstatePress
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Find a post by its exact title.
 *
 * @param string $title     Post title.
 * @param string $post_type Post type.
 * @return WP_Post|null
 */
function estatepress_get_post_by_title( $title, $post_type = 'post' ) {
	$query = new WP_Query(
		array(
			'post_type'              => $post_type,
			'title'                  => $title,
			'posts_per_page'         => 1,
			'post_status'            => 'any',
			'no_found_rows'          => true,
			'update_post_term_cache' => false,
			'update_post_meta_cache' => false,
		)
	);
	return $query->have_posts() ? $query->posts[0] : null;
}

/**
 * Import demo pages and sample properties on theme activation.
 */
function estatepress_import_demo() {
	if ( get_option( 'estatepress_demo_imported' ) ) {
		return;
	}

	$pages = array(
		'home'    => array(
			'title'    => __( 'Home', 'estatepress' ),
			'template' => '',
			'content'  => '',
		),
		'about'   => array(
			'title'    => __( 'About Us', 'estatepress' ),
			'template' => 'page-templates/template-about.php',
			'content'  => '',
		),
		'contact' => array(
			'title'    => __( 'Contact', 'estatepress' ),
			'template' => 'page-templates/template-contact.php',
			'content'  => '',
		),
		'listings'=> array(
			'title'    => __( 'Properties', 'estatepress' ),
			'template' => 'page-templates/template-listings.php',
			'content'  => '',
		),
	);

	$page_ids = array();
	foreach ( $pages as $slug => $data ) {
		$existing = get_page_by_path( $slug );
		if ( $existing ) {
			$page_ids[ $slug ] = $existing->ID;
			continue;
		}
		$page_id = wp_insert_post(
			array(
				'post_title'   => $data['title'],
				'post_name'    => $slug,
				'post_status'  => 'publish',
				'post_type'    => 'page',
				'post_content' => $data['content'],
			)
		);
		if ( $page_id && ! is_wp_error( $page_id ) ) {
			$page_ids[ $slug ] = $page_id;
			if ( $data['template'] ) {
				update_post_meta( $page_id, '_wp_page_template', $data['template'] );
			}
		}
	}

	if ( isset( $page_ids['home'] ) ) {
		update_option( 'show_on_front', 'page' );
		update_option( 'page_on_front', $page_ids['home'] );
	}

	$properties = array(
		array(
			'title'   => 'Luxury 4 Bedroom Apartment in Gulshan 2',
			'type'    => 'Apartment',
			'location'=> 'Gulshan',
			'status'  => 'For Sale',
			'meta'    => array(
				'_ep_price'   => '42,000,000',
				'_ep_area'    => '3200',
				'_ep_beds'    => '4',
				'_ep_baths'   => '4',
				'_ep_parking' => '2',
				'_ep_year'    => '2019',
				'_ep_address' => 'Road 113, Gulshan 2',
				'_ep_city'    => 'Dhaka',
				'_ep_features'=> "Lake view\nServant quarter\nCentral AC\nLift & generator\n24/7 security\nGym & rooftop",
			),
			'content' => 'A beautifully finished four-bedroom apartment in the heart of Gulshan 2, offering lake views, generous living spaces and premium fittings throughout. Close to embassies, restaurants and international schools.',
		),
		array(
			'title'   => 'Modern 3 Bedroom Apartment in Dhanmondi 27',
			'type'    => 'Apartment',
			'location'=> 'Dhanmondi',
			'status'  => 'For Sale',
			'meta'    => array(
				'_ep_price'   => '18,500,000',
				'_ep_area'    => '1650',
				'_ep_beds'    => '3',
				'_ep_baths'   => '3',
				'_ep_parking' => '1',
				'_ep_year'    => '2021',
				'_ep_address' => 'Road 27, Dhanmondi',
				'_ep_city'    => 'Dhaka',
				'_ep_features'=> "Lift\nGenerator backup\nCommunity hall\nCCTV security\nSouth-facing balcony",
			),
			'content' => 'A bright, well-planned three-bedroom apartment in Dhanmondi 27 with a south-facing balcony and excellent connectivity to schools, hospitals and shopping.',
		),
		array(
			'title'   => 'Elegant 5 Bedroom Duplex in Banani',
			'type'    => 'Duplex',
			'location'=> 'Banani',
			'status'  => 'For Sale',
			'meta'    => array(
				'_ep_price'   => '75,000,000',
				'_ep_area'    => '5400',
				'_ep_beds'    => '5',
				'_ep_baths'   => '6',
				'_ep_parking' => '3',
				'_ep_year'    => '2017',
				'_ep_address' => 'Road 11, Banani',
				'_ep_city'    => 'Dhaka',
				'_ep_features'=> "Private garden\nRoof terrace\nHome office\nDriver's room\nTwo car garage\nSmart home wiring",
			),
			'content' => 'A rare standalone duplex in Banani with a private garden, roof terrace and generous family living. Ideal for a growing family or a diplomatic residence.',
		),
		array(
			'title'   => 'Spacious 3 Bedroom Flat for Rent in Uttara Sector 7',
			'type'    => 'Apartment',
			'location'=> 'Uttara',
			'status'  => 'For Rent',
			'meta'    => array(
				'_ep_price'   => '45,000',
				'_ep_price_unit' => '/month',
				'_ep_area'    => '1500',
				'_ep_beds'    => '3',
				'_ep_baths'   => '3',
				'_ep_parking' => '1',
				'_ep_year'    => '2020',
				'_ep_address' => 'Sector 7, Uttara',
				'_ep_city'    => 'Dhaka',
				'_ep_features'=> "Semi-furnished\nLift\nGenerator\nNear metro station\nBalcony",
			),
			'content' => 'A semi-furnished three-bedroom flat in Uttara Sector 7, minutes from the metro station, with lift and generator backup. Available immediately.',
		),
		array(
			'title'   => 'Affordable 2 Bedroom Apartment in Mirpur 10',
			'type'    => 'Apartment',
			'location'=> 'Mirpur',
			'status'  => 'For Rent',
			'meta'    => array(
				'_ep_price'   => '22,000',
				'_ep_price_unit' => '/month',
				'_ep_area'    => '950',
				'_ep_beds'    => '2',
				'_ep_baths'   => '2',
				'_ep_parking' => '0',
				'_ep_year'    => '2015',
				'_ep_address' => 'Block C, Mirpur 10',
				'_ep_city'    => 'Dhaka',
				'_ep_features'=> "Family building\nWater supply\nNear market\nBalcony",
			),
			'content' => 'A budget-friendly two-bedroom apartment in Mirpur 10, well connected to the city and walking distance from markets and transport.',
		),
		array(
			'title'   => 'Premium Office Space in Bashundhara R/A',
			'type'    => 'Office Space',
			'location'=> 'Bashundhara R/A',
			'status'  => 'For Rent',
			'meta'    => array(
				'_ep_price'   => '180,000',
				'_ep_price_unit' => '/month',
				'_ep_area'    => '2800',
				'_ep_beds'    => '',
				'_ep_baths'   => '2',
				'_ep_parking' => '4',
				'_ep_year'    => '2022',
				'_ep_address' => 'Block J, Bashundhara R/A',
				'_ep_city'    => 'Dhaka',
				'_ep_features'=> "Open plan\nMeeting rooms\nCentral AC\nLift\nGenerous parking\nFibre internet ready",
			),
			'content' => 'A premium open-plan office floor in Bashundhara R/A suited to corporate tenants, with meeting rooms, central air conditioning and ample parking.',
		),
		array(
			'title'   => 'Commercial Plot in Purbachal New Town',
			'type'    => 'Land / Plot',
			'location'=> 'Bashundhara R/A',
			'status'  => 'For Sale',
			'meta'    => array(
				'_ep_price'   => '30,000,000',
				'_ep_area'    => '7200',
				'_ep_beds'    => '',
				'_ep_baths'   => '',
				'_ep_parking' => '',
				'_ep_year'    => '',
				'_ep_address' => 'Sector 4, Purbachal',
				'_ep_city'    => 'Dhaka',
				'_ep_features'=> "Corner plot\nWide road frontage\nReady for development\nUtilities available",
			),
			'content' => 'A prime corner commercial plot in Purbachal New Town with wide road frontage, ideal for a mixed-use or commercial development.',
		),
		array(
			'title'   => 'Sea-View 3 Bedroom Apartment in Chattogram',
			'type'    => 'Apartment',
			'location'=> 'Chattogram',
			'status'  => 'For Sale',
			'meta'    => array(
				'_ep_price'   => '15,800,000',
				'_ep_area'    => '1750',
				'_ep_beds'    => '3',
				'_ep_baths'   => '3',
				'_ep_parking' => '1',
				'_ep_year'    => '2020',
				'_ep_address' => 'Patenga, Chattogram',
				'_ep_city'    => 'Chattogram',
				'_ep_features'=> "Sea view\nLift\nGenerator\nCommunity gym\nBalcony",
			),
			'content' => 'A bright three-bedroom apartment near Patenga with partial sea views, modern finishes and full building amenities in one of Chattogram\'s fastest-growing areas.',
		),
		array(
			'title'   => 'Furnished 4 Bedroom House in Sylhet City',
			'type'    => 'House',
			'location'=> 'Sylhet',
			'status'  => 'For Sale',
			'meta'    => array(
				'_ep_price'   => '26,500,000',
				'_ep_area'    => '3600',
				'_ep_beds'    => '4',
				'_ep_baths'   => '4',
				'_ep_parking' => '2',
				'_ep_year'    => '2018',
				'_ep_address' => 'Uposhohor, Sylhet',
				'_ep_city'    => 'Sylhet',
				'_ep_features'=> "Fully furnished\nLandscaped garden\nGenerator\nServant quarter\nTwo car parking",
			),
			'content' => 'A fully furnished four-bedroom family house in Uposhohor, Sylhet, with a landscaped garden and complete backup facilities.',
		),
		array(
			'title'   => 'Serviced 1 Bedroom Apartment in Banani (Short Stay)',
			'type'    => 'Apartment',
			'location'=> 'Banani',
			'status'  => 'For Rent',
			'meta'    => array(
				'_ep_price'   => '75,000',
				'_ep_price_unit' => '/month',
				'_ep_area'    => '780',
				'_ep_beds'    => '1',
				'_ep_baths'   => '1',
				'_ep_parking' => '1',
				'_ep_year'    => '2021',
				'_ep_address' => 'Road 7, Banani',
				'_ep_city'    => 'Dhaka',
				'_ep_features'=> "Fully serviced\nHousekeeping\nUtilities included\nGym access\nConcierge",
			),
			'content' => 'A serviced one-bedroom apartment in Banani ideal for short stays and business travellers, with housekeeping and utilities included.',
		),
	);

	foreach ( $properties as $property ) {
		$existing = estatepress_get_post_by_title( $property['title'], 'property' );
		if ( $existing ) {
			continue;
		}
		$post_id = wp_insert_post(
			array(
				'post_type'    => 'property',
				'post_status'  => 'publish',
				'post_title'   => $property['title'],
				'post_content' => $property['content'],
				'post_excerpt' => wp_trim_words( $property['content'], 20 ),
			)
		);
		if ( ! $post_id || is_wp_error( $post_id ) ) {
			continue;
		}
		foreach ( $property['meta'] as $key => $value ) {
			update_post_meta( $post_id, $key, $value );
		}
		update_post_meta( $post_id, '_ep_reference', 'EP-' . ( 1000 + $post_id ) );

		wp_set_object_terms( $post_id, $property['type'], 'property_type' );
		wp_set_object_terms( $post_id, $property['location'], 'property_location' );
		wp_set_object_terms( $post_id, $property['status'], 'property_status' );
	}

	update_option( 'estatepress_demo_imported', 1 );
	flush_rewrite_rules();
}
add_action( 'after_switch_theme', 'estatepress_import_demo', 20 );

/**
 * Admin notice offering manual demo import.
 */
function estatepress_demo_notice() {
	if ( get_option( 'estatepress_demo_imported' ) || ! current_user_can( 'manage_options' ) ) {
		return;
	}
	if ( isset( $_GET['ep_import_demo'] ) && check_admin_referer( 'ep_import_demo' ) ) {
		estatepress_import_demo();
		echo '<div class="notice notice-success is-dismissible"><p>' . esc_html__( 'Demo content imported.', 'estatepress' ) . '</p></div>';
		return;
	}
	$url = wp_nonce_url( add_query_arg( 'ep_import_demo', 1 ), 'ep_import_demo' );
	echo '<div class="notice notice-info"><p>' . esc_html__( 'EstatePress: import sample properties and pages?', 'estatepress' ) . ' <a class="button button-primary" href="' . esc_url( $url ) . '">' . esc_html__( 'Import Demo Content', 'estatepress' ) . '</a></p></div>';
}
add_action( 'admin_notices', 'estatepress_demo_notice' );
