<script type="text/javascript">var switchTo5x=true;</script>
<script type="text/javascript" src="http://w.sharethis.com/button/buttons.js"></script>
<script type="text/javascript">stLight.options({publisher: "59c869dc-0556-4583-a360-c51fa316b859",onhover: false});</script>
<div role="main"> </div>
<!-- end of main-->

<div class="content_div">
	
	<div class="navbar_left_div">
		<?php include(VIEW_PATH.'layouts/layout_leftsidebar.php');?>
	</div>
	<!-- Body Content-->
	<div class="container">
		
		<div class="row">
		  
		  <!--left side bar-->
			<div class="span4">
				<?php include(VIEW_PATH.'layouts/layout_left_content.php');?>
			</div>
			<!-- End left side bar-->
			
			<!-- Content body-->
				
			<div class="span8" style="text-align: left">

			<div class="row gamma">
				<div class="row d_container"> 
					<div class="span3 packagedetail_container_left">
						<h1 style="line-height: 110%;" class="agency_name"><?php echo $title; ?></h1>
						<?php if($price) { ?>
						<p class="angecy_price_range">From <?php echo $price; ?></p>
						<?php } ?>
						<img src='<?php echo $TD_image ?>'/>
					</div>
					<div class="span3 packagedetail_container_right">
						<?php if($TA_logo) { ?>
						<div class="agency_photo">
							<a href="/directory/review/<?php echo $company->get('CompanyID')?>/<?php echo url_title($company_name)?>">
								<img src='<?php echo $TA_logo?>'/>
							</a>
						</div>
						<?php } ?>
						<div class="agency_name"><a href="/directory/review/<?php echo $company_id?>/<?php echo url_title($company_name)?>"><?php echo $company_name; ?></a></div>
					</div>
					
					<div style="clear: both;"></div>
					<div style="padding-bottom: 10px;">
            Share With Friends: 
            <span class='st_email' displayText='Email'></span>
	          <span class='st_facebook' displayText='Facebook'></span>
	          <span class='st_twitter' displayText='Tweet'></span>           
          </div>
				</div>
				
			</div>
			
			<div class="row gamma">
				<div class="row package_container"> 
					
					<?php echo $enquire_button?>
				
					<?php if(strlen($short_description)) { ?>
					<div class="title_partition ">
						  <h4>Highlights</h4>
					  </div>
					<?php echo $short_description; ?>
					
					<br />
					<br />
					<?php } ?>
					<div class="title_partition ">
						<h4>Package Description</h4>
					</div>
					
					<?php echo $description; ?>
					
					<?php echo $enquire_button?>
					<div style="float: right; width: 72px;">
						<a href="#top">Back to top</a>
					</div>
					
				</div>
				
			</div>
				<!--Content end here-->
			</div>
		</div>
	</div>
</div>