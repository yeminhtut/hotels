<div role="main"> </div>
<!-- end of main-->

<div class="content_div" onmouseover="hide_all()">
	
	<div class="navbar_left_div">
		<?php include(VIEW_PATH."layouts/layout_leftsidebar.php");?>
	</div>
	<!-- Body Content-->
	<div class="container" style="position:relative;">
		<?php include(VIEW_PATH . 'layouts/layout_side_ads.php');?>
		
		<div class="row">
		  <div class="row_alignleft"><h1 id="enquiry_title">Enquiry on <?php echo ucwords(str_replace('_',' ',$package->type))?> - <?php echo $package->title?></h1></div>
		  
			<div class="span6 text_left" style="width: 600px;">
				<?php if(count($err_msgs)) { ?>
				<p class="error" style="color:#FF6747;"><?php echo implode('<br />',$err_msgs)?></p>
				<?php } ?>				
				<?php// var_dump($company); ?>
				<p><strong>To: </strong><a target="_blank" href="/directory/review/<?php echo $company->id?>/<?php echo url_title($company->display_name)?>"><?php echo $company->display_name?></a></p>
				<p><strong>Package Name: </strong><a target="_blank" href="/tour/package/<?php echo $package->id?>/<?php echo url_title($package->title)?>"><?php echo $package->title?></a></p>
				
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
					<textarea name="remarks" id="remarks" rows="10" cols="50" class="contact_textfield"><?php echo $remarks?></textarea><br/>
					<br />
					
					<?php if(count($similar_packages)>0) { ?>
						<label class="label_title">Similar Packages</label>
						<small><em>Send enquiry to these similar packages (click on checkbox to add/remove recommendations)</em></small>
						<br /><br />
					
							<?php foreach($similar_packages as $similar_package) { ?>
		          <div id="similar_packages">
		            <input type="checkbox" name="similar_packages[]" value="<?=$similar_package['PostingID']?>" <?=@in_array($similar_package['PostingID'],$_POST['similar_packages'])?'checked':''?> />
		            <div class="similar_package">
		              <a target="_blank" href="/tour/package/<?=$similar_package['PostingID']?>/<?=url_title($similar_package['Title'])?>"><?=$similar_package['Title']?></a>
		              <?php
		              $company_arr = Company::retrieve_company_by_posting_id($similar_package['PostingID']);
		              $similar_package_company = new Company($company_arr['CompanyID']);
		              ?>
		              <div class="ta_name"><a target="_blank" href="/directory/review/<?php echo $similar_package_company->get('CompanyID')?>/<?php echo $similar_package_company->get('Name')?>"><?=$similar_package['Name']?></a></div>
		            </div>
		          </div>
		          <div style="clear:both;"></div>
		        <?php } ?>
		        <div style="clear:both;">&nbsp;</div>
		      <?php } ?>
					
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
					<p style="color: #41AAD3;">Please note that a TourPackage/TripZilla account will be created base on the email you have provided upon submission of this enquiry.</p>
					<br />
					<button id="signup_button" class="sign_up red_button" type="submit">Send Enquiry</button>

				</form> 
				
				
			</div>
		</div>
	</div>
</div>