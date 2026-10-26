<?php
/**
 * Customizer options.
 *
 * @package EstatePress
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Register customizer settings.
 *
 * @param WP_Customize_Manager $wp_customize Customizer instance.
 */
function estatepress_customize_register( $wp_customize ) {
	$wp_customize->add_panel(
		'estatepress_options',
		array(
			'title'    => __( 'EstatePress Options', 'estatepress' ),
			'priority' => 30,
		)
	);

	$wp_customize->add_section(
		'estatepress_contact',
		array(
			'title' => __( 'Contact Details', 'estatepress' ),
			'panel' => 'estatepress_options',
		)
	);

	$fields = array(
		'estatepress_phone'    => __( 'Phone', 'estatepress' ),
		'estatepress_whatsapp' => __( 'WhatsApp Number (with country code)', 'estatepress' ),
		'estatepress_email'    => __( 'Email', 'estatepress' ),
		'estatepress_address'  => __( 'Office Address', 'estatepress' ),
	);
	foreach ( $fields as $key => $label ) {
		$wp_customize->add_setting( $key, array( 'default' => '', 'sanitize_callback' => 'sanitize_text_field' ) );
		$wp_customize->add_control( $key, array( 'label' => $label, 'section' => 'estatepress_contact', 'type' => 'text' ) );
	}

	$wp_customize->add_section(
		'estatepress_hero',
		array(
			'title' => __( 'Homepage Hero', 'estatepress' ),
			'panel' => 'estatepress_options',
		)
	);

	$wp_customize->add_setting( 'estatepress_hero_title', array( 'default' => __( 'Find Your Dream Property in Bangladesh', 'estatepress' ), 'sanitize_callback' => 'sanitize_text_field' ) );
	$wp_customize->add_control( 'estatepress_hero_title', array( 'label' => __( 'Hero Title', 'estatepress' ), 'section' => 'estatepress_hero', 'type' => 'text' ) );

	$wp_customize->add_setting( 'estatepress_hero_subtitle', array( 'default' => __( 'Apartments, houses, offices and land across Dhaka, Chattogram and Sylhet.', 'estatepress' ), 'sanitize_callback' => 'sanitize_text_field' ) );
	$wp_customize->add_control( 'estatepress_hero_subtitle', array( 'label' => __( 'Hero Subtitle', 'estatepress' ), 'section' => 'estatepress_hero', 'type' => 'textarea' ) );

	$wp_customize->add_setting( 'estatepress_hero_image', array( 'default' => '', 'sanitize_callback' => 'esc_url_raw' ) );
	$wp_customize->add_control(
		new WP_Customize_Image_Control(
			$wp_customize,
			'estatepress_hero_image',
			array( 'label' => __( 'Hero Background Image', 'estatepress' ), 'section' => 'estatepress_hero' )
		)
	);

	$wp_customize->add_section(
		'estatepress_social',
		array(
			'title' => __( 'Social Links', 'estatepress' ),
			'panel' => 'estatepress_options',
		)
	);
	foreach ( array( 'facebook', 'instagram', 'linkedin', 'youtube' ) as $network ) {
		$key = 'estatepress_' . $network;
		$wp_customize->add_setting( $key, array( 'default' => '', 'sanitize_callback' => 'esc_url_raw' ) );
		$wp_customize->add_control( $key, array( 'label' => ucfirst( $network ), 'section' => 'estatepress_social', 'type' => 'url' ) );
	}

	/* Stats band ---------------------------------------------------------- */
	$wp_customize->add_section(
		'estatepress_stats',
		array(
			'title'       => __( 'Stats Counters', 'estatepress' ),
			'panel'       => 'estatepress_options',
			'description' => __( 'The four counters on the homepage. Use real numbers once available.', 'estatepress' ),
		)
	);

	$wp_customize->add_setting( 'estatepress_stats_demo', array( 'default' => true, 'sanitize_callback' => 'estatepress_sanitize_checkbox' ) );
	$wp_customize->add_control( 'estatepress_stats_demo', array( 'label' => __( 'Show "sample data" note', 'estatepress' ), 'section' => 'estatepress_stats', 'type' => 'checkbox' ) );

	$wp_customize->add_setting( 'estatepress_stats_note', array( 'default' => __( 'Sample data — replace with your real numbers.', 'estatepress' ), 'sanitize_callback' => 'sanitize_text_field' ) );
	$wp_customize->add_control( 'estatepress_stats_note', array( 'label' => __( 'Note text', 'estatepress' ), 'section' => 'estatepress_stats', 'type' => 'text' ) );

	$stat_defaults = array(
		1 => array( '1200', __( 'Properties Sold', 'estatepress' ), __( 'Total successful sales to date', 'estatepress' ) ),
		2 => array( '500', __( 'Happy Clients', 'estatepress' ), __( 'Buyers, sellers and tenants served', 'estatepress' ) ),
		3 => array( '10', __( 'Years Experience', 'estatepress' ), __( 'Active in the Bangladesh market', 'estatepress' ) ),
		4 => array( '8', __( 'Locations', 'estatepress' ), __( 'Cities and neighbourhoods covered', 'estatepress' ) ),
	);
	foreach ( $stat_defaults as $i => $stat ) {
		$wp_customize->add_setting( "estatepress_stat{$i}_value", array( 'default' => $stat[0], 'sanitize_callback' => 'sanitize_text_field' ) );
		$wp_customize->add_control( "estatepress_stat{$i}_value", array( 'label' => sprintf( __( 'Counter %d — value', 'estatepress' ), $i ), 'section' => 'estatepress_stats', 'type' => 'text' ) );
		$wp_customize->add_setting( "estatepress_stat{$i}_label", array( 'default' => $stat[1], 'sanitize_callback' => 'sanitize_text_field' ) );
		$wp_customize->add_control( "estatepress_stat{$i}_label", array( 'label' => sprintf( __( 'Counter %d — label', 'estatepress' ), $i ), 'section' => 'estatepress_stats', 'type' => 'text' ) );
		$wp_customize->add_setting( "estatepress_stat{$i}_hint", array( 'default' => $stat[2], 'sanitize_callback' => 'sanitize_text_field' ) );
		$wp_customize->add_control( "estatepress_stat{$i}_hint", array( 'label' => sprintf( __( 'Counter %d — hover note', 'estatepress' ), $i ), 'section' => 'estatepress_stats', 'type' => 'text' ) );
	}

	/* Testimonials -------------------------------------------------------- */
	$wp_customize->add_section(
		'estatepress_testimonials',
		array(
			'title' => __( 'Testimonials', 'estatepress' ),
			'panel' => 'estatepress_options',
		)
	);
	$testimonial_defaults = array(
		1 => array( __( 'They found us the perfect family apartment in Dhanmondi within two weeks. Completely transparent throughout.', 'estatepress' ), __( 'Rafi Samnan', 'estatepress' ), __( 'Home Buyer', 'estatepress' ) ),
		2 => array( __( 'Professional, responsive and honest. The paperwork support saved us a lot of time.', 'estatepress' ), __( 'Ayesha Haque', 'estatepress' ), __( 'Investor', 'estatepress' ) ),
		3 => array( __( 'Rented out my Gulshan office within days. Great reach and quality leads.', 'estatepress' ), __( 'Mahmud Karim', 'estatepress' ), __( 'Landlord', 'estatepress' ) ),
	);
	foreach ( $testimonial_defaults as $i => $t ) {
		$wp_customize->add_setting( "estatepress_testimonial{$i}_quote", array( 'default' => $t[0], 'sanitize_callback' => 'sanitize_textarea_field' ) );
		$wp_customize->add_control( "estatepress_testimonial{$i}_quote", array( 'label' => sprintf( __( 'Testimonial %d — quote', 'estatepress' ), $i ), 'section' => 'estatepress_testimonials', 'type' => 'textarea' ) );
		$wp_customize->add_setting( "estatepress_testimonial{$i}_name", array( 'default' => $t[1], 'sanitize_callback' => 'sanitize_text_field' ) );
		$wp_customize->add_control( "estatepress_testimonial{$i}_name", array( 'label' => sprintf( __( 'Testimonial %d — name', 'estatepress' ), $i ), 'section' => 'estatepress_testimonials', 'type' => 'text' ) );
		$wp_customize->add_setting( "estatepress_testimonial{$i}_role", array( 'default' => $t[2], 'sanitize_callback' => 'sanitize_text_field' ) );
		$wp_customize->add_control( "estatepress_testimonial{$i}_role", array( 'label' => sprintf( __( 'Testimonial %d — role', 'estatepress' ), $i ), 'section' => 'estatepress_testimonials', 'type' => 'text' ) );
		$wp_customize->add_setting( "estatepress_testimonial{$i}_avatar", array( 'default' => '', 'sanitize_callback' => 'esc_url_raw' ) );
		$wp_customize->add_control( new WP_Customize_Image_Control( $wp_customize, "estatepress_testimonial{$i}_avatar", array( 'label' => sprintf( __( 'Testimonial %d — photo', 'estatepress' ), $i ), 'section' => 'estatepress_testimonials' ) ) );
	}

	/* Agents -------------------------------------------------------------- */
	$wp_customize->add_section(
		'estatepress_agents',
		array(
			'title' => __( 'Agents', 'estatepress' ),
			'panel' => 'estatepress_options',
		)
	);
	$agent_defaults = array(
		1 => array( __( 'Rafi Samnan', 'estatepress' ), __( 'Senior Consultant', 'estatepress' ) ),
		2 => array( __( 'Ayesha Haque', 'estatepress' ), __( 'Residential Expert', 'estatepress' ) ),
		3 => array( __( 'Mahmud Karim', 'estatepress' ), __( 'Commercial Advisor', 'estatepress' ) ),
		4 => array( __( 'Tanvir Nabil', 'estatepress' ), __( 'Client Relations', 'estatepress' ) ),
	);
	foreach ( $agent_defaults as $i => $a ) {
		$wp_customize->add_setting( "estatepress_agent{$i}_name", array( 'default' => $a[0], 'sanitize_callback' => 'sanitize_text_field' ) );
		$wp_customize->add_control( "estatepress_agent{$i}_name", array( 'label' => sprintf( __( 'Agent %d — name', 'estatepress' ), $i ), 'section' => 'estatepress_agents', 'type' => 'text' ) );
		$wp_customize->add_setting( "estatepress_agent{$i}_role", array( 'default' => $a[1], 'sanitize_callback' => 'sanitize_text_field' ) );
		$wp_customize->add_control( "estatepress_agent{$i}_role", array( 'label' => sprintf( __( 'Agent %d — role', 'estatepress' ), $i ), 'section' => 'estatepress_agents', 'type' => 'text' ) );
		$wp_customize->add_setting( "estatepress_agent{$i}_avatar", array( 'default' => '', 'sanitize_callback' => 'esc_url_raw' ) );
		$wp_customize->add_control( new WP_Customize_Image_Control( $wp_customize, "estatepress_agent{$i}_avatar", array( 'label' => sprintf( __( 'Agent %d — photo', 'estatepress' ), $i ), 'section' => 'estatepress_agents' ) ) );
	}
}
add_action( 'customize_register', 'estatepress_customize_register' );

/**
 * Sanitize a checkbox value.
 *
 * @param mixed $value Raw value.
 * @return bool
 */
function estatepress_sanitize_checkbox( $value ) {
	return (bool) $value;
}
