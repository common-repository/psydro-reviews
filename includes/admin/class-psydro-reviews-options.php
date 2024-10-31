<?php

defined( 'ABSPATH' ) or die( 'No script kiddies please!' );
if( !class_exists('PsydroOptions') ) {
	class PsydroOptions {

		public static $instance = null;

		public static function getInstance() {
			if (!isset(PsydroOptions::$instance)) {
				PsydroOptions::$instance = new PsydroOptions();
			}
			return PsydroOptions::$instance;
		}

		function render_options() {
		    require_once( PSYDRO_REVIEWS_PLUGIN_DIR. '/templates/admin/options.php' );
		}

		public function get_setting($key = '') {
			echo $key;
		}	 
	}
}