<?php

defined( 'ABSPATH' ) or die( 'No script kiddies please!' );
if( !class_exists('PsydroAdmin') ) {
	class PsydroAdmin {

		public static $instance = null;

		public function __construct() {
			$this->include_required_files();

			add_action( 'admin_menu', array( $this, 'psydro_menu' ) );
			add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_scripts' ) );

			$this->option = PsydroOptions::getInstance();
			$this->settings = PsydroSetting::getInstance();
			
			if( $this->settings->is_valid() && $this->settings->isError() != 1 ){
				$this->post_type = PsydroPostType::getInstance();
				$this->add_meta_box = PsydroAddMetaBox::getInstance();
				$this->add_meta_field = PsydroAddMetaField::getInstance();
				$this->shortcode_create = PsydroShortcodeColumns::getInstance();
			}
		}

		public static function getInstance() {
			if (!isset(PsydroAdmin::$instance)) {
				PsydroAdmin::$instance = new PsydroAdmin();
			}
			return PsydroAdmin::$instance;
		}

		private function include_required_files() {
			require_once( PSYDRO_REVIEWS_PLUGIN_DIR. '/includes/admin/class-psydro-reviews-options.php' );
			require_once( PSYDRO_REVIEWS_PLUGIN_DIR. '/includes/class-psydro-reviews-setting.php' );
			require_once( PSYDRO_REVIEWS_PLUGIN_DIR. '/includes/admin/class-psydro-reviews-post-type.php' );
			require_once( PSYDRO_REVIEWS_PLUGIN_DIR. '/includes/admin/class-psydro-reviews-add-meta-box.php' );
			require_once( PSYDRO_REVIEWS_PLUGIN_DIR. '/includes/admin/class-psydro-reviews-meta-field.php' );
			require_once( PSYDRO_REVIEWS_PLUGIN_DIR. '/includes/admin/class-psydro-reviews-add-shortcode-column.php' );
		}

		function psydro_menu() {
		    add_menu_page( 'Psydro Review', 'Psydro Review', 'manage_options', 'psydro-options', array( $this->option, 'render_options' ), 'dashicons-admin-page', 99 );

		    if( $this->settings->is_valid() && $this->settings->isError() != 1 ){
			    $psydro_custom_post_type = 'edit.php?post_type=psydro_shortcode';
				add_submenu_page('psydro-options', 'My Psydro Sliders', 'My Psydro Sliders', 'manage_options', $psydro_custom_post_type);

			    $psydro_add_custom_post_type = 'post-new.php?post_type=psydro_shortcode';
				add_submenu_page('psydro-options', 'Add New Slider', 'Add New Slider', 'manage_options', $psydro_add_custom_post_type);
			}
		}
	 
		public function enqueue_scripts() {
		  	wp_enqueue_script( 'psydro-admin', PSYDRO_REVIEWS_PLUGIN_URL . '/assets/js/admin/admin.js' , array( 'wp-color-picker' ), PSYDRO_REVIEWS_VERSION, true );
			wp_enqueue_style( 'psydro-admin', PSYDRO_REVIEWS_PLUGIN_URL . '/assets/css/psydro-review.min.css' , array(), PSYDRO_REVIEWS_VERSION, 'all');

			wp_enqueue_style( 'psydro-admin-spectrum', PSYDRO_REVIEWS_PLUGIN_URL . '/assets/js/spectrum/spectrum.min.css' );

			wp_enqueue_script( 'psydro-axios', PSYDRO_REVIEWS_PLUGIN_URL . '/assets/js/cdn/psydro-axios.min.js' );

			wp_enqueue_script( 'psydro-admin-spectrum-js', PSYDRO_REVIEWS_PLUGIN_URL . '/assets/js/spectrum/spectrum.min.js' , array( 'jquery' ), PSYDRO_REVIEWS_VERSION, false );
		}
	}
}