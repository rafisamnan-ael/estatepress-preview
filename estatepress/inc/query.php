<?php
/**
 * Custom queries and property filtering.
 *
 * @package EstatePress
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Filter property archives and searches by price and bedrooms.
 *
 * @param WP_Query $query Query instance.
 */
function estatepress_pre_get_posts( $query ) {
	if ( is_admin() || ! $query->is_main_query() ) {
		return;
	}

	$is_property_query = $query->is_post_type_archive( 'property' ) || $query->is_tax( array( 'property_type', 'property_location', 'property_status' ) );

	if ( ! $is_property_query ) {
		return;
	}

	$meta_query = array();

	$min = isset( $_GET['ep_min_price'] ) ? (int) preg_replace( '/[^0-9]/', '', wp_unslash( $_GET['ep_min_price'] ) ) : 0;
	$max = isset( $_GET['ep_max_price'] ) ? (int) preg_replace( '/[^0-9]/', '', wp_unslash( $_GET['ep_max_price'] ) ) : 0;

	if ( $min ) {
		$meta_query[] = array(
			'key'     => '_ep_price',
			'value'   => $min,
			'type'    => 'NUMERIC',
			'compare' => '>=',
		);
	}
	if ( $max ) {
		$meta_query[] = array(
			'key'     => '_ep_price',
			'value'   => $max,
			'type'    => 'NUMERIC',
			'compare' => '<=',
		);
	}

	$beds = isset( $_GET['ep_beds'] ) ? absint( $_GET['ep_beds'] ) : 0;
	if ( $beds ) {
		$meta_query[] = array(
			'key'     => '_ep_beds',
			'value'   => $beds,
			'type'    => 'NUMERIC',
			'compare' => '>=',
		);
	}

	if ( $meta_query ) {
		$query->set( 'meta_query', $meta_query );
	}

	$query->set( 'posts_per_page', 9 );
}
add_action( 'pre_get_posts', 'estatepress_pre_get_posts' );
