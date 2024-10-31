<?php

defined( 'ABSPATH' ) or die( 'No script kiddies please!' );
if( !class_exists('PsydroAddMetaBox') ) {
	class PsydroAddMetaBox {

		public static $instance = null;

		public function __construct() {

			add_action( 'add_meta_boxes_psydro_shortcode', array( $this, 'psydro_shortcode_meta_box' ) );
			//add_action( 'post_updated', array( $this,'check_post_update' ), 10, 3 );
		}

		public static function getInstance() {
			if (!isset(PsydroAddMetaBox::$instance)) {
				PsydroAddMetaBox::$instance = new PsydroAddMetaBox();
			}
			return PsydroAddMetaBox::$instance;
		}

		public function psydro_shortcode_meta_box() {

		    add_meta_box(
		        'psydro-slider-shortcode',
		        __( 'Psydro Slider Shortcode', 'Psydro Reviews' ),
		         array( $this, 'psydro_shortcode_meta_box_callback')
		    );
		}

		
		function psydro_shortcode_meta_box_callback( $post ) {

		    wp_nonce_field( 'psydro_slider_shortcode', 'psydro_slider_shortcode' );

		    $value = get_post_meta( $post->ID, '_psydro_hori_shortcode', true );

		    ?>
		    	<div class="psydro-tab-content">
						<form id="psydro-shortcode-generator-form">
							<table class="psydro-table-striped" width="100%">
								<tr>
								  <td><label>Select Orientation :</label>
								  	<p>Set orientation view of the review extension</p>
								  </td>
								  <td>
								  	<?php 
									  	$screen = get_current_screen();
										if ( $screen->parent_base === 'edit' ) {
										    $orientation = get_post_meta( get_the_ID(), 'orientation', true);
										}
										else{
											$orientation = psydro()->setting->get_config('orientation');
										}
								  	?>
								  	<select id="orientation" class="select-dimension" name="orientation">
										<option value="horizontal" <?php if($orientation === "horizontal") echo "selected"; ?> >Horizontal </option>
										<option value="vertical" <?php if($orientation === "vertical") echo "selected"; ?> >Vertical</option>
									</select>
								  </td>
								</tr>
								<tr>
								  <td><label> Reviews Background Color : </label>
								  	<p>Set background color of reviews slider</p>
								  </td>
								  <td>
								  	<?php 
										if ( $screen->parent_base === 'edit' ) {
										    $review_bg_color = get_post_meta( get_the_ID(), 'psydro-review-bg-color', true);
										}
										else{
											$review_bg_color = psydro()->setting->get_config('psydro-review-bg-color');
										}
								  	?>
								  	<input type="text" id="psydro-review-bg-color" name="psydro-review-bg-color" value="<?php echo $review_bg_color; ?>" class="left-bg-color" />
								  </td>
								</tr>
								<tr>
								  <td><label> Average Rating Background Color :  </label><p>Set background color of Avg rating slider</p></td>
								  <td>
								  	<?php 
										if ( $screen->parent_base === 'edit' ) {
										    $average_rating_bg_color = get_post_meta( get_the_ID(), 'psydro-average-rating-bg-color', true);
										}
										else{
											$average_rating_bg_color = psydro()->setting->get_config('average_rating_bg_color');
										}
								  	?>
								  	<input type="text" id="psydro-average-rating-bg-color" name="psydro-average-rating-bg-color" value=" <?php echo $average_rating_bg_color; ?>" class="right-bg-color"  />
								  </td>
								</tr>
								<tr>
								  <td><label> Reviews Font Color : </label><p>Set background color of Review's Font</p></td>
								  <td>
								  	<?php 
										if ( $screen->parent_base === 'edit' ) {
										    $reviews_font_color = get_post_meta( get_the_ID(), 'psydro-reviews-font-color', true);
										}
										else{
											$reviews_font_color = psydro()->setting->get_config('review_font_color');
										}
								  	?>
								  	<input type="text" id="psydro-reviews-font-color" name="psydro-reviews-font-color" value="<?php echo $reviews_font_color; ?>" class="font-color" />
								  </td>
								</tr>
								<tr>
								  <td><label> Title Font Color :</label><p>Set background color of Review's Title Font</p></td>
								  <td>
								  	<?php 
										if ( $screen->parent_base === 'edit' ) {
										    $title_font_color = get_post_meta( get_the_ID(), 'psydro-title-font-color', true);
										}
										else{
											$title_font_color = psydro()->setting->get_config('title_font_color');
										}
								  	?>
								  	<input type="text" id="psydro-title-font-color" name="psydro-title-font-color" value="<?php echo $title_font_color; ?>" class="title-color" data-default-color="#000000" />
								  </td>
								</tr>
								<tr>
								  <td><label>Reviews Image Slider : </label><p>Change image slider status</p><p id="image-info">Note: If number of images on merchant page is less than 10; image thumbnails would not be visible on slider.</p></td>
								  <td>
								  	<?php 
										if ( $screen->parent_base === 'edit' ) {
										    $image_slider = get_post_meta( get_the_ID(), 'reviews-image', true);
										}
										else{
											$image_slider = psydro()->setting->get_config('image_slider');
										}
								  	?>
								  	<select id="image" name="reviews-image" class="slider-image-drpdn">
										<option value="disable" <?php if($image_slider === "disable") echo "selected"; ?> >Disable </option>
										<option value="enable" <?php if($image_slider === "enable") echo "selected"; ?> >Enable</option>
									</select>
								  </td>
								</tr>
							</table>
						</form>
					</div>
		    <?php
		}
	}
}