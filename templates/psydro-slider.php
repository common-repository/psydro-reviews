<?php 	do_action('before_psydro_slider', $params); $flag=0; ?>
<div class="<?php echo $params['orientation'] == 'horizontal' ? "psydro-horizontal-sliderbar " : "psydro-vertical-sliderbar "; ?> <?php if ($params['image_slider'] == 'enable' && $params['orientation'] == 'horizontal')
  {$flag=1; echo '';} 
  else 
  echo "psydro-no-image-slider "; ?> 
<?php if($flag==1) if( empty($reviews['images'] )) echo " psydro-no-image-slider "; ?>
psydro-horizontal-slider-loader " id="<?php echo $params['id']; ?>">
	<div class="psydro-loader-box"><div class="psydro-loader"></div></div>
	<div class="psydro-rating-extension">
			<div class="psydro-extenstion-main left-home-slider">
		<div class="psydro-wrap">
			<div class="psydro-top-rating">
				<?php for ($i = 1; $i <= $reviews['averageRating']; $i++) { ?>
				<i class="zmdi zmdi-star starchecked" data-alt="1" title="review"></i>
				<?php } 
				if( $reviews['averageRating'] === 4 ){ ?>
				<i class="zmdi zmdi-star" data-alt="1" title="review"></i> 
				<?php }
				?>
			</div>
			<div class="psydro-rating-text">
				<span class="psydro-no-of-review-text"> Based on </span> <span class="psydro-no-of-review"> <?php echo isset( $reviews['noOfReviews'] ) ? $reviews['noOfReviews'] : 0 ; ?> </span> <span class="psydro-no-of-review-text"> reviews </span>
			</div>
			<div class="psydro-logo-extension">
				<a target="_blank" rel="nofollow noopener" href="<?php echo esc_url($reviews['url']);?>">
					<img src="<?php echo esc_url($reviews['logo']) ?>" alt="logo">
				</a>
			</div>
			
			<div class="psydro-slider-text">
				<a  class="psydro-review-btn psydro-write-a-review-link">Write A Review</a>
			</div>
		</div>
		</div>
		<div class="psydro-left-content">
				<div class="psydro-horizontal-sidebar" id="slick_<?php echo $params['id']; ?>">
					<?php  foreach ($reviews['reviews'] as $key => $review) {  ?>
					<div class="psydro-item" id="<?php echo $params['id'] . '_review_'. $key; ?>">
						<div class="psydro-item-header" >
							<div class="psydro-review-username">
								<?php echo $review['reviewer_username']; ?>
							</div>
							<div class="psydro-rating-time">
								<?php echo $review['created_date']; ?>
							</div>
						</div>
						<div class="psydro-vertical-slider-stars">
							<?php for ($i = 1; $i <= $review['rating']; $i++) { ?>
							<i class="zmdi zmdi-star starchecked" data-alt="1" title="review"></i>
							<?php }
							if( $review['rating'] == 4 ) {
								?> <i class="zmdi zmdi-star" data-alt="1" title="review"></i>  <?php
							}
							?>
						</div>
						<div class="psydro-rating-header-text">
							<?php echo $review['title']; ?>
						</div>
						<div class="psydro-review-full psydro-hidden">
							<?php echo ( $review['description']); ?>
						</div>
						<div class="psydro-rating-slider-comment">
							
							<?php echo substr( $review['description'], 0, 68 ); ?>
							
							<?php if(strlen( $review['description'] ) > 68): ?>

								<div data-slider-id="" data-review-id="<?php echo $params['id'] . '_review_'. $key; ?>" class="psydro-read-more-point">Read More</div>
							<?php endif; ?>
						</div>
					</div>
					<?php } ?>
				</div>
			<div class="psydro-bottom-img-slider">
				<div class="psydro-full-bottom-slider">
						<?php 
							if (!empty($reviews['images']) && $params['image_slider'] != 'disable') {
								?><div id="psydro-img-slider_<?php echo $params['id'] ?>" class="psydro-img-slider"><?php 
								foreach ($reviews['images'] as $image) { ?>
									<div class="item">
										<a href="<?php print_r($image['image_url']); ?>" data-fancybox="psydro">
										 <img src='<?php print_r($image['image_url']); ?>' class="reviewImgThumb psydro-image-fancy imageZoom" alt="review-image" />
										 </a>
									</div>
								<?php } 
             					?></div><?php 
							}
						?>
				</div>
			</div>
		</div>
	</div>
</div>
<?php 	do_action('after_psydro_slider', $params); ?>
