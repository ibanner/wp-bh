<?php
/**
 * Search form
 *
 * @author 		Beit Hatfutsot
 * @package 	bh
 * @version     2.0
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

global $current_site;
$search_string = $current_site == 'shop' ? __('Search in shop...', 'BH') : __('Search in site...', 'BH');

?>

<form method="get" class="search-form" action="<?php echo HOME; ?>">
	<?php echo $current_site == 'shop' ? '<input type="hidden" name="post_type" value="product" />' : ''; ?>
	<input type="text" class="search-field <?php echo ( isset($_GET['s']) && $_GET['s'] ) ? 'active' : ''; ?>" value="<?php echo ( isset($_GET['s']) && $_GET['s'] ) ? $_GET['s'] : $search_string; ?>" name="s" onfocus="if (this.value == '<?php echo $search_string; ?>') {this.value = '';}" onblur="if (this.value == '') {this.value = '<?php echo $search_string; ?>';}" />
	<div class="search-submit-wrapper">
		<div class="magnifying-glass"></div>
		<input type="submit" class="search-submit" value="" title="<?php echo $search_string; ?>" alt="<?php echo $search_string; ?>" />
	</div>
</form>