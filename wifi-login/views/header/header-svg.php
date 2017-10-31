<?php
/**
 * The template for displaying the SVG sprite
 *
 * @author		Beit Hatfutsot
 * @package		wifi-login/views/header
 * @since		1.0.0
 * @version		1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/**
 * Variables
 */
$svg_sprite = TEMPLATE . '/images/general/svg-defs.svg';

/**
 * Display SVG sprite
 */
include_once( $svg_sprite );