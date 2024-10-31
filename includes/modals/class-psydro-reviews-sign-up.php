<?php

defined( 'ABSPATH' ) or die( 'No script kiddies please!' );
if( !class_exists('PsydroSignup') ) {
	class PsydroSignup extends PsydroModal {

		public static $instance = null;

		public function __construct() {
			parent::__construct();
		}
		
		public static function getInstance() {

			if (!isset(PsydroSignup::$instance)) {
				PsydroSignup::$instance = new PsydroSignup();
			}
			return PsydroSignup::$instance;
		}

		public function render() {
			require_once(PSYDRO_REVIEWS_PLUGIN_DIR . '/templates/modal/signup.php');
		}
	}
}
PsydroSignup::getInstance();