<?php
/**
 * Inquiry form handling and storage.
 *
 * @package EstatePress
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Register the inquiry post type (private lead storage).
 */
function estatepress_register_inquiry() {
	register_post_type(
		'ep_inquiry',
		array(
			'labels'          => array(
				'name'          => __( 'Inquiries', 'estatepress' ),
				'singular_name' => __( 'Inquiry', 'estatepress' ),
				'menu_name'     => __( 'Inquiries', 'estatepress' ),
			),
			'public'          => false,
			'show_ui'         => true,
			'menu_icon'       => 'dashicons-email-alt',
			'menu_position'   => 26,
			'supports'        => array( 'title', 'editor' ),
			'capability_type' => 'post',
			'capabilities'    => array( 'create_posts' => 'do_not_allow' ),
			'map_meta_cap'    => true,
		)
	);
}
add_action( 'init', 'estatepress_register_inquiry' );

/**
 * Handle inquiry form submission.
 */
function estatepress_handle_inquiry() {
	$redirect = wp_get_referer() ? wp_get_referer() : home_url( '/' );

	if ( ! isset( $_POST['estatepress_inquiry_nonce'] ) || ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['estatepress_inquiry_nonce'] ) ), 'estatepress_inquiry' ) ) {
		wp_safe_redirect( add_query_arg( 'inquiry', 'error', $redirect ) );
		exit;
	}

	$name    = isset( $_POST['ep_name'] ) ? sanitize_text_field( wp_unslash( $_POST['ep_name'] ) ) : '';
	$email   = isset( $_POST['ep_email'] ) ? sanitize_email( wp_unslash( $_POST['ep_email'] ) ) : '';
	$phone   = isset( $_POST['ep_phone'] ) ? sanitize_text_field( wp_unslash( $_POST['ep_phone'] ) ) : '';
	$message = isset( $_POST['ep_message'] ) ? sanitize_textarea_field( wp_unslash( $_POST['ep_message'] ) ) : '';
	$prop_id = isset( $_POST['ep_property'] ) ? absint( $_POST['ep_property'] ) : 0;

	if ( '' === $name || ( '' === $email && '' === $phone ) ) {
		wp_safe_redirect( add_query_arg( 'inquiry', 'invalid', $redirect ) );
		exit;
	}

	$property_title = $prop_id ? get_the_title( $prop_id ) : __( 'General Inquiry', 'estatepress' );

	$inquiry_id = wp_insert_post(
		array(
			'post_type'   => 'ep_inquiry',
			'post_status' => 'publish',
			'post_title'  => sprintf( '%s - %s', $property_title, $name ),
			'post_content'=> $message,
		)
	);

	if ( $inquiry_id && ! is_wp_error( $inquiry_id ) ) {
		update_post_meta( $inquiry_id, '_ep_name', $name );
		update_post_meta( $inquiry_id, '_ep_email', $email );
		update_post_meta( $inquiry_id, '_ep_phone', $phone );
		update_post_meta( $inquiry_id, '_ep_property', $prop_id );

		$to      = get_option( 'admin_email' );
		$subject = sprintf( __( 'New inquiry: %s', 'estatepress' ), $property_title );
		$body    = sprintf(
			"Name: %s\nEmail: %s\nPhone: %s\nProperty: %s\n\nMessage:\n%s",
			$name,
			$email,
			$phone,
			$property_title,
			$message
		);
		wp_mail( $to, $subject, $body );
	}

	wp_safe_redirect( add_query_arg( 'inquiry', 'sent', $redirect ) );
	exit;
}
add_action( 'admin_post_estatepress_inquiry', 'estatepress_handle_inquiry' );
add_action( 'admin_post_nopriv_estatepress_inquiry', 'estatepress_handle_inquiry' );

/**
 * Render the inquiry form.
 *
 * @param int $property_id Optional property ID.
 */
function estatepress_inquiry_form( $property_id = 0 ) {
	$property_id = $property_id ? $property_id : get_the_ID();
	?>
	<form class="ep-inquiry-form" method="post" action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>">
		<input type="hidden" name="action" value="estatepress_inquiry" />
		<input type="hidden" name="ep_property" value="<?php echo esc_attr( $property_id ); ?>" />
		<?php wp_nonce_field( 'estatepress_inquiry', 'estatepress_inquiry_nonce' ); ?>
		<p>
			<label for="ep_name"><?php esc_html_e( 'Your Name', 'estatepress' ); ?> <span class="req">*</span></label>
			<input type="text" id="ep_name" name="ep_name" required />
		</p>
		<p class="ep-form-row">
			<span>
				<label for="ep_email"><?php esc_html_e( 'Email', 'estatepress' ); ?></label>
				<input type="email" id="ep_email" name="ep_email" />
			</span>
			<span>
				<label for="ep_phone"><?php esc_html_e( 'Phone', 'estatepress' ); ?></label>
				<input type="tel" id="ep_phone" name="ep_phone" />
			</span>
		</p>
		<?php
		$default_message = $property_id
			? sprintf( __( 'I am interested in "%s". Please contact me.', 'estatepress' ), get_the_title( $property_id ) )
			: __( 'Hello, I would like to know more about your properties.', 'estatepress' );
		?>
		<p>
			<label for="ep_message"><?php esc_html_e( 'Message', 'estatepress' ); ?></label>
			<textarea id="ep_message" name="ep_message" rows="4"><?php echo esc_textarea( $default_message ); ?></textarea>
		</p>
		<button type="submit" class="ep-btn ep-btn-primary"><?php esc_html_e( 'Send Inquiry', 'estatepress' ); ?></button>

		<?php if ( isset( $_GET['inquiry'] ) ) : ?>
			<?php $status = sanitize_text_field( wp_unslash( $_GET['inquiry'] ) ); ?>
			<?php if ( 'sent' === $status ) : ?>
				<p class="ep-form-note success"><?php esc_html_e( 'Thank you! Your inquiry has been sent.', 'estatepress' ); ?></p>
			<?php elseif ( 'invalid' === $status ) : ?>
				<p class="ep-form-note error"><?php esc_html_e( 'Please provide your name and a phone number or email.', 'estatepress' ); ?></p>
			<?php else : ?>
				<p class="ep-form-note error"><?php esc_html_e( 'Something went wrong. Please try again.', 'estatepress' ); ?></p>
			<?php endif; ?>
		<?php endif; ?>
	</form>
	<?php
}

/**
 * Inquiry admin columns.
 */
function estatepress_inquiry_columns( $columns ) {
	return array(
		'cb'        => $columns['cb'],
		'title'     => __( 'Inquiry', 'estatepress' ),
		'ep_contact'=> __( 'Contact', 'estatepress' ),
		'ep_prop'   => __( 'Property', 'estatepress' ),
		'date'      => __( 'Date', 'estatepress' ),
	);
}
add_filter( 'manage_ep_inquiry_posts_columns', 'estatepress_inquiry_columns' );

/**
 * Inquiry column content.
 */
function estatepress_inquiry_column( $column, $post_id ) {
	if ( 'ep_contact' === $column ) {
		$phone = get_post_meta( $post_id, '_ep_phone', true );
		$email = get_post_meta( $post_id, '_ep_email', true );
		echo esc_html( trim( $phone . ' ' . $email ) );
	}
	if ( 'ep_prop' === $column ) {
		$prop = get_post_meta( $post_id, '_ep_property', true );
		if ( $prop ) {
			echo '<a href="' . esc_url( get_edit_post_link( $prop ) ) . '">' . esc_html( get_the_title( $prop ) ) . '</a>';
		} else {
			esc_html_e( 'General', 'estatepress' );
		}
	}
}
add_action( 'manage_ep_inquiry_posts_custom_column', 'estatepress_inquiry_column', 10, 2 );
