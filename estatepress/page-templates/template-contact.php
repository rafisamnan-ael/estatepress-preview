<?php
/**
 * Template Name: Contact
 *
 * @package EstatePress
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

get_header();

$phone   = get_theme_mod( 'estatepress_phone', '' );
$whatsapp= get_theme_mod( 'estatepress_whatsapp', '' );
$email   = get_theme_mod( 'estatepress_email', '' );
$address = get_theme_mod( 'estatepress_address', '' );
?>
<div class="ep-page-head">
	<div class="ep-container">
		<h1 class="ep-page-title"><?php the_title(); ?></h1>
		<?php echo estatepress_breadcrumbs(); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
	</div>
</div>

<div class="ep-container ep-contact-layout">
	<div class="ep-contact-info">
		<?php
		while ( have_posts() ) :
			the_post();
			if ( get_the_content() ) :
				?>
				<div class="ep-entry-content"><?php the_content(); ?></div>
			<?php endif; ?>
		<?php endwhile; ?>

		<ul class="ep-contact-list">
			<?php if ( $phone ) : ?>
				<li>
					<span class="ep-contact-label"><?php esc_html_e( 'Phone', 'estatepress' ); ?></span>
					<a href="tel:<?php echo esc_attr( preg_replace( '/[^0-9+]/', '', $phone ) ); ?>"><?php echo esc_html( $phone ); ?></a>
				</li>
			<?php endif; ?>
			<?php if ( $whatsapp ) : ?>
				<li>
					<span class="ep-contact-label"><?php esc_html_e( 'WhatsApp', 'estatepress' ); ?></span>
					<a href="https://wa.me/<?php echo esc_attr( preg_replace( '/[^0-9]/', '', $whatsapp ) ); ?>" target="_blank" rel="noopener"><?php echo esc_html( $whatsapp ); ?></a>
				</li>
			<?php endif; ?>
			<?php if ( $email ) : ?>
				<li>
					<span class="ep-contact-label"><?php esc_html_e( 'Email', 'estatepress' ); ?></span>
					<a href="mailto:<?php echo esc_attr( $email ); ?>"><?php echo esc_html( $email ); ?></a>
				</li>
			<?php endif; ?>
			<?php if ( $address ) : ?>
				<li>
					<span class="ep-contact-label"><?php esc_html_e( 'Office', 'estatepress' ); ?></span>
					<span><?php echo esc_html( $address ); ?></span>
				</li>
			<?php endif; ?>
		</ul>
	</div>

	<div class="ep-contact-form">
		<h2><?php esc_html_e( 'Send us a message', 'estatepress' ); ?></h2>
		<?php estatepress_inquiry_form( 0 ); ?>
	</div>
</div>

<?php
get_footer();
