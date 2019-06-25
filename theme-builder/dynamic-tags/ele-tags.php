<?php

namespace ElementorPro\Modules\DynamicTags;

use Elementor\Modules\DynamicTags\Module as TagsModule;
use Elementor\Plugin;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

require_once('tags/post-summary.php');

class Eletags extends TagsModule {

	public function __construct() {
		parent::__construct();
	}

	public function get_name() {
		return 'eletags';
	}

	public function get_tag_classes_names() {
		return [
			'Post_Summary',
		];
	}
  
}
