<div role="main"> </div>
<!-- end of main-->

<div class="content_div" onmouseover="hide_all()">
	
	<div class="navbar_left_div">
		<?php include(VIEW_PATH . 'layouts/layout_leftsidebar.php');?>
	</div>
	<!-- Body Content-->
	<div class="container">
		
		
		
		<div class="row">
		  
		  
			<div class="span6 text_left">
				<img src="/images/map_asia.jpg" alt="">
			</div>
			
			<div class="span6 text_left" style="margin-left:80px; width:400px">
				<div class="row_alignleft" style="margin-left:0px;"><h1 id="login_title">Login</h1></div>
				<small>You may login to TourPackages.com.sg with your <a href="http://tripzilla.sg">TripZilla.com</a> or <a href="http://tourpackages.com">TourPackages.com.sg</a> login</small>
				<br /><br />
				
				<?php if(isset($_SESSION['login']['err_msg'])) { ?>
				<span class="error" style="color:#FF6747;"><?php echo $_SESSION['login']['err_msg']?></span><br /><br />
				<?php } ?>
				
				<form action="/user/process_login" method="post">
					<div class="signup_header"><b>Your Email Address</b></div>
					<input type="text" class="signup_textfield" name="email" id="email_address" placeholder="Enter your email address" value="<?php echo (isset($_SESSION['login']['email']))?$_SESSION['login']['email']:''?>">
					
					<div class="signup_header2"><b>Password</b></div>
					<input type="password" class="signup_textfield" name="password" id="password" placeholder="Enter your password">
					
					<div class="signup_header2">
					  <button id="signup_button" class="sign_up red_button" type="submit">Login</button>
					</div> 
					
					<div class="signup_header2">
					  <div class="forgot_password"><a href="/user/forgot_password">Forgot password?</a></div>
					</div>
					<div class="clearfix"></div>   
					
					<br />
					
					<p><font style="font-size: 30px;">Not a member yet?</font> <a href="/user/register">Sign up now</a></p>
					
					<div class="clearfix"></div> 
				</form> 
			</div>
		</div>
	</div>
</div>
<?php 
if(isset($_SESSION['login']))
	unset($_SESSION['login']);
?>