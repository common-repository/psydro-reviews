<div id="psydro-write-review-popup" class="psydro-sign-in-rvw-form-box consumer-rvw-box psydro-hidden">
	<div class="psydrp-rvw-consumer">
		<div class="psy-write-rvw-heading">Write a review</div>
		<form id="psydro-write-review-form" enctype="multipart/form-data">
			<div class="psydro-message-container"></div>
			<fieldset>
				<div class="psy-label-text">Rating *</div>
				<div class="psydro-rvw-star"> 
					<i id="1" class="zmdi zmdi-star starchecked psydro-star"></i>
					<i id="2" class="zmdi zmdi-star starchecked psydro-star"></i>
					<i id="3" class="zmdi zmdi-star starchecked psydro-star"></i>
					<i id="4" class="zmdi zmdi-star starchecked psydro-star"></i>
					<i id="5" class="zmdi zmdi-star starchecked psydro-star"></i>
				</div>
				<div class="psy-label-text">Title your Review *</div>
				<input type="text" name="psydro_review_title" id="psydro-title" maxlength="40" placeholder="Review Title" value="" class="rvw-text" aria-required="true" autocomplete="off">
			</fieldset>
			<p id="titleCharMsg" class="note-max-length-error">
                <small><strong>Note: </strong> Max. 40 characters allowed.</small>
            </p>
			<fieldset>
				<div class="psy-label-text">Your Review</div>
				<textarea name="psydro_review_description" id="psydro-description" placeholder="The best reviews are informative, real and to the point!

Was the product/service as described and on time?
How was the customer service and communication?
Would you recommend to others?

Add pictures to your reviews where possible as 'a picture speaks a thousand words'"></textarea>
			</fieldset>
			<div class="psydro-inverified-multiple-upload-thumbs"></div>
			<fieldset>
				<div class="psy-label-text">Upload Images</div>
				<div class="psydro-upload-item">
					<label for="psydro-images" class="psydro-inverified-multiple-upload" name="psydro-unverified-images">
						upload
					</label>
				</div>
				<div class="psydro-upload-info">
					<i class="zmdi zmdi-help" aria-hidden="true"></i>
					<div class="information">
	                    <ul>
	                        <li>1. Maximum image size should be 5MB.</li>
			                <li>2. You can upload image type of jpeg, jpg, png.</li>
			                <li>3. Maximum of 3 images per review.</li>
	                    </ul>
	                </div>
				</div>
				
				<div class="psydro-multiple-upload-text-info">You can upload review images here</div>
			</fieldset>
			
			<fieldset>
				<span class="psydro-review-cancel psydro-fancybox-close"> Cancel </span>
				<input type="submit" value="Submit" id="hb-submit-writeRev" class="psydro-btn-primary">
			</fieldset>
		</form>
	</div>
</div>
 
