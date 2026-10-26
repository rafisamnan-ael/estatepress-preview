<?php
/**
 * Property meta boxes and saving.
 *
 * @package EstatePress
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Register meta boxes.
 */
function estatepress_add_meta_boxes() {
	add_meta_box( 'ep_property_details', __( 'Property Details', 'estatepress' ), 'estatepress_box_details', 'property', 'normal', 'high' );
	add_meta_box( 'ep_property_features', __( 'Features', 'estatepress' ), 'estatepress_box_features', 'property', 'normal', 'default' );
	add_meta_box( 'ep_property_gallery', __( 'Image Gallery', 'estatepress' ), 'estatepress_box_gallery', 'property', 'normal', 'default' );
	add_meta_box( 'ep_property_agent', __( 'Agent / Contact', 'estatepress' ), 'estatepress_box_agent', 'property', 'side', 'default' );
	add_meta_box( 'ep_property_map', __( 'Location Map', 'estatepress' ), 'estatepress_box_map', 'property', 'normal', 'default' );
}
add_action( 'add_meta_boxes', 'estatepress_add_meta_boxes' );

/**
 * Nonce field.
 */
function estatepress_meta_nonce() {
	wp_nonce_field( 'estatepress_save_meta', 'estatepress_meta_nonce' );
}

/**
 * Details meta box.
 */
function estatepress_box_details( $post ) {
	estatepress_meta_nonce();
	$fields = array(
		'_ep_price'      => array( 'label' => __( 'Price', 'estatepress' ), 'type' => 'text', 'desc' => __( 'e.g. 12,500,000', 'estatepress' ) ),
		'_ep_price_unit' => array( 'label' => __( 'Price Suffix', 'estatepress' ), 'type' => 'text', 'desc' => __( 'e.g. BDT or /month', 'estatepress' ) ),
		'_ep_area'       => array( 'label' => __( 'Area (sqft)', 'estatepress' ), 'type' => 'text', 'desc' => __( 'e.g. 1800', 'estatepress' ) ),
		'_ep_beds'       => array( 'label' => __( 'Bedrooms', 'estatepress' ), 'type' => 'number', 'desc' => '' ),
		'_ep_baths'      => array( 'label' => __( 'Bathrooms', 'estatepress' ), 'type' => 'number', 'desc' => '' ),
		'_ep_parking'    => array( 'label' => __( 'Parking Spaces', 'estatepress' ), 'type' => 'number', 'desc' => '' ),
		'_ep_year'       => array( 'label' => __( 'Year Built', 'estatepress' ), 'type' => 'text', 'desc' => '' ),
		'_ep_reference'  => array( 'label' => __( 'Reference ID', 'estatepress' ), 'type' => 'text', 'desc' => __( 'e.g. EP-1001', 'estatepress' ) ),
		'_ep_address'    => array( 'label' => __( 'Address', 'estatepress' ), 'type' => 'text', 'desc' => '' ),
		'_ep_city'       => array( 'label' => __( 'City', 'estatepress' ), 'type' => 'text', 'desc' => '' ),
	);
	echo '<div class="ep-meta-grid">';
	foreach ( $fields as $key => $field ) {
		$value = get_post_meta( $post->ID, $key, true );
		echo '<p class="ep-meta-field"><label for="' . esc_attr( $key ) . '"><strong>' . esc_html( $field['label'] ) . '</strong></label>';
		echo '<input type="' . esc_attr( $field['type'] ) . '" class="widefat" id="' . esc_attr( $key ) . '" name="' . esc_attr( $key ) . '" value="' . esc_attr( $value ) . '" />';
		if ( $field['desc'] ) {
			echo '<span class="description">' . esc_html( $field['desc'] ) . '</span>';
		}
		echo '</p>';
	}
	echo '</div>';
}

/**
 * Features meta box.
 */
function estatepress_box_features( $post ) {
	$value = get_post_meta( $post->ID, '_ep_features', true );
	echo '<p><label for="_ep_features"><strong>' . esc_html__( 'Features (one per line)', 'estatepress' ) . '</strong></label></p>';
	echo '<textarea class="widefat" rows="6" id="_ep_features" name="_ep_features">' . esc_textarea( $value ) . '</textarea>';
}

/**
 * Gallery meta box.
 */
function estatepress_box_gallery( $post ) {
	$ids = get_post_meta( $post->ID, '_ep_gallery', true );
	$ids = $ids ? array_filter( array_map( 'absint', explode( ',', $ids ) ) ) : array();
	echo '<div id="ep-gallery-wrap">';
	echo '<input type="hidden" id="_ep_gallery" name="_ep_gallery" value="' . esc_attr( implode( ',', $ids ) ) . '" />';
	echo '<ul class="ep-gallery-preview">';
	foreach ( $ids as $id ) {
		$img = wp_get_attachment_image( $id, 'thumbnail' );
		if ( $img ) {
			echo '<li data-id="' . esc_attr( $id ) . '">' . $img . '<span class="ep-gallery-remove">&times;</span></li>';
		}
	}
	echo '</ul>';
	echo '<button type="button" class="button button-primary" id="ep-gallery-add">' . esc_html__( 'Add Images', 'estatepress' ) . '</button>';
	echo '<p class="description">' . esc_html__( 'Select multiple images. The featured image is shown first.', 'estatepress' ) . '</p>';
	echo '</div>';
}

/**
 * Agent meta box.
 */
function estatepress_box_agent( $post ) {
	estatepress_meta_nonce();
	$fields = array(
		'_ep_agent_name'    => __( 'Agent Name', 'estatepress' ),
		'_ep_agent_phone'   => __( 'Phone', 'estatepress' ),
		'_ep_agent_whatsapp'=> __( 'WhatsApp Number', 'estatepress' ),
		'_ep_agent_email'   => __( 'Email', 'estatepress' ),
	);
	foreach ( $fields as $key => $label ) {
		$value = get_post_meta( $post->ID, $key, true );
		echo '<p><label for="' . esc_attr( $key ) . '"><strong>' . esc_html( $label ) . '</strong></label>';
		echo '<input type="text" class="widefat" id="' . esc_attr( $key ) . '" name="' . esc_attr( $key ) . '" value="' . esc_attr( $value ) . '" /></p>';
	}
}

/**
 * Map meta box.
 */
function estatepress_box_map( $post ) {
	$value = get_post_meta( $post->ID, '_ep_map', true );
	echo '<p><label for="_ep_map"><strong>' . esc_html__( 'Google Maps Embed URL', 'estatepress' ) . '</strong></label></p>';
	echo '<input type="url" class="widefat" id="_ep_map" name="_ep_map" value="' . esc_attr( $value ) . '" placeholder="https://www.google.com/maps/embed?pb=..." />';
	echo '<p class="description">' . esc_html__( 'Paste the src URL from Google Maps > Share > Embed a map.', 'estatepress' ) . '</p>';
}

/**
 * Save meta.
 */
function estatepress_save_meta( $post_id ) {
	if ( ! isset( $_POST['estatepress_meta_nonce'] ) || ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['estatepress_meta_nonce'] ) ), 'estatepress_save_meta' ) ) {
		return;
	}
	if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
		return;
	}
	if ( ! current_user_can( 'edit_post', $post_id ) ) {
		return;
	}

	$text_fields = array(
		'_ep_price', '_ep_price_unit', '_ep_area', '_ep_beds', '_ep_baths', '_ep_parking',
		'_ep_year', '_ep_reference', '_ep_address', '_ep_city',
		'_ep_agent_name', '_ep_agent_phone', '_ep_agent_whatsapp', '_ep_agent_email',
	);
	foreach ( $text_fields as $field ) {
		if ( isset( $_POST[ $field ] ) ) {
			update_post_meta( $post_id, $field, sanitize_text_field( wp_unslash( $_POST[ $field ] ) ) );
		}
	}

	if ( isset( $_POST['_ep_features'] ) ) {
		update_post_meta( $post_id, '_ep_features', sanitize_textarea_field( wp_unslash( $_POST['_ep_features'] ) ) );
	}

	if ( isset( $_POST['_ep_gallery'] ) ) {
		$gallery = implode( ',', array_filter( array_map( 'absint', explode( ',', wp_unslash( $_POST['_ep_gallery'] ) ) ) ) );
		update_post_meta( $post_id, '_ep_gallery', $gallery );
	}

	if ( isset( $_POST['_ep_map'] ) ) {
		update_post_meta( $post_id, '_ep_map', esc_url_raw( wp_unslash( $_POST['_ep_map'] ) ) );
	}
}
add_action( 'save_post_property', 'estatepress_save_meta' );

/**
 * Admin assets for the gallery control.
 */
function estatepress_admin_assets( $hook ) {
	global $post_type;
	if ( 'property' !== $post_type ) {
		return;
	}
	wp_enqueue_media();
	wp_enqueue_script( 'estatepress-admin', ESTATEPRESS_URI . '/assets/js/admin.js', array( 'jquery' ), ESTATEPRESS_VERSION, true );
	wp_enqueue_style( 'estatepress-admin', ESTATEPRESS_URI . '/assets/css/admin.css', array(), ESTATEPRESS_VERSION );
}
add_action( 'admin_enqueue_scripts', 'estatepress_admin_assets' );
