<?php $search_string = __('Search in site...', 'BH'); ?>

<form method="get" class="search-form" action="<?php echo HOME; ?>">
	<input type="text" class="search-field <?php echo ( isset($_GET['s']) && $_GET['s'] ) ? 'active' : ''; ?>" value="<?php echo ( isset($_GET['s']) && $_GET['s'] ) ? $_GET['s'] : $search_string; ?>" name="s" onfocus="if (this.value == '<?php echo $search_string; ?>') {this.value = '';}" onblur="if (this.value == '') {this.value = '<?php echo $search_string; ?>';}" />
	<input type="submit" class="search-submit bh-sprites" value="" title="<?php echo $search_string; ?>" alt="<?php echo $search_string; ?>" />
</form>