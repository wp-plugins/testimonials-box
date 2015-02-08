<?php

/*
*Instruction - if you are using this as a template for a plugin, change the class name, the call to create an object from this class *at the bottom, and modify the private variables to meet your needs.
*/

class TestimonialsBoxCustomPostType{

private $post_type = 'testimonialsbox';
private $post_label = 'Testimonials Box';
private $prefix = '_testimonials-box_';
function __construct() {
	
	add_filter( 'cmb_meta_boxes', array(&$this,'metaboxes' ));
	add_action( 'init', array(&$this,'initialize_meta_boxes'), 9999 );
	add_action("init", array(&$this,"create_post_type"));
	add_action( 'init', array(&$this, 'testimonials_box_register_shortcodes'));
	add_action( 'wp_footer', array(&$this, 'enqueue_styles'));
	register_activation_hook( __FILE__, array(&$this,'activate' ));
}

function create_post_type(){
	register_post_type($this->post_type, array(
	         'label' => _x($this->post_label, $this->post_type.' label'), 
	         'singular_label' => _x('All '.$this->post_label, $this->post_type.' singular label'), 
	         'public' => true, // These will be public
	         'show_ui' => true, // Show the UI in admin panel
	         '_builtin' => false, // This is a custom post type, not a built in post type
	         '_edit_link' => 'post.php?post=%d',
	         'capability_type' => 'page',
	         'hierarchical' => false,
	         'rewrite' => array("slug" => $this->post_type), // This is for the permalinks
	         'query_var' => $this->post_type, // This goes to the WP_Query schema
	         //'supports' =>array('title', 'editor', 'custom-fields', 'revisions', 'excerpt'),
	         'supports' =>array('title', 'author'),
	         'add_new' => _x('Add New', 'Event')
	         ));
}


/**
 * Define the metabox and field configurations.
 *
 * @param  array $meta_boxes
 * @return array
 */
function metaboxes( array $meta_boxes ) {
	
	// Start with an underscore to hide fields from custom fields list
	//$prefix = '_testimonials-box_';
	

	$meta_boxes[] = array(
		'id'         => 'adsense_metabox',
		'title'      => 'Ad',
		'pages'      => array( $this->post_type ), // Post type
		'context'    => 'normal',
		'priority'   => 'high',
		'show_names' => true, // Show field names on the left
		'fields'     => array(
			array(
				'name' => 'Testimonial Title',
				'desc' => 'Enter your Testimonial title here.',
				'id'   => $this->prefix . 'title',
				'type' => 'text',
				//'std'  => 'This is the headline, man!',
			),
			array(
			            'name' => 'Background Color',
			            'desc' => 'Use this to set the backgroud of the listing.',
			            'id'   => $this->prefix . 'background_color',
			            'type' => 'colorpicker',
					'std'  => '#ffffff'
		        ),
		        array(
				'name' => 'Image',
				'desc' => 'Upload an image or enter an URL.',
				'id'   => $this->prefix . 'testimonial_image',
				'type' => 'file',
			),
			array(
				'name' => 'Name',
				'desc' => 'Enter the name of the person who is giving the testimonial.',
				'id'   => $this->prefix . 'name',
				'type' => 'text',
				'std'  => '',
			),
			array(
				'name'    => 'Testimonial',
				//'desc'    => 'field description (optional)',
				'id'      => $this->prefix . 'testimonial',
				'type'    => 'wysiwyg',
				'options' => array(	'textarea_rows' => 20, 'wpautop' => true ),
				
			),

		),
	);

	

	// Add other metaboxes as needed

	return $meta_boxes;
}


function testimonials_box_shortcode($atts){
		extract( shortcode_atts( array(
			'id' => '',
		), $atts ) );
		//$meta_data = get_post_meta( $id, $this->prefix . 'adsense_code', true );
		//$meta_data = get_post_meta($id);
		$dir = plugin_dir_path( __FILE__ );

		$title = get_post_meta($id, $this->prefix . 'title', true);
		$background_color = get_post_meta($id, $this->prefix . 'background_color', true);
		$testimonial_image = get_post_meta($id, $this->prefix . 'testimonial_image', true);
		$name = get_post_meta($id, $this->prefix . 'name', true);
		$testimonial = get_post_meta($id, $this->prefix . 'testimonial', true);
		
		ob_start();
		include $dir.'template/testimonialsBoxTemplate.php';
		return ob_get_clean();
}



function testimonials_box_register_shortcodes(){
		add_shortcode( 'testimonials_box', array(&$this,'testimonials_box_shortcode' ));
	}


function activate() {
	// register taxonomies/post types here
	$this->create_post_type();
	global $wp_rewrite;
	$wp_rewrite->flush_rules();
}

function enqueue_styles(){
	wp_register_style( 'testimonials-box-css', plugin_dir_url(__FILE__).'css/testimonialsBox.css' );
	wp_enqueue_style('testimonials-box-css');
}


/*
 * Initialize the metabox class.
 */
 
function initialize_meta_boxes() {

	if ( ! class_exists( 'cmb_Meta_Box' ) )
		require_once 'lib/metabox/init.php';

}


}

new TestimonialsBoxCustomPostType();


?>