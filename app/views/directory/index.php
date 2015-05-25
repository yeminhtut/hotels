<div role="main"> </div>
<!-- end of main-->

<div class="content_div">
	
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

			  <div class="row gamma">
              <div class="row c_container">
					  <div class="title_partition ">
						  <h2>Featured Agencies</h2>
					  </div>
					  <?=$featured_companies?>					  
					</div>
              
              </div>
			  
			  <div class="row gamma">
				  <div class="title_partition_content ">
					  <h1>List of Travel Agencies in Singapore</h1>
				  </div>
				  
				  <div style="background-color:#EEE;text-align:center;padding:2px;">
						<div style="display:inline;padding:5px;">
							<a href="/directory">All</a> 
						</div>
						<div style="display:inline;padding:5px;">
							<a href="/directory/_HEX_">#</a> 
						</div>
						<?php $letters = range('a', 'z');
						foreach($letters as $letter) {
						?>
						<div style="display:inline;padding:4px;">
							<a href="/directory/<?=$letter?>"><?=$letter?></a>
						</div>
						<?php } ?>
					</div>
					<br />
				  
				  <?=$make_html_nonfeatured_company?>		
				  
				  <div style="text-align: center;">
				  	<?php echo $pagination?>
				  </div>
			  </div>
				<!--Content end here-->
                
                
                </div>
			</div>
		</div>
	</div>
</div>
<style type="text/css">
	.agency_main {
background-color: #fff;
float: left;
height: 150px;
margin-bottom: 10px;
margin-right: 10px;
}
</style>