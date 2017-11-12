<?php
/**
 * index.php
 *
 * @author		Beit Hatfutsot
 * @package		wifi-login
 * @since		1.0.0
 * @version		1.0.0
 */

?>

<?php
	/**
	 * Configuration
	 */
	include( 'functions/config.php' );

?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

<?php
	/**
	 * Display header meta
	 */
	include( 'views/header/header-meta.php' );
?>

<body>

	<?php
		/**
		 * Display SVG sprite
		 */
		include( 'views/header/header-svg.php' );
	?>

	<div class="wrapper">
		<div class="content">

			<?php
			/**
			 * Display the logo
			 */
			?>
			<div class="logo-wrapper">
				<div class="logo">
					<?php include( 'views/svgs/shape-logo.php' ); ?>
				</div>
			</div>

			<?php
				/**
				 * Display the registration form
				 */
				include( 'views/form/form.php' );
			?>

		</div><!-- .content -->
	</div><!-- .wrapper -->

</body>
</html>