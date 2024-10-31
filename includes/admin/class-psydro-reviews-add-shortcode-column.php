<?php

defined( 'ABSPATH' ) or die( 'No script kiddies please!' );
if( !class_exists('PsydroShortcodeColumns') ) {
	class PsydroShortcodeColumns {
		public static $instance = null;

		public function __construct() {

			add_filter( 'manage_psydro_shortcode_posts_columns', array( $this, 'set_custom_edit_psydro_shortcode_columns' ) );
			add_action( 'manage_psydro_shortcode_posts_custom_column' , array( $this, 'custom_psydro_shortcode_column' ), 10, 2 );
		}

		public static function getInstance() {
			if (!isset(PsydroShortcodeColumns::$instance)) {
				PsydroShortcodeColumns::$instance = new PsydroShortcodeColumns();
			}
			return PsydroShortcodeColumns::$instance;
		}

		public function set_custom_edit_psydro_shortcode_columns($columns) {
		    $columns['shortcode'] = __( 'Shortcode', 'psydro_shortcode' );

		    return $columns;
		}

		public function custom_psydro_shortcode_column( $column, $post_id ) {
		    switch ( $column ) {

		        case 'shortcode' :

		        echo '[psydro-review-slider' . ' id="' . $post_id . '"]';
		    }
		}
	}
}