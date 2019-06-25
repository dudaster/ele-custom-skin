<?php
/*
 * Plugin Name: Ele Custom Skin
 * Version: 1.1.0
 * Description: Elementor Custom Skin for Posts and Archive Posts. You can create a skin as you want.
 * Plugin URI: https://www.eletemplator.com
 * Author: Liviu Duda
 * Author URI: https://www.leadpro.ro
 * Text Domain: ele-custom-skin
 * Domain Path: /languages
 * License: GPLv3
 * License URI: http://www.gnu.org/licenses/gpl-3.0
*/


if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

define( 'ELECS_DIR', plugin_dir_path( __FILE__ ));
add_action( 'elementor_pro/init', 'elecs_elementor_init' );
function elecs_elementor_init(){
		//load templates types
	
	//require_once ELECS_DIR.'theme-builder/init.php';
	require_once ELECS_DIR.'theme-builder/init.php';

}

add_action('elementor/widgets/widgets_registered','elecs_add_skins');
function elecs_add_skins(){
	require_once ELECS_DIR.'skins/skin-custom.php';
}

// dynamic background fix
function ECS_set_bg_element( \Elementor\Element_Base $element ) {
  global $ecs_render_loop;
  if(!$ecs_render_loop)
    return; // only act inside loop
  $controls = $element->get_controls();//get_settings( '__dynamic__' );//get_active_settings();
  $values = isset($values) ? $values : "";
  $settings = $element->parse_dynamic_settings( $values, $controls);
  //print_r($settings);


  $bg = isset($settings["background_image"]["url"]) ? $settings["background_image"]["url"] : (isset($settings["_background_image"]["url"]) ? $settings["_background_image"]["url"] : "");
  $bgh= isset($settings["background_hover_image"]["url"]) ? $settings["background_hover_image"]["url"] : (isset($settings["_background_hover_image"]["url"]) ? $settings["_background_hover_image"]["url"] : "");
  
  $style=$bg ? "background-image:url($bg);" : "";
  $onmouseover=$bgh ? "this.style.backgroundImage='url($bgh)';" :"";
  $onmouseout=$bg ? "this.style.backgroundImage='url($bg)';" :"";
  
  if($element->get_name()=='section') $wrapper="_wrapper"; 
      else 
        $wrapper="_inner_wrapper"; 
  
  if ($bg || $bgh) 
     $element->add_render_attribute( $wrapper, [
            'style'       => $style ,
            'onmouseover' => $onmouseover,
            'onmouseout'  => $onmouseout,
      ]);
  
 }

add_action( 'elementor/frontend/section/before_render', 'ECS_set_bg_element' );
add_action( 'elementor/frontend/column/before_render', 'ECS_set_bg_element' );