<?php $tab_class =  !psydro()->setting->isValid()  ? 'invalid-tab' : true ; ?>
<div class="psydro-header-wrap">
 	
	<div class="psydro-info">
		<h2>Psydro Review-Configuration</h2>
    	<em>Create your Psydro Review shortcode by adjusting the values below</em>
  	</div>
  	<div class="psydro-brand">
 		<img src="<?php echo PSYDRO_REVIEWS_PLUGIN_URL ?>/assets/images/psydro-logo.png">
	</div>
</div>


<div class="psydro-options ">
	<div class="psydro-tab" >
		<p>To allow Psydro review slider to fetch your company reviews. Please enter your API key from your verified Psydro merchant profile. </p>

		<p>Enter your Psydro API key and press the Validate API button.</p>

		<div class="psydro-tab-content">
		<form id="psydro-form-validate-api-key"  data-action="<?php echo admin_url( 'admin-ajax.php' );  ?>">
			<input required type="text" value="<?php echo psydro()->setting->get('api_key'); ?>" name="key" id="psydro_key" class="psydro-key-input-type" />
			<input type="hidden" name="action" value="psdyro_save_api_key" />
			<input type="submit" class="psydro-validate-api-btn" name="psydro_api_button" id="psydro_api_button" value="Validate API" />
			<div id="psydro-admin-api-message"></div>
		</form>
		</div>
		<?php if(  psydro()->setting->isError() != 1 && psydro()->setting->isSuccess() == 1 ){ ?>
		<div class="psydro-slider-buttons">
			<a href="edit.php?post_type=psydro_shortcode"><input type="button" name="my_psydro_slider" id="my_psydro_slider" value="My Psydro Slider" /></a>

			<a href="post-new.php?post_type=psydro_shortcode"><input type="button" name="add_new_slider" id="add_new_slider" value="Add New Slider" /></a>
		</div>
		<script type="text/javascript">
            document.getElementById("psydro_api_button").setAttribute("disabled", "true");
            jQuery('#psydro_api_button').css({
                'background-color': '#a0a5aa',
                'cursor': 'not-allowed'
            });
		</script>
		<?php } ?>
		<p>If you face any difficulty in setting up the slider, we are just a email away ^_^ <a href="">support@psydro.com</a></p>
	</div>
	<div class="psydro-instruction">
		<img src="<?php echo PSYDRO_REVIEWS_PLUGIN_URL ?>/assets/images/psydro.jpg">
		<h2>Features</h2>
		<ul>
			<li>Simple and easy to use.</li> 
			<li>Use the images slider to show review images on your store.</li>
			<li>Highly customizable from a backend. Use the color picker to pick any color of your choice matching the color scheme of your store.</li>
			<li>Customers can write reviews without leaving your store. If they do not have an account they can register from the extensions itself.</li> 
		</ul>

		<h2>What you get with Psydro’s Review Extension</h2>
		<ul>
			<li><strong>Social sharing - </strong>Sharing reviews will only help your company’s visibility.</li>

			<li><strong>Decrease cart abandonment - </strong>Our checkout page widget will help decrease cart abandonment.</li>

			<li><strong>Increase conversions - </strong>Showing your customers you care will improve your conversion rate.</li>

			<li><strong>Widgets - </strong>You can show your reviews and review images on your homepage.</li>

			<li><strong>Help build your reputation through reviews - </strong>potential customers love to read others reviews.</li>

			<li><strong>Drive more traffic  - </strong>User Generated Content will help your search engine rank.</li>
		</ul>
	</div>
</div>