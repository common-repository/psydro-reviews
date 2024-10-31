<?php

defined( 'ABSPATH' ) or die( 'No script kiddies please!' );
if( !class_exists('PsydroReviewReadMore') ) {
	class PsydroReviewReadMore extends PsydroModal {

		public static $instance = null;

		public function __construct() {
			parent::__construct();
		}
		
		public static function getInstance() {

			if (!isset(PsydroReviewReadMore::$instance)) {
				PsydroReviewReadMore::$instance = new PsydroReviewReadMore();
			}
			return PsydroReviewReadMore::$instance;
		}

		public function render() {
			require_once(PSYDRO_REVIEWS_PLUGIN_DIR . '/templates/modal/review-read-more.php');
		}
	}
}
PsydroReviewReadMore::getInstance();