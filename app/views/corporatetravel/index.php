<div role="main"> </div>
<!-- end of main-->

<div class="content_div" onmouseover="hide_all()">
	
	<div class="navbar_left_div">
		<?php include(VIEW_PATH."layouts/layout_leftsidebar.php");?>
	</div>
	<!-- Body Content-->
	<div class="container">
		
		<div class="row">
		  <div class="row_alignleft"><h1 id="enquiry_title">Corporate Travel</h1></div>
		  
			<div class="span6 text_left" style="width: 600px;">
				<?php if(count($err_msgs)) { ?>
				<p class="error" style="color:#FF6747;"><?php echo implode('<br />',$err_msgs)?></p>
				<?php } ?>
				
				<?php if(isset($success_msg)) { ?>
				<p class="success" style="color:#41AAD3;"><?php echo $success_msg?></p>
				<?php } else { ?>
				
				<form method="POST">
					<label class="label_title">* Contact Person</label>
					<input type="text" class="contact_textfield" name="contact_person" id="contact_person" value="<?php echo $contact_person?>">
					
					<label class="label_title">* Contact Number</label>
					<input type="text" class="contact_textfield" name="contact_number" id="contact_number" value="<?php echo $contact_number?>">
					
					<label class="label_title">* Email Address</label>
					<input type="text" class="contact_textfield" name="email" id="email" value="<?php echo $email?>">
					
					<label class="label_title">* Company Name</label>
					<input type="text" class="contact_textfield" name="company_name" id="company_name" value="<?php echo $company_name?>">
					
					<label class="label_title">* Preferred Destination</label>
					<div id="preferred_destination_div" style="padding-left: 10px;">
            <?php foreach ($destinations as $destination) { ?>
            <div style="float: left; width: 200px;">
	            <label>
	             	<input type="checkbox" name="preferred_destination[]" <?php echo (is_array($preferred_destination) && in_array($destination, $preferred_destination))?'checked="checked"':''?> value="<?php echo $destination ?>">
	             	<?php echo $destination ?>
	            </label>
            </div>
            <?php } ?>
            <div style="clear: both;"></div>
          </div>
          <br />
          
          <label class="label_title">* Pax</label>
					<input type="text" class="contact_textfield" name="pax" id="pax" value="<?php echo $pax?>">
					
					<label class="label_title">Additional Comments</label>
					<textarea name="additional_comments" rows="10" cols="50" class="contact_textfield" placeholder="Budget, preferred departure dates and length of trip, theme of trip if applicable (team building, beach resort, food and shopping, sight-seeing etc)"><?php echo $additional_comments?></textarea><br/>
					
					<button id="signup_button" class="sign_up red_button" type="submit">Send Enquiry</button>

				</form> 
				
				<?php } ?>
				
			</div>
		</div>
	</div>
</div>
