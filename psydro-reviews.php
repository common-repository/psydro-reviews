<?php 

defined( 'ABSPATH' ) or die( 'No script kiddies please!' );

/**
Plugin Name: Psydro Reviews
Plugin URI: https://wordpress.org/plugins/psydro-reviews/
Description: Psydro Reviews – is used to give reviews.
Version: 0.0.6
Author: Psydro
Author URI: https://www.psydro.com
Text Domain: psydro-reviews
License: GPLv2 or later
License URI:  https://www.gnu.org/licenses/gpl-2.0.html
**/
if( !class_exists('Psydro') ) {
	class Psydro {

		private $version = '0.0.6';

		private static $instance = null;

		public function __construct() {

			define( 'PSYDRO_REVIEWS_VERSION',  $this->version );
			define( 'PSYDRO_REVIEWS_PLUGIN_DIR', untrailingslashit( plugin_dir_path( __FILE__ ) ) );
			define( 'PSYDRO_REVIEWS_PLUGIN_URL', untrailingslashit( plugins_url( basename( plugin_dir_path( __FILE__ ) ), basename( __FILE__ ) ) ) );
			define( 'PSYDRO_REVIEWS_API_BASE_URL',  'https://psydro.com/api/' );

			$this->init_required_files();
			
			if ( is_admin() ) {
				include_once( PSYDRO_REVIEWS_PLUGIN_DIR . '/includes/admin/class-psydro-reviews-admin.php' );
				$this->admin = PsydroAdmin::getInstance();
			}

			$this->ajax =  PsydroAjax::getInstance();
			$this->setting =  PsydroSetting::getInstance();

			if($this->setting->isValid()) :
				$this->init_required_modal_files();
				add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_scripts' ) );
			endif;
		}

		public static function instance() {
			
			if (!isset(Psydro::$instance)) {
	            Psydro::$instance = new Psydro();
	        }

	        return Psydro::$instance;
		}

		public function init_required_files() {

			require_once( PSYDRO_REVIEWS_PLUGIN_DIR . '/includes/class-psydro-reviews-ajax.php' );
			require_once( PSYDRO_REVIEWS_PLUGIN_DIR . '/functions.php' );
			require_once( PSYDRO_REVIEWS_PLUGIN_DIR . '/includes/class-psydro-reviews-setting.php' );
			require_once( PSYDRO_REVIEWS_PLUGIN_DIR. '/includes/class-psydro-reviews-shortcode.php' );
		}

		private function init_required_modal_files() {
			
			require_once( PSYDRO_REVIEWS_PLUGIN_DIR . '/includes/modals/abstract-class-psydro-reviews-modal.php' );
			require_once( PSYDRO_REVIEWS_PLUGIN_DIR . '/includes/modals/class-psydro-reviews-forgot-password.php' );
			require_once( PSYDRO_REVIEWS_PLUGIN_DIR . '/includes/modals/class-psydro-reviews-otp.php' );
			require_once( PSYDRO_REVIEWS_PLUGIN_DIR . '/includes/modals/class-psydro-reviews-sign-in.php' );
			require_once( PSYDRO_REVIEWS_PLUGIN_DIR . '/includes/modals/class-psydro-reviews-sign-up.php' );
			require_once( PSYDRO_REVIEWS_PLUGIN_DIR . '/includes/modals/class-psydro-reviews-thank-you.php' );
			require_once( PSYDRO_REVIEWS_PLUGIN_DIR . '/includes/modals/class-psydro-reviews-write-review.php' );
			require_once( PSYDRO_REVIEWS_PLUGIN_DIR . '/includes/modals/class-psydro-review-read-more.php' );
		}

		public function enqueue_scripts() {
		  	wp_enqueue_style( 'psydro-frontend', PSYDRO_REVIEWS_PLUGIN_URL . '/assets/css/psydro-frontend.css','', PSYDRO_REVIEWS_VERSION);

			wp_enqueue_script('jquery');

			wp_enqueue_script( 'jquery-validate', PSYDRO_REVIEWS_PLUGIN_URL . '/assets/js/cdn/psydrojquery-validate.min.js' , array('jquery'), PSYDRO_REVIEWS_VERSION, true );

			wp_enqueue_script( 'image-compressor', PSYDRO_REVIEWS_PLUGIN_URL . '/assets/js/JIC.min.js'  );

			wp_enqueue_script( 'psydro-vendors', PSYDRO_REVIEWS_PLUGIN_URL . '/assets/js/psydro-vendors.min.js' , array( 'jquery', 'jquery-validate' ), PSYDRO_REVIEWS_VERSION, true );

			wp_enqueue_script( 'image-crop', PSYDRO_REVIEWS_PLUGIN_URL . '/assets/js/psydro-review-image-uploader.min.js' , array( 'psydro-vendors' ), PSYDRO_REVIEWS_VERSION, true   );
				
			wp_enqueue_script( 'psydro-frontend', PSYDRO_REVIEWS_PLUGIN_URL . '/assets/js/psydro-frontend.min.js' , array( 'psydro-vendors' ), PSYDRO_REVIEWS_VERSION, true );

			wp_localize_script( 'psydro-frontend', 'psydro_env',  array(
					'base_url' => PSYDRO_REVIEWS_API_BASE_URL, 
					'api_key' =>  $this->setting->is_valid() ?   $this->setting->get('api_key') : ''
				));

			wp_enqueue_script( 'psydro-axios', PSYDRO_REVIEWS_PLUGIN_URL . '/assets/js/cdn/psydro-axios.min.js' , array( 'jquery-validate' ), PSYDRO_REVIEWS_VERSION, true );
			wp_enqueue_script( 'psydro-locales', PSYDRO_REVIEWS_PLUGIN_URL . '/assets/js/cdn/psydro-moment-with-locales.js' , PSYDRO_REVIEWS_VERSION, true );
		}
	}
}
function psydro() {
	return Psydro::instance();
}

psydro();