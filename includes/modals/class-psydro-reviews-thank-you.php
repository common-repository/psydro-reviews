<?php

defined( 'ABSPATH' ) or die( 'No script kiddies please!' );
if( !class_exists('PsydroThankYou') ) {
	class PsydroThankYou extends PsydroModal {

		public static $instance = null;

		public function __construct() {
			parent::__construct();
		}

		public static function getInstance() {

			if (!isset(PsydroThankYou::$instance)) {
				PsydroThankYou::$instance = new PsydroThankYou();
			}
			return PsydroThankYou::$instance;
		}

		public function render() {
			require_once(PSYDRO_REVIEWS_PLUGIN_DIR . '/templates/modal/thank-you.php');
		}
	}
}
$PsydroThankYou = new PsydroThankYou();