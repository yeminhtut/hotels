<div role="main"> </div>
<!-- end of main-->

<div class="content_div" onmouseover="hide_all()">
	
	<div class="navbar_left_div">
		<?php include(VIEW_PATH."layouts/layout_leftsidebar.php");?>
	</div>
	<!-- Body Content-->
	<div class="container">
		
		
		
		<div class="row">
		  <div class="row_alignleft"><h2>CONTACT US</h2></div>
		  
			<div class="span6 text_left">
				<p>
					<b>Advertising & Technical Support</b><br/>
					To advertise on TripZilla or contact us for technical support, please use the form on the right.
				</p>
				<br/>
				<p>
					<b>Travel Agents & Travel Agencies</b><br/>
					Are you a travel agent or representing a tour agency? Sign up for a free account with us and start posting your packages for FREE!
				</p>
			</div>
			
			<div class="span6 text_left" style="margin-left:80px; width:400px">
				<?php if($err_msg) { ?>
				<p class="error" style="color:#FF6747;"><?php echo $err_msg?></p>
				<?php } ?>
				
				<?php if($success_msg) { ?>
				<p class="success" style="color:#41AAD3;"><?php echo $success_msg?></p>
				<?php } ?>
				
				<form method="POST">
					<input type="text" class="contact_textfield" name="name" id="name" placeholder="Your name" value="<?php echo $name?>">
					<input type="text" class="contact_textfield" name="email" id="email_address" placeholder="Your email address" value="<?php echo $email?>">
					<select name="type" style="width:415px">
						<option <?php echo ($type=='Bussiness Development')?'selected="selected"':''?>>Bussiness Development</option>
						<option <?php echo ($type=='Advertising Sales')?'selected="selected"':''?>>Advertising Sales</option>
						<option <?php echo ($type=='Media Enquiries')?'selected="selected"':''?>>Media Enquiries</option>
						<option <?php echo ($type=='General Feedback')?'selected="selected"':''?>>General Feedback</option>
						<option <?php echo ($type=='Others')?'selected="selected"':''?>>Others</option>
					</select>
					<input type="text" class="contact_textfield" name="subject" id="subject" placeholder="Subject" value="<?php echo $subject?>">
					<textarea name="message" rows="10" cols="50" class="contact_textfield"><?php echo $message?></textarea><br/>
					<?php 
					$user=User::getUser();
					if(!$user) {
						?>
						<label class="label_title">Image Verification</label>
						<img src="/securimage/show" /><br />
						<input type="text" class="contact_textfield" name="captcha" id="captcha" placeholder="Enter the text above" value="<?php echo $captcha?>">
						<br />
						<?php					
					}
					?>
					<button id="signup_button" class="sign_up red_button" type="submit">Post Message</button>

				</form> 
			</div>
		</div>
	</div>
</div>