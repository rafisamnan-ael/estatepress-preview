<?php
/**
 * Sidebar template.
 *
 * @package EstatePress
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! is_active_sidebar( 'sidebar-1' ) ) {
	return;
}
?>
<aside id="secondary" class="ep-sidebar widget-area">
	<?php dynamic_sidebar( 'sidebar-1' ); ?>
</aside>
