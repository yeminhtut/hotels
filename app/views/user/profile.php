<div role="main"> </div>
<!-- end of main-->

<div class="content_div" onmouseover="hide_all()">
	
	<div class="navbar_left_div">
		<?php include(VIEW_PATH . 'layouts/layout_leftsidebar.php');?>
	</div>
	<!-- Body Content-->
	<div class="container">
		<?php// var_dump($html_gender); ?>
		<div class="row">		  
		  
		  <form method="POST">
		  
			<table style="text-align: left;">
				<tbody>
					<?php if(isset($error_msg)) { ?>
				  <tr>
				  	<td>
				  		<p class="error" style="color: #FF6747;"><?php echo implode("<br />",$error_msg)?></p>
				  	</td>
				  </tr>
				  <?php } ?>
				  
				  <?php if(isset($success_msg)) { ?>
					<tr>
				  	<td>
				  		<p class="success" style="color: #41AAD3;"><?php echo implode("<br />",$success_msg)?></p>
				  	</td>
				  </tr>
					<?php } ?>
					
					<tr>
						<td align="left" colspan="2">
							<h3>Update Profile</h3>
						</td>
					</tr>
					<tr>
						<td align="left">
							<strong>Name: </strong>
						</td>
						<td align="left">
							<input type="text" value="<?php echo $user->get('fullname')?>" name="name" id="search-textLong">
						</td>
					</tr>
					<tr>
						<td>
						</td>
						<td align="left" name_error="">
						</td>
					</tr>
					<tr>
						<td>
						</td>
						<td align="left" email_error="">
						</td>
					</tr>
					<tr>
						<td align="left">
							<strong>Date of Birth: </strong>
						</td>
						<td align="left">
							<input type="text" value="<?php echo $dob?>" name="dob" id="datepicker">
						</td>
					</tr>
					<tr>
						<td>
						</td>
						<td align="left" dob_error="">
						</td>
					</tr>
					<tr>
						<td align="left">
						<strong>Gender: </strong>
						</td>
						<td>
							<?= $html_gender; ?>
						</td>
					</tr>
					<tr>
						<td>
						</td>
						<td align="left" gender_error="">
						</td>
					</tr>
					<tr>
						<td align="left">
						<strong>Location: </strong>
						</td>
						<td align="left">
						<input type="text" value="<?php echo $location?>" name="location" id="search-textLong">
						</td>
					</tr>
					<tr>
						<td>
						</td>
						<td align="left" location_error="">
						</td>
					</tr>
					<tr>
						<td height="10">
						</td>
					</tr>
					<tr>
						<td align="left" colspan="2">
							<h3>Update Password</h3>
						</td>
					</tr>
					<tr>
						<td colspan="2">
							<span class="error">*</span>Leave blank if you are not intending to change your password
						</td>
					</tr>
					<tr>
						<td align="left">
							<strong>New Password:</strong>
						</td>
						<td align="left">
							<input type="password" value="" name="password1">
						</td>
					</tr>
					<tr>
						<td>
						</td>
						<td align="left" password1_error="">
						</td>
					</tr>
					<tr>
						<td align="left">
							<strong>Password Confirmation:</strong>
						</td>
						<td align="left">
							<input type="password" value="" name="password2">
						</td>
					</tr>
					<tr>
						<td>
						</td>
						<td align="left" password2_error="">
						</td>
					</tr>
					<tr>
						<td height="10">
						</td>
					</tr>
					<tr>
						<td height="10">
						</td>
					</tr>
					<tr>
						<td colspan="2">
							<h3>Preferences</h3>
						</td>
					</tr>
					<tr>
						<td id="preferences_visit_location" colspan="2">
							<?php echo $visit_location_preferences_html?>
						</td>
					</tr>
					<tr>
						<td id="preferences_salary" colspan="2">
							<?php echo $salary_preferences_html?>
						</td>
					</tr>
					<tr>
					<td id="preferences_budget" colspan="2">
						<?php echo $budget_preferences_html?>
					</td>
					</tr>
					<tr>
						<td colspan="2">
							<strong>Subscription</strong>
						</td>
					</tr>
					<tr>
						<td align="left" colspan="2">
							<label>
								<table cellspacing="0" cellpadding="0">
									<tbody>
										
										<tr>
											<td>
												<input type="checkbox" <?php echo $subscribe=='y'?'checked="checked"':''?> name="subscribe" value="y" />
											</td>
											<td>Subscribe to TripZilla latest deals!</td>
										</tr>
									</tbody>
								</table>
							</label>
						</td>
					</tr>
					<tr>
						<td align="right" colspan="2">
							<button id="signup_button" name="post" class="sign_up red_button" type="submit">Update</button>
						</td>
					</tr>
				</tbody>
			</table>
			
			</form>
		
		</div>
	</div>
</div>
<?php 
if(isset($_SESSION['login']))
	unset($_SESSION['login']);
?>