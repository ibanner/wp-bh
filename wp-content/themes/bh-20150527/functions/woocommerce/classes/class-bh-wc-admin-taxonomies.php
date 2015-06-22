<?php
/**
 * Handles WooCommerce custom taxonomies in admin
 *
 * @class 		BH_WC_Admin_Taxonomies
 * @version		1.0
 * @package		WooCommerce/Admin
 * @category	Class
 * @author 		Beit Hatfutsot
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

/**
 * BH_WC_Admin_Taxonomies class.
 */
class BH_WC_Admin_Taxonomies {

	/**
	 * Constructor
	 */
	public function __construct() {

		// Category/term ordering
		add_action( "create_term", array( $this, 'create_term' ), 5, 3 );
		add_action( "delete_term", array( $this, 'delete_term' ), 5 );

		// Add form
		add_action( 'occasion_add_form_fields', array( $this, 'add_category_fields' ) );
		add_action( 'occasion_edit_form_fields', array( $this, 'edit_category_fields' ), 10, 2 );
		add_action( 'created_term', array( $this, 'save_category_fields' ), 10, 3 );
		add_action( 'edit_term', array( $this, 'save_category_fields' ), 10, 3 );

		// Add columns
		add_filter( 'manage_edit-occasion_columns', array( $this, 'product_cat_columns' ) );
		add_filter( 'manage_occasion_custom_column', array( $this, 'product_cat_column' ), 10, 3 );

		// Taxonomy page descriptions
		add_action( 'occasion_pre_add_form', array( $this, 'occasion_description' ) );

		// Maintain hierarchy of terms
		add_filter( 'wp_terms_checklist_args', array( $this, 'disable_checked_ontop' ) );
	}

	/**
	 * Order term when created (put in position 0).
	 *
	 * @access public
	 * @param mixed $term_id
	 * @param mixed $tt_id
	 * @param mixed $taxonomy
	 * @return void
	 */
	public function create_term( $term_id, $tt_id = '', $taxonomy = '' ) {
		if ( $taxonomy != 'occasion' && ! taxonomy_is_product_attribute( $taxonomy ) )
			return;

		$meta_name = taxonomy_is_product_attribute( $taxonomy ) ? 'order_' . esc_attr( $taxonomy ) : 'order';

		update_woocommerce_term_meta( $term_id, $meta_name, 0 );
	}

	/**
	 * When a term is deleted, delete its meta.
	 *
	 * @access public
	 * @param mixed $term_id
	 * @return void
	 */
	public function delete_term( $term_id ) {

		$term_id = (int) $term_id;

		if ( ! $term_id )
			return;

		global $wpdb;
		$wpdb->query( "DELETE FROM {$wpdb->woocommerce_termmeta} WHERE `woocommerce_term_id` = " . $term_id );
	}

	/**
	 * Category thumbnail fields.
	 *
	 * @access public
	 * @return void
	 */
	public function add_category_fields() {
		?>
		<div class="form-field">
			<label><?php _e( 'Thumbnail', 'woocommerce' ); ?></label>
			<div id="product_cat_thumbnail" style="float:left;margin-right:10px;"><img src="<?php echo wc_placeholder_img_src(); ?>" width="60px" height="60px" /></div>
			<div style="line-height:60px;">
				<input type="hidden" id="product_cat_thumbnail_id" name="product_cat_thumbnail_id" />
				<button type="button" class="upload_image_button button"><?php _e( 'Upload/Add image', 'woocommerce' ); ?></button>
				<button type="button" class="remove_image_button button"><?php _e( 'Remove image', 'woocommerce' ); ?></button>
			</div>
			<script type="text/javascript">

				 // Only show the "remove image" button when needed
				 if ( ! jQuery('#product_cat_thumbnail_id').val() )
					 jQuery('.remove_image_button').hide();

				// Uploading files
				var file_frame;

				jQuery(document).on( 'click', '.upload_image_button', function( event ){

					event.preventDefault();

					// If the media frame already exists, reopen it.
					if ( file_frame ) {
						file_frame.open();
						return;
					}

					// Create the media frame.
					file_frame = wp.media.frames.downloadable_file = wp.media({
						title: '<?php _e( 'Choose an image', 'woocommerce' ); ?>',
						button: {
							text: '<?php _e( 'Use image', 'woocommerce' ); ?>',
						},
						multiple: false
					});

					// When an image is selected, run a callback.
					file_frame.on( 'select', function() {
						attachment = file_frame.state().get('selection').first().toJSON();

						jQuery('#product_cat_thumbnail_id').val( attachment.id );
						jQuery('#product_cat_thumbnail img').attr('src', attachment.url );
						jQuery('.remove_image_button').show();
					});

					// Finally, open the modal.
					file_frame.open();
				});

				jQuery(document).on( 'click', '.remove_image_button', function( event ){
					jQuery('#product_cat_thumbnail img').attr('src', '<?php echo wc_placeholder_img_src(); ?>');
					jQuery('#product_cat_thumbnail_id').val('');
					jQuery('.remove_image_button').hide();
					return false;
				});

			</script>
			<div class="clear"></div>
		</div>
		<?php
	}

	/**
	 * Edit category thumbnail field.
	 *
	 * @access public
	 * @param mixed $term Term (category) being edited
	 * @param mixed $taxonomy Taxonomy of the term being edited
	 */
	public function edit_category_fields( $term, $taxonomy ) {

		$image 			= '';
		$thumbnail_id 	= absint( get_woocommerce_term_meta( $term->term_id, 'thumbnail_id', true ) );
		if ( $thumbnail_id )
			$image = wp_get_attachment_thumb_url( $thumbnail_id );
		else
			$image = wc_placeholder_img_src();
		?>
		<tr class="form-field">
			<th scope="row" valign="top"><label><?php _e( 'Thumbnail', 'woocommerce' ); ?></label></th>
			<td>
				<div id="product_cat_thumbnail" style="float:left;margin-right:10px;"><img src="<?php echo $image; ?>" width="60px" height="60px" /></div>
				<div style="line-height:60px;">
					<input type="hidden" id="product_cat_thumbnail_id" name="product_cat_thumbnail_id" value="<?php echo $thumbnail_id; ?>" />
					<button type="submit" class="upload_image_button button"><?php _e( 'Upload/Add image', 'woocommerce' ); ?></button>
					<button type="submit" class="remove_image_button button"><?php _e( 'Remove image', 'woocommerce' ); ?></button>
				</div>
				<script type="text/javascript">

					// Uploading files
					var file_frame;

					jQuery(document).on( 'click', '.upload_image_button', function( event ){

						event.preventDefault();

						// If the media frame already exists, reopen it.
						if ( file_frame ) {
							file_frame.open();
							return;
						}

						// Create the media frame.
						file_frame = wp.media.frames.downloadable_file = wp.media({
							title: '<?php _e( 'Choose an image', 'woocommerce' ); ?>',
							button: {
								text: '<?php _e( 'Use image', 'woocommerce' ); ?>',
							},
							multiple: false
						});

						// When an image is selected, run a callback.
						file_frame.on( 'select', function() {
							attachment = file_frame.state().get('selection').first().toJSON();

							jQuery('#product_cat_thumbnail_id').val( attachment.id );
							jQuery('#product_cat_thumbnail img').attr('src', attachment.url );
							jQuery('.remove_image_button').show();
						});

						// Finally, open the modal.
						file_frame.open();
					});

					jQuery(document).on( 'click', '.remove_image_button', function( event ){
						jQuery('#product_cat_thumbnail img').attr('src', '<?php echo wc_placeholder_img_src(); ?>');
						jQuery('#product_cat_thumbnail_id').val('');
						jQuery('.remove_image_button').hide();
						return false;
					});

				</script>
				<div class="clear"></div>
			</td>
		</tr>
		<?php
	}

	/**
	 * save_category_fields function.
	 *
	 * @access public
	 * @param mixed $term_id Term ID being saved
	 * @param mixed $tt_id
	 * @param mixed $taxonomy Taxonomy of the term being saved
	 * @return void
	 */
	public function save_category_fields( $term_id, $tt_id, $taxonomy ) {
		if ( isset( $_POST['product_cat_thumbnail_id'] ) ) {
			update_woocommerce_term_meta( $term_id, 'thumbnail_id', absint( $_POST['product_cat_thumbnail_id'] ) );
		}
	}

	/**
	 * Description for occasion page to aid users.
	 *
	 * @access public
	 * @return void
	 */
	public function occasion_description() {
		echo wpautop( __( 'Product categories for your store can be managed here. To change the order of categories on the front-end you can drag and drop to sort them. To see more categories listed click the "screen options" link at the top of the page.', 'woocommerce' ) );
	}

	/**
	 * Thumbnail column added to category admin.
	 *
	 * @access public
	 * @param mixed $columns
	 * @return array
	 */
	public function product_cat_columns( $columns ) {
		$new_columns          = array();
		$new_columns['cb']    = $columns['cb'];
		$new_columns['thumb'] = __( 'Image', 'woocommerce' );

		unset( $columns['cb'] );

		return array_merge( $new_columns, $columns );
	}

	/**
	 * Thumbnail column value added to category admin.
	 *
	 * @access public
	 * @param mixed $columns
	 * @param mixed $column
	 * @param mixed $id
	 * @return array
	 */
	public function product_cat_column( $columns, $column, $id ) {

		if ( $column == 'thumb' ) {

			$image 			= '';
			$thumbnail_id 	= get_woocommerce_term_meta( $id, 'thumbnail_id', true );

			if ($thumbnail_id)
				$image = wp_get_attachment_thumb_url( $thumbnail_id );
			else
				$image = wc_placeholder_img_src();

			// Prevent esc_url from breaking spaces in urls for image embeds
			// Ref: http://core.trac.wordpress.org/ticket/23605
			$image = str_replace( ' ', '%20', $image );

			$columns .= '<img src="' . esc_url( $image ) . '" alt="' . __( 'Thumbnail', 'woocommerce' ) . '" class="wp-post-image" height="48" width="48" />';

		}

		return $columns;
	}

	/**
	 * Maintain term hierarchy when editing a product.
	 * @param  array $args
	 * @return array
	 */
	public function disable_checked_ontop( $args ) {
		if ( 'occasion' == $args['taxonomy'] ) {
			$args['checked_ontop'] = false;
		}
		return $args;
	}
}

new BH_WC_Admin_Taxonomies();
