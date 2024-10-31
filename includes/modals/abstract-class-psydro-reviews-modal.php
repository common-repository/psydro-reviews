<?php

defined( 'ABSPATH' ) or die( 'No script kiddies please!' );
if( !class_exists('PsydroModal') ) {
	abstract class PsydroModal {
		
		public function __construct() {
			add_action('wp_footer', array( $this, 'render_modal_content' ) );
		}

		public function render_modal_content() {
			ob_start();
				$this->render();
			echo  ob_get_clean();
		}

		abstract public function render();
	}
} 