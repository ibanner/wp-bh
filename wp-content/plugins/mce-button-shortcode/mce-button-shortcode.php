<?php
/*
Plugin Name: MCE Button Shortcode
Plugin URI:
Description: A plugin to add button shortcode to Wordpress editor
Version: 1.0
Author: Nir Goldberg
Author URI: http://www.htmline.com
*/
class mce_button_shortcode {
	/**
	 * $shortcode_tag 
	 * holds the name of the shortcode tag
	 * @var string
	 */
	public $shortcode_tag = 'btn';
	
	/**
	 * __construct 
	 * class constructor will set the needed filter and action hooks
	 * @param array $args
	 */
	function __construct($args = array()) {
		// add shortcode
		add_shortcode( $this->shortcode_tag, array($this, 'shortcode_handler') );
		
		if ( is_admin() ) {
			add_action( 'admin_head', array($this, 'admin_head') );
			add_action( 'admin_enqueue_scripts', array( $this, 'admin_enqueue_scripts') );
		}
	}
	
	/**
	 * shortcode_handler
	 * @param  array  $atts shortcode attributes
	 * @param  string $content shortcode content
	 * @return string
	 */
	function shortcode_handler($atts, $content = null) {
		// attributes
		extract( shortcode_atts(
			array(
				'size'		=> 'big',
				'color'		=> 'default',
				'text'		=> '',
				'link'		=> 'http://',
				'target'	=> 'self'
			), $atts)
		);
		
		$output			= '';
		
		// validate text
		if (!$text)
			return $output;
		
		// validate button size, if not valid revert to default
		$size_list		= array('big', 'small');
		$size			= in_array($size, $size_list) ? $size : 'big';
		
		// validate button color, if not valid revert to default
		$color_list		= array('default', 'red', 'cyan', 'white', 'green');
		$color			= in_array($color, $color_list) ? $color : 'default';
		
		// validate link target, if not valid revert to default
		$target_list	= array('self', 'blank');
		$target			= in_array($target, $target_list) ? $target : 'self';
		
		// button markup
		$output .= '<p><a class="btn inline-btn ' . $color . '-btn ' . $size . '" href="' . $link . '" target="_' . $target . '">' . $text . '</a></p>';
		
		//return shortcode output
		return $output;
	}
	
	/**
	 * admin_head
	 * calls your functions into the correct filters
	 * @return void
	 */
	function admin_head() {
		// check user permissions
		if ( !current_user_can('edit_posts') && !current_user_can('edit_pages') ) {
			return;
		}
		
		// check if WYSIWYG is enabled
		if ( 'true' == get_user_option('rich_editing') ) {
			add_filter( 'mce_external_plugins', array($this ,'mce_external_plugins') );
			add_filter( 'mce_buttons', array($this, 'mce_buttons') );
		}
	}
	
	/**
	 * mce_external_plugins 
	 * Adds our tinymce plugin
	 * @param  array $plugin_array 
	 * @return array
	 */
	function mce_external_plugins($plugin_array) {
		$plugin_array[$this->shortcode_tag] = plugins_url('js/mce-button.js', __FILE__ );
		return $plugin_array;
	}
	
	/**
	 * mce_buttons 
	 * Adds our tinymce button
	 * @param  array $buttons 
	 * @return array
	 */
	function mce_buttons($buttons) {
		array_push($buttons, $this->shortcode_tag);
		return $buttons;
	}
	
	/**
	 * admin_enqueue_scripts 
	 * Used to enqueue custom styles
	 * @return void
	 */
	function admin_enqueue_scripts() {
		wp_enqueue_style( 'mce-button-shortcode', plugins_url('css/mce-button.css' , __FILE__) );
	}
}//end class

new mce_button_shortcode();