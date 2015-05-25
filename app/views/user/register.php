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
				<div class="row_alignleft" style="margin-left:0px;"><h2>Sign Up with Us Today</h2></div>
				
				<?php if(isset($_SESSION['register']['err_msg'])) { ?>
				<p class="error" style="color:#FF6747;"><?php echo $_SESSION['register']['err_msg']?></p>
				<?php } ?>
				
				<form action="/user/process_register" method="post">
					<div class="signup_header"><b>Your Email Address</b></div>
					<input type="text" class="signup_textfield" name="email" id="email_address" placeholder="Enter your email address" value="<?php echo (isset($_SESSION['register']['email']))?$_SESSION['register']['email']:''?>">
						
					<div class="signup_header2">
						<table>
							<tr>
								<td style="padding:0px"><input type="checkbox" name="agree" id="agree" value="agree">&nbsp; </td>
								<td style="padding-top:3px">I agree to the <a href="/terms" target="_blank">Terms & Conditions</a></td>
							</tr>
						</table>
					</div>
					
					<input type="hidden" name="from" value="form" />
					
					<div class="signup_header2"><button id="signup_button" class="sign_up red_button" type="submit">Sign Up For Free</button></div>
					<div class="signup_header2">----------------------------------- OR -----------------------------------</div>
					<div class="signup_header2">
						<a href="javascript:facebook_login();"><img class="footer_nav_facebook" src="/img/icons/login-facebook-2.png" alt=""/></a>
					</div>

				</form> 
			</div>
		</div>
	</div>
</div>
<?php 
if(isset($_SESSION['register']))
	unset($_SESSION['register']);
?>