<?php
/**
 * Header template.
 *
 * @package EstatePress
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>
<!doctype html>
<html <?php language_attributes(); ?>>
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>" />
	<meta name="viewport" content="width=device-width, initial-scale=1" />
	<link rel="profile" href="https://gmpg.org/xfn/11" />
	<?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
<?php wp_body_open(); ?>
<a class="skip-link screen-reader-text" href="#content"><?php esc_html_e( 'Skip to content', 'estatepress' ); ?></a>
<div class="ep-scroll-progress" aria-hidden="true"></div>

<header class="ep-header">
	<div class="ep-container ep-header-inner">
		<div class="ep-brand">
			<?php if ( has_custom_logo() ) : ?>
				<?php the_custom_logo(); ?>
			<?php else : ?>
				<a class="ep-site-title" href="<?php echo esc_url( home_url( '/' ) ); ?>">
					<span class="ep-logo-mark" aria-hidden="true">
						<svg viewBox="0 0 24 24" width="26" height="26" fill="none" stroke="currentColor" stroke-width="2"><path d="M3 10.5 12 3l9 7.5"/><path d="M5 9.5V21h14V9.5"/><path d="M9.5 21v-6h5v6"/></svg>
					</span>
					<?php bloginfo( 'name' ); ?>
				</a>
			<?php endif; ?>
		</div>

		<button class="ep-menu-toggle" aria-expanded="false" aria-controls="ep-primary-nav">
			<span class="screen-reader-text"><?php esc_html_e( 'Menu', 'estatepress' ); ?></span>
			<span></span><span></span><span></span>
		</button>

		<nav id="ep-primary-nav" class="ep-nav" aria-label="<?php esc_attr_e( 'Primary Menu', 'estatepress' ); ?>">
			<?php
			wp_nav_menu(
				array(
					'theme_location' => 'primary',
					'container'      => false,
					'menu_class'     => 'ep-menu',
					'fallback_cb'    => 'estatepress_default_menu',
					'depth'          => 2,
				)
			);
			?>
			<div class="ep-nav-cta">
				<?php $phone = get_theme_mod( 'estatepress_phone', '' ); ?>
				<?php if ( $phone ) : ?>
					<a class="ep-nav-phone" href="tel:<?php echo esc_attr( preg_replace( '/[^0-9+]/', '', $phone ) ); ?>">
						<svg viewBox="0 0 24 24" width="16" height="16" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 16.9v3a2 2 0 0 1-2.2 2 19.8 19.8 0 0 1-8.6-3.1 19.5 19.5 0 0 1-6-6A19.8 19.8 0 0 1 2 4.2 2 2 0 0 1 4 2h3a2 2 0 0 1 2 1.7c.1 1 .4 1.9.7 2.8a2 2 0 0 1-.5 2.1L8.1 9.9a16 16 0 0 0 6 6l1.3-1.1a2 2 0 0 1 2.1-.5c.9.3 1.8.6 2.8.7a2 2 0 0 1 1.7 2z"/></svg>
						<?php echo esc_html( $phone ); ?>
					</a>
				<?php endif; ?>
			</div>
		</nav>
	</div>
</header>

<div id="content" class="ep-site-content">
