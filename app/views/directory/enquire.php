<div role="main"> </div>
<!-- end of main-->

<div class="content_div" onmouseover="hide_all()">
	
	<div class="navbar_left_div">
		<?php include(VIEW_PATH."layouts/layout_leftsidebar.php");?>
	</div>
	<!-- Body Content-->
	<div class="container">
		
		<div class="row">
		  <div class="row_alignleft"><h1 id="enquiry_title">Enquiry to <?php echo $company->display_name?></h1></div>
		  
			<div class="span6 text_left">
				<?php if(count($err_msgs)) { ?>
				<p class="error" style="color:#FF6747;"><?php echo implode('<br />',$err_msgs)?></p>
				<?php } ?>
				
				<form method="POST">
					<label class="label_title">Contact Details</label>
					<input type="text" class="contact_textfield" name="name" id="name" placeholder="Your name" value="<?php echo $name?>">
					<input type="text" class="contact_textfield" name="email" id="email" placeholder="Your email address" value="<?php echo $email?>">
					<input type="text" class="contact_textfield" name="contact" id="contact" placeholder="Your contact number" value="<?php echo $contact?>">
					<div class="row_form">
					  <label class="label_title">Passengers</label>
					  <span>
					  	<input style="width: 50px;" type="text" class="contact_textfield" name="adult" id="adult" placeholder="Adult" value="<?php echo $adult?>">
					  </span>
					  <span>
					  	<input style="width: 50px;" type="text" class="contact_textfield" name="child" id="child" placeholder="Child" value="<?php echo $child?>">
					  </span>
					  <span>
					  	<input style="width: 50px;" type="text" class="contact_textfield" name="infant" id="infant" placeholder="Infant" value="<?php echo $infant?>">
					  </span>					  
					</div>
					<label class="label_title">Enquiry Message</label>
					<textarea name="remarks" rows="10" cols="50" class="contact_textfield"><?php echo $remarks?></textarea><br/>
					<br />
					<?php 
					/*$user=User::getUser();
					if(!$user) {
						?>
						<label class="label_title">Image Verification</label>
						<img src="/securimage/show" /><br />
						<input type="text" class="contact_textfield" name="captcha" id="captcha" placeholder="Enter the text above" value="<?php echo $captcha?>">
						<br />
						<?php					
					} */
					?>
					<p style="color: #41AAD3;">Please note that a TourPackage/TripZilla account will be created base on the email you have provided upon submission of this enquiry.</p>
					<br />
					<button id="signup_button" class="sign_up red_button" type="submit">Send Enquiry</button>

				</form> 
				
				
			</div>
		</div>
	</div>
</div>