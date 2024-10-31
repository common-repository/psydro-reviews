<?php

defined( 'ABSPATH' ) or die( 'No script kiddies please!' );
if( !class_exists('PsydroWriteReview') ) {
	class PsydroWriteReview extends PsydroModal {

		public static $instance = null;

		public function __construct() {
			parent::__construct();
		}
		
		public static function getInstance() {

			if (!isset(PsydroWriteReview::$instance)) {
				PsydroWriteReview::$instance = new PsydroWriteReview();
			}
			return PsydroWriteReview::$instance;
		}

		public function render() {
			require_once(PSYDRO_REVIEWS_PLUGIN_DIR . '/templates/modal/write-review.php');
		}
	}
}
PsydroWriteReview::getInstance();