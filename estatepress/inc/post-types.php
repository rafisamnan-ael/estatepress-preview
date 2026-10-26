<?php
/**
 * Register the Property custom post type and its taxonomies.
 *
 * @package EstatePress
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Register the property post type.
 */
function estatepress_register_property() {
	$labels = array(
		'name'               => __( 'Properties', 'estatepress' ),
		'singular_name'      => __( 'Property', 'estatepress' ),
		'add_new'            => __( 'Add New', 'estatepress' ),
		'add_new_item'       => __( 'Add New Property', 'estatepress' ),
		'edit_item'          => __( 'Edit Property', 'estatepress' ),
		'new_item'           => __( 'New Property', 'estatepress' ),
		'view_item'          => __( 'View Property', 'estatepress' ),
		'search_items'       => __( 'Search Properties', 'estatepress' ),
		'not_found'          => __( 'No properties found', 'estatepress' ),
		'not_found_in_trash' => __( 'No properties found in Trash', 'estatepress' ),
		'menu_name'          => __( 'Properties', 'estatepress' ),
	);

	register_post_type(
		'property',
		array(
			'labels'       => $labels,
			'public'       => true,
			'has_archive'  => true,
			'menu_icon'    => 'dashicons-building',
			'menu_position'=> 5,
			'rewrite'      => array( 'slug' => 'properties' ),
			'supports'     => array( 'title', 'editor', 'thumbnail', 'excerpt', 'comments', 'revisions' ),
			'show_in_rest' => true,
		)
	);
}
add_action( 'init', 'estatepress_register_property' );

/**
 * Register property taxonomies.
 */
function estatepress_register_taxonomies() {
	register_taxonomy(
		'property_type',
		'property',
		array(
			'labels'            => array(
				'name'          => __( 'Property Types', 'estatepress' ),
				'singular_name' => __( 'Property Type', 'estatepress' ),
			),
			'hierarchical'      => true,
			'public'            => true,
			'show_admin_column' => true,
			'show_in_rest'      => true,
			'rewrite'           => array( 'slug' => 'property-type' ),
		)
	);

	register_taxonomy(
		'property_location',
		'property',
		array(
			'labels'            => array(
				'name'          => __( 'Locations', 'estatepress' ),
				'singular_name' => __( 'Location', 'estatepress' ),
			),
			'hierarchical'      => true,
			'public'            => true,
			'show_admin_column' => true,
			'show_in_rest'      => true,
			'rewrite'           => array( 'slug' => 'location' ),
		)
	);

	register_taxonomy(
		'property_status',
		'property',
		array(
			'labels'            => array(
				'name'          => __( 'Statuses', 'estatepress' ),
				'singular_name' => __( 'Status', 'estatepress' ),
			),
			'hierarchical'      => false,
			'public'            => true,
			'show_admin_column' => true,
			'show_in_rest'      => true,
			'rewrite'           => array( 'slug' => 'status' ),
		)
	);
}
add_action( 'init', 'estatepress_register_taxonomies' );

/**
 * Seed default terms on theme activation.
 */
function estatepress_activate() {
	estatepress_register_property();
	estatepress_register_taxonomies();

	$types = array( 'Apartment', 'House', 'Duplex', 'Office Space', 'Commercial', 'Land / Plot' );
	foreach ( $types as $type ) {
		if ( ! term_exists( $type, 'property_type' ) ) {
			wp_insert_term( $type, 'property_type' );
		}
	}

	$locations = array( 'Dhanmondi', 'Gulshan', 'Banani', 'Uttara', 'Mirpur', 'Bashundhara R/A', 'Chattogram', 'Sylhet' );
	foreach ( $locations as $location ) {
		if ( ! term_exists( $location, 'property_location' ) ) {
			wp_insert_term( $location, 'property_location' );
		}
	}

	$statuses = array( 'For Sale', 'For Rent', 'Sold', 'Rented' );
	foreach ( $statuses as $status ) {
		if ( ! term_exists( $status, 'property_status' ) ) {
			wp_insert_term( $status, 'property_status' );
		}
	}

	flush_rewrite_rules();
}
add_action( 'after_switch_theme', 'estatepress_activate' );

/**
 * Admin columns for properties.
 */
function estatepress_property_columns( $columns ) {
	$new = array();
	foreach ( $columns as $key => $value ) {
		$new[ $key ] = $value;
		if ( 'title' === $key ) {
			$new['price']    = __( 'Price', 'estatepress' );
			$new['beds']     = __( 'Beds', 'estatepress' );
			$new['baths']    = __( 'Baths', 'estatepress' );
			$new['area']     = __( 'Area', 'estatepress' );
		}
	}
	return $new;
}
add_filter( 'manage_property_posts_columns', 'estatepress_property_columns' );

/**
 * Render property admin column values.
 */
function estatepress_property_column_content( $column, $post_id ) {
	switch ( $column ) {
		case 'price':
			echo esc_html( estatepress_get_price( $post_id ) );
			break;
		case 'beds':
			echo esc_html( get_post_meta( $post_id, '_ep_beds', true ) );
			break;
		case 'baths':
			echo esc_html( get_post_meta( $post_id, '_ep_baths', true ) );
			break;
		case 'area':
			echo esc_html( get_post_meta( $post_id, '_ep_area', true ) );
			break;
	}
}
add_action( 'manage_property_posts_custom_column', 'estatepress_property_column_content', 10, 2 );
