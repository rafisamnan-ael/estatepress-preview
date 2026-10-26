<?php
/**
 * Template Name: Property Listings
 *
 * @package EstatePress
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

get_header();

$paged = max( 1, get_query_var( 'paged' ), get_query_var( 'page' ) );

$args = array(
	'post_type'      => 'property',
	'post_status'    => 'publish',
	'posts_per_page' => 9,
	'paged'          => $paged,
);

$tax_query  = array();
$meta_query = array();

foreach ( array( 'property_type', 'property_location', 'property_status' ) as $tax ) {
	if ( ! empty( $_GET[ $tax ] ) ) {
		$tax_query[] = array(
			'taxonomy' => $tax,
			'field'    => 'slug',
			'terms'    => sanitize_text_field( wp_unslash( $_GET[ $tax ] ) ),
		);
	}
}

$min = isset( $_GET['ep_min_price'] ) ? (int) preg_replace( '/[^0-9]/', '', wp_unslash( $_GET['ep_min_price'] ) ) : 0;
$max = isset( $_GET['ep_max_price'] ) ? (int) preg_replace( '/[^0-9]/', '', wp_unslash( $_GET['ep_max_price'] ) ) : 0;
if ( $min ) {
	$meta_query[] = array( 'key' => '_ep_price', 'value' => $min, 'type' => 'NUMERIC', 'compare' => '>=' );
}
if ( $max ) {
	$meta_query[] = array( 'key' => '_ep_price', 'value' => $max, 'type' => 'NUMERIC', 'compare' => '<=' );
}
$beds = isset( $_GET['ep_beds'] ) ? absint( $_GET['ep_beds'] ) : 0;
if ( $beds ) {
	$meta_query[] = array( 'key' => '_ep_beds', 'value' => $beds, 'type' => 'NUMERIC', 'compare' => '>=' );
}

if ( ! empty( $_GET['s'] ) ) {
	$args['s'] = sanitize_text_field( wp_unslash( $_GET['s'] ) );
}
if ( $tax_query ) {
	$args['tax_query'] = $tax_query; // phpcs:ignore WordPress.DB.SlowDBQuery.slow_db_query_tax_query
}
if ( $meta_query ) {
	$args['meta_query'] = $meta_query; // phpcs:ignore WordPress.DB.SlowDBQuery.slow_db_query_meta_query
}

$listings = new WP_Query( $args );
?>
<div class="ep-page-head">
	<div class="ep-container">
		<h1 class="ep-page-title"><?php the_title(); ?></h1>
		<?php echo estatepress_breadcrumbs(); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
	</div>
</div>

<div class="ep-container ep-archive-layout">
	<aside class="ep-filters">
		<form class="ep-filter-form" method="get" action="<?php the_permalink(); ?>">
			<h2 class="ep-filter-title"><?php esc_html_e( 'Filter Properties', 'estatepress' ); ?></h2>

			<label class="ep-field">
				<span class="ep-field-label"><?php esc_html_e( 'Keyword', 'estatepress' ); ?></span>
				<input type="text" name="s" value="<?php echo esc_attr( get_search_query() ); ?>" placeholder="<?php esc_attr_e( 'Title or area…', 'estatepress' ); ?>" />
			</label>

			<?php
			estatepress_tax_dropdown( 'property_status', __( 'Status', 'estatepress' ), 'property_status' );
			estatepress_tax_dropdown( 'property_type', __( 'Property Type', 'estatepress' ), 'property_type' );
			estatepress_tax_dropdown( 'property_location', __( 'Location', 'estatepress' ), 'property_location' );
			?>

			<div class="ep-field-row">
				<label class="ep-field">
					<span class="ep-field-label"><?php esc_html_e( 'Min Price', 'estatepress' ); ?></span>
					<input type="number" name="ep_min_price" value="<?php echo esc_attr( $min ? $min : '' ); ?>" placeholder="0" />
				</label>
				<label class="ep-field">
					<span class="ep-field-label"><?php esc_html_e( 'Max Price', 'estatepress' ); ?></span>
					<input type="number" name="ep_max_price" value="<?php echo esc_attr( $max ? $max : '' ); ?>" placeholder="Any" />
				</label>
			</div>

			<label class="ep-field">
				<span class="ep-field-label"><?php esc_html_e( 'Min Bedrooms', 'estatepress' ); ?></span>
				<select name="ep_beds">
					<option value=""><?php esc_html_e( 'Any', 'estatepress' ); ?></option>
					<?php for ( $i = 1; $i <= 5; $i++ ) : ?>
						<option value="<?php echo esc_attr( $i ); ?>" <?php selected( $beds, $i ); ?>><?php echo esc_html( $i ); ?>+</option>
					<?php endfor; ?>
				</select>
			</label>

			<button type="submit" class="ep-btn ep-btn-primary ep-btn-block"><?php esc_html_e( 'Apply Filters', 'estatepress' ); ?></button>
			<a class="ep-reset" href="<?php the_permalink(); ?>"><?php esc_html_e( 'Reset', 'estatepress' ); ?></a>
		</form>
	</aside>

	<main class="ep-archive-main">
		<div class="ep-archive-bar">
			<p class="ep-result-count">
				<?php
				printf(
					/* translators: %d: number of results. */
					esc_html__( '%d properties found', 'estatepress' ),
					(int) $listings->found_posts
				);
				?>
			</p>
		</div>

		<?php if ( $listings->have_posts() ) : ?>
			<div class="ep-grid ep-grid-3">
				<?php
				while ( $listings->have_posts() ) :
					$listings->the_post();
					estatepress_property_card();
				endwhile;
				?>
			</div>
			<?php
			echo paginate_links( // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
				array(
					'total'     => $listings->max_num_pages,
					'current'   => $paged,
					'prev_text' => '&larr;',
					'next_text' => '&rarr;',
				)
			);
			wp_reset_postdata();
			?>
		<?php else : ?>
			<div class="ep-empty-state">
				<h2><?php esc_html_e( 'No properties found', 'estatepress' ); ?></h2>
				<p><?php esc_html_e( 'Try changing or resetting your filters.', 'estatepress' ); ?></p>
			</div>
		<?php endif; ?>
	</main>
</div>

<?php
get_footer();
