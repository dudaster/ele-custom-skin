<?php

require_once MY_WIDGETS.'theme-builder/documents/loop.php';
require_once MY_WIDGETS.'theme-builder/dynamic-tags/ele-tags.php';
//add new tags
$newtags=new ElementorPro\Modules\DynamicTags\Eletags();
$newtags::instance();
//require_once MY_WIDGETS.'theme-builder/classes/custom-types-manager.php';

use Elementor\TemplateLibrary\Source_Local;
use ElementorPro\Modules\ThemeBuilder\Documents\Loop;
use ElementorPro\Plugin;
use ElementorPro\Modules\ThemeBuilder\Documents\Theme_Document;

Plugin::elementor()->documents->register_document_type( 'loop', Loop::get_class_full_name() );
Source_Local::add_template_type( 'loop' );

function my_get_document( $post_id ) {
		$document = null;

		try {
			$document = Plugin::elementor()->documents->get( $post_id );
		} catch ( \Exception $e ) {}

		if ( ! empty( $document ) && ! $document instanceof Theme_Document ) {
			$document = null;
		}

		return $document;
	}

function add_more_types($settings){
  $post_id = get_the_ID();
  $document = my_get_document( $post_id );

	if ( ! $document ) {
		return $settings;
	}
  
  $new_types=['loop'=>Loop::get_properties()];
  $add_settings=['theme_builder' => ['types' =>$new_types]];
  $settings = array_merge_recursive($settings, $add_settings);
  return $settings;
}

add_filter( 'elementor_pro/editor/localize_settings', 'add_more_types' );
