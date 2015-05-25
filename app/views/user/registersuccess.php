<div role="main"> </div>
<!-- end of main-->

<div class="content_div" onmouseover="hide_all()">
	
	<div class="navbar_left_div">
		<?php include(VIEW_PATH . 'layouts/layout_leftsidebar.php');?>
	</div>
	<!-- Body Content-->
	<div class="container">
		
		<div class="row">
		
			<h2>Thank you for subscribing to TourPackags.com.sg</h2>
		
			<p>TourPackages.com.sg is a product of TripZilla.com. A TourPackage/TripZilla account has been registered for you to shortlist tour packages and track your enquiries.</p>
			<br />
			<p>Your password has been sent to your inbox<?php echo ($email!=''?" for $email.":'.')?></p>
			<br />
			<br />
			<div style="text-align: center;">
				<a href="/user/profile">Update Profile Now</a>
			</div>
			
			<br />
			<br />
			
		</div>
	</div>
</div>
