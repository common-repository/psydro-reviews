<?php

defined( 'ABSPATH' ) or die( 'No script kiddies please!' );
if( !class_exists('PsydroAjax') ) {
	class PsydroAjax {

		public static $instance = null;

		public function __construct() {

			add_action( 'wp_ajax_psdyro_save_api_key', array( $this, 'psydro_review_save_key' ) );
		}

		public static function getInstance() {

			if (!isset(PsydroAjax::$instance)) {
				PsydroAjax::$instance = new PsydroAjax();
			}
			return PsydroAjax::$instance;
		}
		

		public function psydro_review_save_key() {
			if(is_admin()) {
				
				$key = sanitize_text_field( $_POST['key'] );

				$response = json_decode(wp_remote_retrieve_body( wp_remote_get( PSYDRO_REVIEWS_API_BASE_URL.'extentionReviews?key='.$key ) ), true);

				psydro()->setting->setResponseError('api_key_res_error', $response['error'] );	
				psydro()->setting->setResponseSuccess('api_key_res_success', $response['success'] );	
				psydro()->setting->set( 'api_key', $key );

				wp_send_json( [ 
					'status' => true, 
	   				'error' => $response['error'], 
	   				'message' => $response['message']
	   			]);
			}
		}	
	}
}