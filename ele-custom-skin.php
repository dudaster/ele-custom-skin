<?php
/*
 * Plugin Name: Ele Custom Skin
 * Version: 1.0.0
 * Description: Elementor Custom Skin for Posts and Posts Archive. You can create a skin as you want.
 * Plugin URI: https://www.eletemplator.com
 * Author: Liviu Duda
 * Author URI: https://www.leadpro.ro
 * Text Domain: elecustomskin
 * Domain Path: /languages
 * License: GPLv3
 * License URI: http://www.gnu.org/licenses/gpl-3.0
*/
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

define( 'MY_WIDGETS', plugin_dir_path( __FILE__ ));
add_action( 'elementor_pro/init', 'elementor_init' );
function elementor_init(){
		//load templates types
	
	//require_once MY_WIDGETS.'theme-builder/init.php';
	require_once MY_WIDGETS.'theme-builder/init.php';

}

add_action('elementor/widgets/widgets_registered','add_eleplug_widgets');
function add_eleplug_widgets(){
	require_once MY_WIDGETS.'skins/skin-custom.php';
}
