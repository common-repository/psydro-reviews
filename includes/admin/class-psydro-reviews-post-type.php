<?php

defined( 'ABSPATH' ) or die( 'No script kiddies please!' );
if( !class_exists('PsydroPostType') ) {
	class PsydroPostType {

		public static $instance = null;

		public function __construct() {

			add_action( 'init', array( $this, 'psydro_shortcode_create_post_type' ) );

			add_filter( 'enter_title_here', array( $this, 'psydro_change_default_title_placeholder' ) );
		}

		public static function getInstance() {
			if (!isset(PsydroPostType::$instance)) {
				PsydroPostType::$instance = new PsydroPostType();
			}
			return PsydroPostType::$instance;
		}
		
		public function psydro_shortcode_create_post_type() {
		  register_post_type( 'psydro_shortcode',
		    array(
		      'labels' => array(
		        'name' => __( 'My Psydro Sliders', 'Psydro Reviews' ),
		        'singular_name' => __( 'psydro shortcode', 'Psydro Reviews' ),
		        'add_new'           => __( 'Add New Slider', 'Psydro Reviews' ),
	            'add_new_item'      => __( 'Psydro Slider - Add New', 'Psydro Reviews' ),
	            'edit_item'         => __( 'Edit Shortcode', 'Psydro Reviews' ),
	            'search_items' 		=> __( 'Search Shortcode', 'Psydro Reviews' ),
		      ),
		      'show_in_menu' => false,
		      'supports' => array('title'),
		      'public' => false,  
				'publicly_queryable' => true,  
				'show_ui' => true,  
				'exclude_from_search' => true,  
				'show_in_nav_menus' => false,  
				'has_archive' => false,  
				'rewrite' => true,  
		    )
		  );
		}
		function psydro_change_default_title_placeholder( $title ){
		    $screen = get_current_screen();
		    if ( 'psydro_shortcode' == $screen->post_type ){
		        $title = 'Enter Slider\'s Unique Name';
		    }
		    return $title;
		}
	}
}