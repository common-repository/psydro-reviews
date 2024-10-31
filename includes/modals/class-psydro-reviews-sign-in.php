<?php

defined( 'ABSPATH' ) or die( 'No script kiddies please!' );
if( !class_exists('PsydroSignin') ) {
	class PsydroSignin extends PsydroModal {

		public static $instance = null;

		public function __construct() {
			parent::__construct();
		}
		
		public static function getInstance() {

			if (!isset(PsydroSignin::$instance)) {
				PsydroSignin::$instance = new PsydroSignin();
			}
			return PsydroSignin::$instance;
		}

		public function render() {
			require_once(PSYDRO_REVIEWS_PLUGIN_DIR . '/templates/modal/signin.php');
		}
	}
}
PsydroSignin::getInstance();