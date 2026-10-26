<?php
/**
 * Search form.
 *
 * @package EstatePress
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>
<form role="search" method="get" class="ep-searchform" action="<?php echo esc_url( home_url( '/' ) ); ?>">
	<label class="screen-reader-text" for="ep-search-field"><?php esc_html_e( 'Search for:', 'estatepress' ); ?></label>
	<input type="search" id="ep-search-field" class="ep-search-field" placeholder="<?php esc_attr_e( 'Search…', 'estatepress' ); ?>" value="<?php echo esc_attr( get_search_query() ); ?>" name="s" />
	<button type="submit" class="ep-search-submit"><?php esc_html_e( 'Search', 'estatepress' ); ?></button>
</form>
