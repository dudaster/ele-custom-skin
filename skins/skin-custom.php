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

class Skin_Custom extends Skin_Base {

	private $template_cache=[];
	private $pid;


	
	public function get_id() {
		return 'custom';
	}

	public function get_title() {
		return __( 'Custom', 'elementor-pro' );
	}

	protected function _register_controls_actions() {
		add_action( 'elementor/element/posts-archive/section_layout/before_section_end', [ $this, 'register_controls' ] );
		add_action( 'elementor/element/posts-archive/section_query/after_section_end', [ $this, 'register_style_sections' ] );
		
		add_action( 'elementor/element/posts/section_layout/before_section_end', [ $this, 'register_controls' ] );
		add_action( 'elementor/element/posts/section_query/after_section_end', [ $this, 'register_style_sections' ] );
	
	}	
	
	public function register_controls( Widget_Base $widget ) {

		$this->parent = $widget;


		$this->add_control(
			'skin_template',
			[
				'label' => __( 'Select a template', 'elecustomskin' ),
				'type' => Controls_Manager::SELECT2,
				'label_block' => true,
				'default' => [],
				'options' => $this->get_skin_template(),
			]
		);
		parent::register_controls($widget);

		$this->remove_control( 'img_border_radius' );
		$this->remove_control( 'meta_data' );
		$this->remove_control( 'item_ratio' );
		$this->remove_control( 'image_width' );
		$this->remove_control( 'show_title' );
		$this->remove_control( 'title_tag' );

	
	}

	private function get_post_id(){
		return $this->pid;
	}
	private function get_skin_template(){
				global $wpdb;
				$templates = $wpdb->get_results( 
					"SELECT $wpdb->term_relationships.object_id as ID, $wpdb->posts.post_title as post_title FROM $wpdb->term_relationships 
					INNER JOIN $wpdb->terms ON 
						$wpdb->term_relationships.term_taxonomy_id=$wpdb->terms.term_id AND $wpdb->terms.slug='loop'
					INNER JOIN $wpdb->posts ON
						$wpdb->term_relationships.object_id=$wpdb->posts.ID"
				);
				$options = [ '' => '' ];
				foreach ( $templates as $template ) {
					$options[ $template->ID ] = $template->post_title;
					$this->set_template($template->ID);//this is for termlisting we cache the templates
				}
				return $options;
	}


	protected function get_skin_template_sterge() {
			$this->is_in_templates();
			$menus = get_terms( array(
					    'taxonomy' => 'nav_menu',
					    'hide_empty' => false,
					));

			$options = [ '' => '' ];

			foreach ( $menus as $menu ) {
				$options[ $menu->slug ] = $menu->name;

			}

			return $options;
	}



	public function render_amp() {

	}

	protected function set_template($skin){// this is for terms we don't need passid so we can actually add them in cache
		//$this->pid=get_the_ID();//set the current id in private var usefull to passid 
		if (!$skin) return;
		if ($this->template_cache[$skin]) return $this->template_cache[$skin];
//		add_action( 'elementor/frontend/widget/before_render', array( $this, 'passid' ) , 10 , 1); // pass the curent id to the widgets;

		$return = \Elementor\Plugin::instance()->frontend->get_builder_content_for_display( $skin );
		$this->template_cache[$skin] = $return;

	}

	protected function get_template(){
		$settings = $this->parent->get_settings();
		$this->pid=get_the_ID();//set the current id in private var usefull to passid 
		if (!$this->get_instance_value( 'skin_template' )) return;
		//term listing stuff
		/*if($settings['eleplug_eloop_term']=="yes" && $settings['taxonomy']){ // not to mess up with the terms fang shui we choose to get the cache template
			if ($this->template_cache[$this->get_instance_value( 'skin_template' )]) return $this->template_cache[$this->get_instance_value( 'skin_template' )];
		}*/

		$return = \Elementor\Plugin::instance()->frontend->get_builder_content_for_display( $this->get_instance_value( 'skin_template' ) );
		//$this->template_cache[$this->get_instance_value( 'skin_template' )] = $return;
		return $return;
	}

	protected function render_post_header() {
		?>
		<article id="post-<?php the_ID(); ?>" <?php post_class( [ 'elementor-post elementor-grid-item' ] ); ?>>
		<?php
	}
	protected function render_post() {
		$this->render_post_header();
		if ($this->get_instance_value( 'skin_template' )) 	echo $this->get_template();

			else echo "Select a Loop template! If you don't have one go to Elementor &gt; My Templates.";


		$this->render_post_footer();

	}


}

// Add a custom skin for the POST Archive widget
add_action( 'elementor/widget/posts-archive/skins_init', function( $widget ) {
   $widget->add_skin( new Skin_Custom( $widget ) );
} );
// Add a custom skin for the POSTS widget
add_action( 'elementor/widget/posts/skins_init', function( $widget ) {
   $widget->add_skin( new Skin_Custom( $widget ) );
} );
