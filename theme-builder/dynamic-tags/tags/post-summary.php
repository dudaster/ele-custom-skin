<?php
namespace ElementorPro\Modules\DynamicTags\Tags;

use Elementor\Controls_Manager;
use Elementor\Core\DynamicTags\Tag;
use ElementorPro\Modules\DynamicTags\Module;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

class Post_Summary extends Tag {
	public function get_name() {
		return 'post-summary';
	}

	public function get_title() {
		return __( 'Post Summary', 'ele-custom-skin' );
	}

	public function get_group() {
		return Module::POST_GROUP;
	}

	public function get_categories() {
		return [ Module::TEXT_CATEGORY ];
	}
  
  protected function _register_controls() {
    $this->add_control(
      'length',
      [
         'label'   => __( 'Length', 'ele-custom-skin' ),
         'type'    => Controls_Manager::NUMBER,
         'default' => 25,
         'min'     => 0,
         'max'     => 1000,
         'step'    => 1,
      ]
    );
	}

	public function render() {
    add_filter( 'excerpt_more',function(){return '';}, 20 );
    add_filter( 'excerpt_length', function(){$settings = $this->get_settings(); return $settings['length'];}, 20 ); 
		echo get_the_excerpt();
	}
}
