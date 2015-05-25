<div role="main"> </div>
<!-- end of main-->

<div class="content_div" onmouseover="hide_all()">
	
	<div class="navbar_left_div">
	  <?php include(VIEW_PATH.'layouts/layout_leftsidebar.php');?>
	</div>
	<!-- Body Content-->
	<div class="container" style="position:relative;">
		<?php include(VIEW_PATH . 'layouts/layout_side_ads.php');?>
		
		<div class="row">
		  
		  <!--left side bar-->
			<div class="span4">
				<?php include(VIEW_PATH.'layouts/layout_left_content.php');?>
			</div>
			<!-- End left side bar-->
			
			<!-- Content body-->
				
			<div class="span8" style="text-align: left">
	
				<!--Content start here-->
				<div class="row gamma">
				  <div class="row c_container">
					  <div class="title_partition ">
						  <h4>All Travel Destinations</h4>
					  </div>
					  <?= $make_html; ?>
					</div>
				</div>
			</div>
			<!--Content end here-->
		</div>
	</div>
</div>
<style type="text/css">
.continent img{
	max-height: 110px;
}
</style>