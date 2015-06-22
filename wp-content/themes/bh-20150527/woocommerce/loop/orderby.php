<?php
/**
 * Show options for ordering
 *
 * @author 		WooThemes
 * @package 	WooCommerce/Templates
 * @version     2.2.0
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

// Keep query string vars intact
$query_string_vars	= array();
$query_string		= '';

foreach ( $_GET as $key => $val ) {
	if ( 'orderby' === $key || 'submit' === $key ) {
		continue;
	}
	
	if ( is_array( $val ) ) {
		foreach( $val as $innerVal ) {
			$query_string_vars[esc_attr($key)] = esc_attr($innerVal);
		}
	} else {
		$query_string_vars[esc_attr($key)] = esc_attr($val);
	}
}

if ( count($query_string_vars) > 0 )
	foreach ($query_string_vars as $key => $val)
		$query_string .= '&' . $key . '=' . $val;
?>

<div class="shop-catalog-ordering">
	<span class="sort-title"><?php _e('Sort by', 'BH'); ?></span>
	
	<ul>
		<?php foreach ($catalog_orderby_options as $id => $name) : ?>
			<li <?php echo ($orderby == $id) ? 'class="active"' : ''; ?>>
				<a href="?orderby=<?php echo esc_attr($id) . $query_string; ?>" id="<?php echo esc_attr($id); ?>"><?php echo esc_html($name); ?></a>
			</li>
		<?php endforeach; ?>
	</ul>
</div>