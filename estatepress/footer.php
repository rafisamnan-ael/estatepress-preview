<?php
/**
 * Footer template.
 *
 * @package EstatePress
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>
	</div><!-- #content -->

	<footer class="ep-footer">
		<div class="ep-container ep-footer-widgets">
			<?php for ( $i = 1; $i <= 3; $i++ ) : ?>
				<div class="ep-footer-col">
					<?php if ( is_active_sidebar( 'footer-' . $i ) ) : ?>
						<?php dynamic_sidebar( 'footer-' . $i ); ?>
					<?php elseif ( 1 === $i ) : ?>
						<h3 class="widget-title"><?php bloginfo( 'name' ); ?></h3>
						<p><?php echo esc_html( get_bloginfo( 'description' ) ); ?></p>
					<?php elseif ( 2 === $i ) : ?>
						<h3 class="widget-title"><?php esc_html_e( 'Contact', 'estatepress' ); ?></h3>
						<ul class="ep-footer-contact">
							<?php $phone = get_theme_mod( 'estatepress_phone', '' ); ?>
							<?php if ( $phone ) : ?>
								<li><?php esc_html_e( 'Phone:', 'estatepress' ); ?> <a href="tel:<?php echo esc_attr( preg_replace( '/[^0-9+]/', '', $phone ) ); ?>"><?php echo esc_html( $phone ); ?></a></li>
							<?php endif; ?>
							<?php $email = get_theme_mod( 'estatepress_email', '' ); ?>
							<?php if ( $email ) : ?>
								<li><?php esc_html_e( 'Email:', 'estatepress' ); ?> <a href="mailto:<?php echo esc_attr( $email ); ?>"><?php echo esc_html( $email ); ?></a></li>
							<?php endif; ?>
							<?php $address = get_theme_mod( 'estatepress_address', '' ); ?>
							<?php if ( $address ) : ?>
								<li><?php echo esc_html( $address ); ?></li>
							<?php endif; ?>
						</ul>
					<?php elseif ( 3 === $i ) : ?>
						<h3 class="widget-title"><?php esc_html_e( 'Follow Us', 'estatepress' ); ?></h3>
						<ul class="ep-social">
							<?php
							foreach ( array( 'facebook', 'instagram', 'linkedin', 'youtube' ) as $network ) {
								$link = get_theme_mod( 'estatepress_' . $network, '' );
								if ( $link ) {
									printf(
										'<li><a href="%s" target="_blank" rel="noopener">%s</a></li>',
										esc_url( $link ),
										esc_html( ucfirst( $network ) )
									);
								}
							}
							?>
						</ul>
					<?php endif; ?>
				</div>
			<?php endfor; ?>
		</div>

		<div class="ep-footer-bottom">
			<div class="ep-container">
				<p>
					<?php
					printf(
						/* translators: 1: year, 2: site name. */
						esc_html__( '© %1$s %2$s. All rights reserved.', 'estatepress' ),
						esc_html( gmdate( 'Y' ) ),
						esc_html( get_bloginfo( 'name' ) )
					);
					?>
				</p>
				<?php
				wp_nav_menu(
					array(
						'theme_location' => 'footer',
						'container'      => false,
						'menu_class'     => 'ep-footer-menu',
						'fallback_cb'    => false,
						'depth'          => 1,
					)
				);
				?>
			</div>
		</div>
	</footer>

	<?php
	$whatsapp = get_theme_mod( 'estatepress_whatsapp', '' );
	$wa_num   = preg_replace( '/[^0-9]/', '', $whatsapp );
	if ( $wa_num ) :
		?>
		<a class="ep-whatsapp-float" href="https://wa.me/<?php echo esc_attr( $wa_num ); ?>" target="_blank" rel="noopener" aria-label="<?php esc_attr_e( 'Chat on WhatsApp', 'estatepress' ); ?>">
			<svg viewBox="0 0 24 24" width="28" height="28" fill="currentColor"><path d="M12 2a10 10 0 0 0-8.6 15l-1.3 4.8 4.9-1.3A10 10 0 1 0 12 2zm0 18a8 8 0 0 1-4.1-1.1l-.3-.2-2.9.8.8-2.8-.2-.3A8 8 0 1 1 12 20zm4.4-6c-.2-.1-1.4-.7-1.6-.8-.2-.1-.4-.1-.5.1l-.7.9c-.1.2-.3.2-.5.1a6.5 6.5 0 0 1-3.2-2.8c-.1-.2 0-.4.1-.5l.5-.6c.1-.2.1-.3 0-.5l-.8-1.9c-.1-.3-.3-.3-.5-.3h-.5c-.2 0-.5.1-.7.4-.2.3-.8 1-.8 2.3s.9 2.6 1 2.8c.1.2 1.8 2.9 4.4 4 .6.3 1.1.4 1.5.5.6.1 1.2.1 1.6 0 .5-.1 1.4-.6 1.6-1.1.2-.5.2-1 .1-1.1-.1-.1-.2-.2-.4-.3z"/></svg>
		</a>
	<?php endif; ?>

	<button type="button" class="ep-backtop" aria-label="<?php esc_attr_e( 'Back to top', 'estatepress' ); ?>">
		<svg viewBox="0 0 24 24" width="20" height="20" fill="none" stroke="currentColor" stroke-width="2"><path d="m6 15 6-6 6 6"/></svg>
	</button>

	<nav class="ep-dotnav" aria-label="<?php esc_attr_e( 'Page sections', 'estatepress' ); ?>"></nav>

<?php wp_footer(); ?>
</body>
</html>
