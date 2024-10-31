<?php

defined( 'ABSPATH' ) or die( 'No script kiddies please!' );
if( !class_exists('PsydroSetting') ) {
	class PsydroSetting {

		public static $instance = null;
		private $config = [ 
			'psydro-review-bg-color' => '#ffffff',
			'average_rating_bg_color' => '#ffffff',
			'orientation' => 'horizontal',
			'title_font_color' => '	#000000',
			'review_font_color' => '#000000',
			'image_slider' => 'disable',
			'slider_config' => array(
				'speed' => 5000,
				'slidesToShow' => 3,
				'autoplaySpeed' => 5000,
				'autoplay' => true,
				'pauseOnHover' => true,
				'prevArrow' => '<button class="slick-prev" aria-label="Previous" type="button"></button>',
				'nextArrow' => '<button class="slick-next" aria-label="Next" type="button"></button>',
				'responsive' => array(
						array(
							'breakpoint' => '1024',
							'settings' => array(
								'slidesToShow' => 3,
						        'infinite' => true,
							)
						),
						array(
							'breakpoint' => '767',
							'settings' => array(
								'slidesToShow' => 2,
							)
						),
						array(
							'breakpoint' => '480',
							'settings' => array(
								'slidesToShow' => 1,
							)
						)
					)
				)
			];

		public static function getInstance() {

			if (!isset(PsydroSetting::$instance)) {
				PsydroSetting::$instance = new PsydroSetting();
			}
			return PsydroSetting::$instance;
		}

		public function get($key) {
			return trim( get_option('psydro_'.$key) );
		}

		public function set($key, $value) {
			return update_option('psydro_'.$key, sanitize_text_field($value));
		}

		public function isValid() {
			return empty( $this->get('api_key') ) ? false : true ;
		}

		public function is_valid() {
			return !$this->isValid() ? false : true;
		}

		public function get_config($key) {
			return $this->config[$key];
		}	

		public function getApiRes($key) {
			return trim( get_option('psydro_'.$key) );
		}


		public function setResponseError($key, $value) {
			return update_option('psydro_'.$key, sanitize_text_field($value));
		}

		public function isError() {
			return get_option('psydro_api_key_res_error');
		}
		
		public function setResponseSuccess($key, $value) {
			return update_option('psydro_'.$key, sanitize_text_field($value));
		}

		public function isSuccess() {
			return get_option('psydro_api_key_res_success');
		}
	}
}