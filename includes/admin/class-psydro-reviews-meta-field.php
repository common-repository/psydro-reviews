<?php

defined( 'ABSPATH' ) or die( 'No script kiddies please!' );
if( !class_exists('PsydroAddMetaField') ) {
	class PsydroAddMetaField {

		public static $instance = null;

		public function __construct() {

			add_action('save_post', array( $this, 'psydro_slider_shortcode_save_postdata' ));
			add_action( 'save_post', array( $this, 'psydro_custom_post_type_title' ));
			add_action( 'edit_form_after_title', array( $this, 'psydro_after_title' ));
		}

		public static function getInstance() {
			if (!isset(PsydroAddMetaField::$instance)) {
				PsydroAddMetaField::$instance = new PsydroAddMetaField();
			}
			return PsydroAddMetaField::$instance;
		}

		public function psydro_slider_shortcode_save_postdata($post_id)
		{
		    if (array_key_exists('orientation', $_POST)) {
		        update_post_meta(
		            $post_id,
		            'orientation',
		            trim($_POST['orientation'])
		        );
		    }
		    if (array_key_exists('psydro-review-bg-color', $_POST)) {
		        update_post_meta(
		            $post_id,
		            'psydro-review-bg-color',
		            trim($_POST['psydro-review-bg-color'])
		        );
		    }
		    if (array_key_exists('psydro-average-rating-bg-color', $_POST)) {
		        update_post_meta(
		            $post_id,
		            'psydro-average-rating-bg-color',
		            trim($_POST['psydro-average-rating-bg-color'])
		        );
		    }
		    if (array_key_exists('psydro-reviews-font-color', $_POST)) {
		        update_post_meta(
		            $post_id,
		            'psydro-reviews-font-color',
		            trim($_POST['psydro-reviews-font-color'])
		        );
		    }
		    if (array_key_exists('psydro-title-font-color', $_POST)) {
		        update_post_meta(
		            $post_id,
		            'psydro-title-font-color',
		            trim($_POST['psydro-title-font-color'])
		        );
		    }
		    if (array_key_exists('reviews-image', $_POST)) {
		        update_post_meta(
		            $post_id,
		            'reviews-image',
		            trim($_POST['reviews-image'])
		        );
		    }
		}

		function psydro_custom_post_type_title (  $post_id ) {
			global $wpdb, $post;

		    if ( get_post_type( $post_id ) == 'psydro_shortcode' && isset($_REQUEST['_wpnonce']) ) {
				$postTitle = @sanitize_text_field($_REQUEST['post_title']);
				if( !empty( $postTitle ) ){
					$title = $postTitle;
				}else{
			        $name= 'Psydro Slider';
			        $title = $name.' id - '.$post_id;
			        $postid = array( 'ID' => $post_id );
			        $wpdb->update( $wpdb->posts, array( 'post_title' => $title ), $postid );
				}
		    }
			
		}

		function psydro_after_title() {
			$postType = get_post_type();
			$id = get_the_ID();
		    $scr = get_current_screen();
		    if ( ( $scr->base !== 'post' && $scr->base !== 'page' ) || $scr->action === 'add' )
		        return;
		    if($postType == 'psydro_shortcode' ){
		    	echo '<p class="psydro-review-slider-edit-tagline">Please use following shortcode to display your Psydro Review Slider</p>';
		    	echo '<p class="psydro-review-slider-edit-shorcode">[psydro-review-slider id="'.$id.'"]</p>';
		    }
		}
	}
}