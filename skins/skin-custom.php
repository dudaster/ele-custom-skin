<?php
namespace ElementorPro\Modules\Posts\Skins;

use Elementor\Controls_Manager;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Image_Size;
use Elementor\Group_Control_Typography;
use Elementor\Scheme_Color;
use Elementor\Scheme_Typography;
use Elementor\Widget_Base;
use ElementorPro\Plugin;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class Skin_Posts_ECS extends Skin_Base {

	private $template_cache=[];
	private $pid;


	
	public function get_id() {
		return 'custom';
	}

	public function get_title() {
		return __( 'Custom', 'ele-custom-skin' );
	}

	protected function _register_controls_actions() {
		add_action( 'elementor/element/archive-posts/section_layout/before_section_end', [ $this, 'register_controls' ] );
		add_action( 'elementor/element/archive-posts/section_query/after_section_end', [ $this, 'register_style_sections' ] );
		
		add_action( 'elementor/element/posts/section_layout/before_section_end', [ $this, 'register_controls' ] );
		add_action( 'elementor/element/posts/section_query/after_section_end', [ $this, 'register_style_sections' ] );
	
	}	
	
	public function register_controls( Widget_Base $widget ) {

		$this->parent = $widget;


		$this->add_control(
			'skin_template',
			[
				'label' => __( 'Select a template', 'ele-custom-skin' ),
				'type' => Controls_Manager::SELECT2,
				'label_block' => true,
				'default' => [],
				'options' => $this->get_skin_template(),
			]
		);

		$this->add_control(//this would make use of 100% if width
			'view',
			[
				'label' => __( 'View', 'ele-custom-skin' ),
				'type' => \Elementor\Controls_Manager::HIDDEN,
				'default' => 'top',
				'prefix_class' => 'elementor-posts--thumbnail-',
			]
		);

		parent::register_controls($widget);

		$this->remove_control( 'img_border_radius' );
		$this->remove_control( 'meta_data' );
		$this->remove_control( 'item_ratio' );
		$this->remove_control( 'image_width' );
		$this->remove_control( 'show_title' );
		$this->remove_control( 'title_tag' );
		$this->remove_control( 'masonry' );
		$this->remove_control( 'thumbnail' );
		$this->remove_control( 'thumbnail_size' );
		$this->remove_control( 'show_read_more' );
		$this->remove_control( 'read_more_text' );
		$this->remove_control( 'show_excerpt' );
		$this->remove_control( 'excerpt_length' );

	
	}

	private function get_post_id(){
		return $this->pid;
	}
	private function get_skin_template(){
				global $wpdb;
				$templates = $wpdb->get_results( 
					"SELECT $wpdb->term_relationships.object_id as ID, $wpdb->posts.post_title as post_title FROM $wpdb->term_relationships
						INNER JOIN $wpdb->term_taxonomy ON
							$wpdb->term_relationships.term_taxonomy_id=$wpdb->term_taxonomy.term_taxonomy_id
						INNER JOIN $wpdb->terms ON 
							$wpdb->term_taxonomy.term_id=$wpdb->terms.term_id AND $wpdb->terms.slug='loop'
						INNER JOIN $wpdb->posts ON
							$wpdb->term_relationships.object_id=$wpdb->posts.ID"
				);
				$options = [ '' => '' ];
				foreach ( $templates as $template ) {
					$options[ $template->ID ] = $template->post_title;
				}
				return $options;
	}


	public function render_amp() {

	}

	protected function set_template($skin){// this is for terms we don't need passid so we can actually add them in cache
		
		if (!$skin) return;
		if (isset($this->template_cache[$skin])) return $this->template_cache[$skin];

		$return = \Elementor\Plugin::instance()->frontend->get_builder_content_for_display( $skin );
		$this->template_cache[$skin] = $return;

	}

	protected function get_template(){
    global $ecs_render_loop;
    $ecs_render_loop=true;
		$settings = $this->parent->get_settings();
		$this->pid=get_the_ID();//set the current id in private var usefull to passid 
		if (!$this->get_instance_value( 'skin_template' )) return;
		$return = \Elementor\Plugin::instance()->frontend->get_builder_content_for_display( $this->get_instance_value( 'skin_template' ) );
    $ecs_render_loop=false;
		return $return;
	}

	protected function render_post_header() {
		?>
		<article id="post-<?php the_ID(); ?>" <?php post_class( [ 'elementor-post elementor-grid-item' ] ); ?>>
		<?php
	}
	protected function render_post() {
		$this->render_post_header();
		if ($this->get_instance_value( 'skin_template' )){
      if (function_exists("parse_content")) {
        global $post;
        echo parse_content($this->get_template(),$post);
      }
        else echo $this->get_template(); 
    }

			else  _e( "Select a Loop template! If you don't have one go to Elementor &gt; My Templates.", 'ele-custom-skin');


		$this->render_post_footer();

	}


}


// it seems the same skin brakes if set to 2 widgets in the same time

class Skin_Archive_ECS extends Skin_Posts_ECS {

	private $template_cache=[];
	private $pid;


	
	public function get_id() {
		return 'archive_custom';
	}

	public function get_title() {
		return __( 'Custom', 'ele-custom-skin' );
	}
}

// Add a custom skin for the POSTS widget
    add_action( 'elementor/widget/posts/skins_init', function( $widget ) {
       $widget->add_skin( new Skin_Posts_ECS( $widget ) );
    } );
// Add a custom skin for the POST Archive widget
    add_action( 'elementor/widget/archive-posts/skins_init', function( $widget ) {
       $widget->add_skin( new Skin_Archive_ECS( $widget ) );
    } );
    