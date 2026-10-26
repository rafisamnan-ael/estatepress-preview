<?php
/**
 * EstatePress functions and definitions.
 *
 * @package EstatePress
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

define( 'ESTATEPRESS_VERSION', '1.0.0' );
define( 'ESTATEPRESS_DIR', get_template_directory() );
define( 'ESTATEPRESS_URI', get_template_directory_uri() );

/**
 * Theme setup.
 */
function estatepress_setup() {
	load_theme_textdomain( 'estatepress', ESTATEPRESS_DIR . '/languages' );

	add_theme_support( 'automatic-feed-links' );
	add_theme_support( 'title-tag' );
	add_theme_support( 'post-thumbnails' );
	add_theme_support( 'customize-selective-refresh-widgets' );
	add_theme_support( 'responsive-embeds' );
	add_theme_support( 'align-wide' );
	add_theme_support(
		'html5',
		array( 'search-form', 'comment-form', 'comment-list', 'gallery', 'caption', 'style', 'script' )
	);
	add_theme_support(
		'custom-logo',
		array(
			'height'      => 60,
			'width'       => 220,
			'flex-height' => true,
			'flex-width'  => true,
		)
	);

	register_nav_menus(
		array(
			'primary' => __( 'Primary Menu', 'estatepress' ),
			'footer'  => __( 'Footer Menu', 'estatepress' ),
		)
	);

	add_image_size( 'estatepress-card', 600, 400, true );
	add_image_size( 'estatepress-hero', 1600, 900, true );
	add_image_size( 'estatepress-thumb', 200, 200, true );
}
add_action( 'after_setup_theme', 'estatepress_setup' );

/**
 * Set the content width.
 */
function estatepress_content_width() {
	$GLOBALS['content_width'] = apply_filters( 'estatepress_content_width', 1200 );
}
add_action( 'after_setup_theme', 'estatepress_content_width', 0 );

/**
 * Enqueue styles and scripts.
 */
function estatepress_scripts() {
	wp_enqueue_style( 'estatepress-style', get_stylesheet_uri(), array(), ESTATEPRESS_VERSION );
	wp_enqueue_style(
		'estatepress-main',
		ESTATEPRESS_URI . '/assets/css/main.css',
		array( 'estatepress-style' ),
		ESTATEPRESS_VERSION
	);
	wp_enqueue_style(
		'estatepress-fonts',
		'https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&family=Sora:wght@600;700;800&display=swap',
		array(),
		null
	);

	wp_enqueue_script(
		'estatepress-main',
		ESTATEPRESS_URI . '/assets/js/main.js',
		array(),
		ESTATEPRESS_VERSION,
		true
	);

	wp_enqueue_script(
		'estatepress-interactions',
		ESTATEPRESS_URI . '/assets/js/interactions.js',
		array( 'estatepress-main' ),
		ESTATEPRESS_VERSION,
		true
	);

	if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
		wp_enqueue_script( 'comment-reply' );
	}
}
add_action( 'wp_enqueue_scripts', 'estatepress_scripts' );

/**
 * Widget areas.
 */
function estatepress_widgets_init() {
	register_sidebar(
		array(
			'name'          => __( 'Sidebar', 'estatepress' ),
			'id'            => 'sidebar-1',
			'description'   => __( 'Add widgets here.', 'estatepress' ),
			'before_widget' => '<section id="%1$s" class="widget %2$s">',
			'after_widget'  => '</section>',
			'before_title'  => '<h2 class="widget-title">',
			'after_title'   => '</h2>',
		)
	);

	for ( $i = 1; $i <= 3; $i++ ) {
		register_sidebar(
			array(
				/* translators: %d: footer column number. */
				'name'          => sprintf( __( 'Footer Column %d', 'estatepress' ), $i ),
				'id'            => 'footer-' . $i,
				'before_widget' => '<section id="%1$s" class="widget %2$s">',
				'after_widget'  => '</section>',
				'before_title'  => '<h3 class="widget-title">',
				'after_title'   => '</h3>',
			)
		);
	}
}
add_action( 'widgets_init', 'estatepress_widgets_init' );

/**
 * Body classes.
 */
function estatepress_body_classes( $classes ) {
	if ( ! is_active_sidebar( 'sidebar-1' ) ) {
		$classes[] = 'no-sidebar';
	}
	if ( is_front_page() ) {
		$classes[] = 'is-front-page';
	}
	return $classes;
}
add_filter( 'body_class', 'estatepress_body_classes' );

/**
 * Excerpt length.
 */
function estatepress_excerpt_length( $length ) {
	return 24;
}
add_filter( 'excerpt_length', 'estatepress_excerpt_length' );

/**
 * Excerpt more.
 */
function estatepress_excerpt_more( $more ) {
	return '&hellip;';
}
add_filter( 'excerpt_more', 'estatepress_excerpt_more' );

require ESTATEPRESS_DIR . '/inc/post-types.php';
require ESTATEPRESS_DIR . '/inc/query.php';
require ESTATEPRESS_DIR . '/inc/meta-boxes.php';
require ESTATEPRESS_DIR . '/inc/template-tags.php';
require ESTATEPRESS_DIR . '/inc/customizer.php';
require ESTATEPRESS_DIR . '/inc/contact-form.php';
require ESTATEPRESS_DIR . '/inc/demo-content.php';
