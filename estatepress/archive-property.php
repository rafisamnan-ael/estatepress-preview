<?php
/**
 * Property archive template.
 *
 * @package EstatePress
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

get_header();

$term = get_queried_object();
?>
<div class="ep-page-head">
	<div class="ep-container">
		<h1 class="ep-page-title">
			<?php
			if ( is_post_type_archive( 'property' ) ) {
				esc_html_e( 'All Properties', 'estatepress' );
			} elseif ( is_tax() && $term instanceof WP_Term ) {
				echo esc_html( $term->name );
			} else {
				esc_html_e( 'Properties', 'estatepress' );
			}
			?>
		</h1>
		<?php echo estatepress_breadcrumbs(); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
	</div>
</div>

<div class="ep-container ep-archive-layout">
	<aside class="ep-filters">
		<form class="ep-filter-form" method="get" action="<?php echo esc_url( get_post_type_archive_link( 'property' ) ); ?>">
			<h2 class="ep-filter-title"><?php esc_html_e( 'Filter Properties', 'estatepress' ); ?></h2>
			<input type="hidden" name="post_type" value="property" />

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
					<input type="number" name="ep_min_price" value="<?php echo esc_attr( isset( $_GET['ep_min_price'] ) ? sanitize_text_field( wp_unslash( $_GET['ep_min_price'] ) ) : '' ); ?>" placeholder="0" />
				</label>
				<label class="ep-field">
					<span class="ep-field-label"><?php esc_html_e( 'Max Price', 'estatepress' ); ?></span>
					<input type="number" name="ep_max_price" value="<?php echo esc_attr( isset( $_GET['ep_max_price'] ) ? sanitize_text_field( wp_unslash( $_GET['ep_max_price'] ) ) : '' ); ?>" placeholder="Any" />
				</label>
			</div>

			<label class="ep-field">
				<span class="ep-field-label"><?php esc_html_e( 'Min Bedrooms', 'estatepress' ); ?></span>
				<select name="ep_beds">
					<option value=""><?php esc_html_e( 'Any', 'estatepress' ); ?></option>
					<?php for ( $i = 1; $i <= 5; $i++ ) : ?>
						<option value="<?php echo esc_attr( $i ); ?>" <?php selected( isset( $_GET['ep_beds'] ) ? absint( $_GET['ep_beds'] ) : 0, $i ); ?>><?php echo esc_html( $i ); ?>+</option>
					<?php endfor; ?>
				</select>
			</label>

			<button type="submit" class="ep-btn ep-btn-primary ep-btn-block"><?php esc_html_e( 'Apply Filters', 'estatepress' ); ?></button>
			<a class="ep-reset" href="<?php echo esc_url( get_post_type_archive_link( 'property' ) ); ?>"><?php esc_html_e( 'Reset', 'estatepress' ); ?></a>
		</form>
	</aside>

	<main class="ep-archive-main">
		<div class="ep-archive-bar">
			<p class="ep-result-count">
				<?php
				global $wp_query;
				printf(
					/* translators: %d: number of results. */
					esc_html__( '%d properties found', 'estatepress' ),
					(int) $wp_query->found_posts
				);
				?>
			</p>
		</div>

		<?php if ( have_posts() ) : ?>
			<div class="ep-grid ep-grid-3">
				<?php
				while ( have_posts() ) :
					the_post();
					estatepress_property_card();
				endwhile;
				?>
			</div>
			<?php estatepress_pagination(); ?>
		<?php else : ?>
			<div class="ep-empty-state">
				<h2><?php esc_html_e( 'No properties found', 'estatepress' ); ?></h2>
				<p><?php esc_html_e( 'Try changing or resetting your filters.', 'estatepress' ); ?></p>
				<a class="ep-btn ep-btn-primary" href="<?php echo esc_url( get_post_type_archive_link( 'property' ) ); ?>"><?php esc_html_e( 'View all properties', 'estatepress' ); ?></a>
			</div>
		<?php endif; ?>
	</main>
</div>

<?php
get_footer();
