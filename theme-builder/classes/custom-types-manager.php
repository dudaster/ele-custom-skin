<?php
namespace ElementorPro\Modules\ThemeBuilder\Classes;

use Elementor\TemplateLibrary\Source_Local;
use ElementorPro\Modules\ThemeBuilder\Documents;
use ElementorPro\Modules\ThemeBuilder\Module;
use ElementorPro\Plugin;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

class Custom_Types_Manager extends Templates_Types_Manager {
	
  public function register_documents() {
		$this->docs_types = [
			'loop' => Documents\Loop::get_class_full_name(),
		];

		foreach ( $this->docs_types as $type => $class_name ) {
			Plugin::elementor()->documents->register_document_type( $type, $class_name );
			Source_Local::add_template_type( $type );
		}
	}
}

new Custom_Types_Manager();
