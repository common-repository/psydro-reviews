<?php

defined( 'ABSPATH' ) or die( 'No script kiddies please!' );
if( !class_exists('PsydroForgetPassword') ) {
	class PsydroForgetPassword extends PsydroModal {

		public static $instance = null;

		public function __construct() {
			parent::__construct();
		}
		
		public static function getInstance() {

			if (!isset(PsydroForgetPassword::$instance)) {
				PsydroForgetPassword::$instance = new PsydroForgetPassword();
			}
			return PsydroForgetPassword::$instance;
		}

		public function render() {
			require_once(PSYDRO_REVIEWS_PLUGIN_DIR . '/templates/modal/forgot.php');
		}
	}
}
PsydroForgetPassword::getInstance();