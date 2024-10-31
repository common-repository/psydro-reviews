<?php

defined( 'ABSPATH' ) or die( 'No script kiddies please!' );
if( !class_exists('PsydroOtpVerification') ) {
	class PsydroOtpVerification extends PsydroModal {

		public static $instance = null;

		public function __construct() {
			parent::__construct();
		}
		
		public static function getInstance() {

			if (!isset(PsydroOtpVerification::$instance)) {
				PsydroOtpVerification::$instance = new PsydroOtpVerification();
			}
			return PsydroOtpVerification::$instance;
		}

		public function render() {
			require_once(PSYDRO_REVIEWS_PLUGIN_DIR . '/templates/modal/otp.php');
		}
	}
}
PsydroOtpVerification::getInstance();