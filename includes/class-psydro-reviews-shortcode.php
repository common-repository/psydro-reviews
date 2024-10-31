<?php

defined( 'ABSPATH' ) or die( 'No script kiddies please!' );
if( !class_exists('PsydroShortcode') ) {
	class PsydroShortcode {

		public static $instance = null;

		public function __construct() {

			add_shortcode( 'psydro-review-slider' , array( $this, 'psydro_review_slider' ) );
			add_action( 'before_psydro_slider' , array( $this, 'genrate_slider_css' ), 10, 1 );
			add_action( 'before_psydro_slider' , array( $this, 'genrate_slider_js' ), 11, 1 );
		}

		public static function getInstance() {
			if (!isset(PsydroShortcode::$instance)) {
				PsydroShortcode::$instance = new PsydroShortcode();
			}
			return PsydroShortcode::$instance;
		}

		public function psydro_review_slider( $atts ) {
			$slider_id = isset( $atts['id'] )?absint( $atts['id'] ):0;
			if( $slider_id <= 0 ){		
				return false;
			}

			if( false === ($slider = get_post($slider_id) ) || $slider->post_status != 'publish' || $slider->post_type != 'psydro_shortcode' ){
				return false;
			}


			$params = shortcode_atts( array(
		        'review_bg_color' => psydro()->setting->get_config('psydro-review-bg-color'),
		        'average_rating_bg_color' => psydro()->setting->get_config('average_rating_bg_color'),
		        'orientation' => psydro()->setting->get_config('orientation'),
		        'title_font_color' => psydro()->setting->get_config('title_font_color'),
		        'review_font_color' => psydro()->setting->get_config('review_font_color'),
		        'image_slider' => psydro()->setting->get_config('image_slider'),
		    ),array(
		        'review_bg_color' => get_post_meta( $slider->ID , 'psydro-review-bg-color' , true),
		        'average_rating_bg_color' => get_post_meta( $slider->ID , 'psydro-average-rating-bg-color' , true),
		        'orientation' => get_post_meta( $slider->ID , 'orientation' , true),
		        'title_font_color' => get_post_meta( $slider->ID , 'psydro-title-font-color' , true),
		        'review_font_color' => get_post_meta( $slider->ID , 'psydro-reviews-font-color' , true),
		        'image_slider' => get_post_meta( $slider->ID , 'reviews-image' , true),
		    )); 

		    if(!psydro()->setting->is_valid() ){
		    	return false;
		    }

		    $reviews = psydro_curl_request('extentionReviews');
		   	
		   	if(empty($reviews) || $reviews['success'] === false) {
		   		return false;
		   	}

		   	$params['id'] = 'psydro_'.time().rand(99,9999);
		   	
		   	$reviews = $reviews['data'];

		   	ob_start();
				include( PSYDRO_REVIEWS_PLUGIN_DIR . '/templates/psydro-slider.php' );
			return ob_get_clean();
		}

		function genrate_slider_css($params) {
			ob_start();
			?>
			<style type="text/css">
				#<?php echo $params['id'] ?> .left-home-slider{
					background: <?php  echo $params['average_rating_bg_color']; ?>;
				}
				#<?php echo $params['id'] ?> .psydro-left-content{
					background: <?php  echo $params['review_bg_color']; ?>;
				}
				#<?php echo $params['id'] ?> .psydro-review-username{
					color: <?php  echo $params['title_font_color']; ?>;
				}
				#<?php echo $params['id'] ?> .psydro-rating-time{
					color: <?php  echo $params['title_font_color']; ?>;
				}
				#<?php echo $params['id'] ?> .psydro-rating-header-text{
					color: <?php  echo $params['title_font_color']; ?>;
				}
				#<?php echo $params['id'] ?> .psydro-no-of-review{
					color: <?php  echo $params['title_font_color']; ?>;
				}
				#<?php echo $params['id'] ?> .psydro-no-of-review-text{
					color: <?php  echo $params['review_font_color']; ?>;
				}
				#<?php echo $params['id'] ?> .psydro-rating-slider-comment{
					color: <?php echo $params['review_font_color']; ?>;
				}
			</style>
			<?php	
			echo ob_get_clean();
		}

		function genrate_slider_js($params) {
			$args = psydro()->setting->get_config('slider_config');
				
			if($params['image_slider'] == 'enable') { 
				$image_slider_args = $args;
				if($params['orientation'] != 'horizontal') {
					$image_slider_args['slidesToScroll'] = 1; 
					$image_slider_args['draggable'] = false; 
					$image_slider_args['responsive'][0]['settings']['slidesToShow'] = 3;
					$image_slider_args['responsive'][1]['settings']['slidesToShow'] = 3;
					$image_slider_args['responsive'][2]['settings']['slidesToShow'] = 3;

					$image_slider_args['responsive'][0]['settings']['slidesToScroll'] = 1;
					$image_slider_args['responsive'][1]['settings']['slidesToScroll'] = 1;
					$image_slider_args['responsive'][2]['settings']['slidesToScroll'] = 1;
				}else{ 
					$image_slider_args['slidesToShow'] = 10; 
					$image_slider_args['draggable'] = false; 
					$image_slider_args['responsive'][0]['settings']['slidesToShow'] = 10;
					$image_slider_args['responsive'][1]['settings']['slidesToShow'] = 3;
					$image_slider_args['responsive'][2]['settings']['slidesToShow'] = 3;

					$image_slider_args['responsive'][0]['settings']['slidesToScroll'] = 11;
					$image_slider_args['responsive'][1]['settings']['slidesToScroll'] = 1;
					$image_slider_args['responsive'][2]['settings']['slidesToScroll'] = 1;
				}
			}

			if($params['orientation'] != 'horizontal') {
				$args['vertical'] = true;
				$args['slidesToScroll'] = 1;
				$args['speed'] = 5000;
				unset($args['responsive']);
			}else{
				$args['slidesToScroll'] = 1;
				$args['speed'] = 5000;
			}

			ob_start();
			?> 
			<script>
				jQuery(document).ready(function(){
				  	jQuery('#slick_<?php echo $params['id'] ?>').slick(<?php echo json_encode($args); ?>);
					<?php if(isset($image_slider_args)): ?>
						jQuery('#psydro-img-slider_<?php echo $params['id'] ?>').slick(<?php echo json_encode($image_slider_args); ?>);
					<?php endif; ?>
				});
			
			</script>
			<?php	
			
			echo ob_get_clean();
		}
	}
}
PsydroShortcode::getInstance();