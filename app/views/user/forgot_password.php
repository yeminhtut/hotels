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
				<div class="row_alignleft" style="margin-left:0px;"><h1 id="login_title">Forgot Password</h1></div>
				
				<?php if(isset($error_msg)) { ?>
				<p class="error" style="color:#FF6747;"><?php echo $error_msg?></p>
				<?php } ?>
				
				<?php if(isset($success_msg)) { ?>
				<p class="success" style="color:#41AAD3;"><?php echo $success_msg?></p>
				<?php } ?>
				
				<form action="/user/forgot_password" method="post">
					<div class="signup_header"><b>Your Email Address</b></div>
					<input type="text" class="signup_textfield" name="email" id="email_address" placeholder="Enter your email address" value="<?php echo (isset($_SESSION['login']['email']))?$_SESSION['login']['email']:''?>">
					
					<div class="signup_header2">
					  <button id="signup_button" class="sign_up red_button" type="submit">Send Password</button>
					</div>

				</form>
			</div>
		</div>
	</div>
</div>