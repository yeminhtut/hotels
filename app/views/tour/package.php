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
				<div class="row d_container"> 
					<div class="span3 packagedetail_container_left">
						<h1 style="line-height: 110%;" class="agency_name"><?=$package->get('display_title')?></h1>
						
						<p class="angecy_price_range">From <?=$package->get('price')?$package->get('price'):'N/A'?></p>
						
						<img src='<?=$thumb_url?>'/>
					</div>
					<div class="span3 packagedetail_container_right">
						<?php if($company_logo) { ?>
						<div class="agency_photo">
							<a href="/directory/review/<?=$company->get('id')?>/<?=makeslug($company->get('display_name'))?>">
								<img src='<?php echo $company_logo?>'/>
							</a>
						</div>
						<?php } ?>
						<div class="agency_name"><a href="/directory/review/<?php echo $company_id?>/<?php echo url_title($company_name)?>"><?php echo $company_name; ?></a></div>
						<?php if($custom_agency) { ?>
						<!-- CSS dirty hack -->
						<style>
						.agency_name a h2{
						  font-size:14pt;
						  font-weight:bold;
						  line-height: 16pt;
						  margin-bottom:8px;
						}
						.agency_name a:hover h2{
						  text-decoration: underline;
						}
						.agency_name a{
						  font-size:12px;
						  font-weight:bold;
						  margin-bottom:8px;
							text-decoration: none;
						}
						.agency_name a:hover{
							text-decoration: none;
						}
						</style>						
						<?php } else { ?>
						<small><?=$company->get('address')!=''? implode('<br/>', $company->get('address')):''?></small>
						<br />
						<?php if(strlen($company->get('website1'))) { ?>
						<img id="agency_website_btn" src="/images/website.png" width="15px"/>&nbsp;&nbsp;
						
						<small><b><a href="<?php echo $company->get('website1')?>" target="_blank" rel="nofollow"><?php echo $company->get('website1')?></a></b></small>
						<?php } ?>
						
						<?php } ?>
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
					
					<?php if($expired) { ?>
						<br />
		        <div style="background-color: #C1282D;
						    border-radius: 5px 5px 5px 5px;
						    color: #FFFFFF;
						    font-size: 12px;
						    font-weight: bold;
						    margin: auto;
						    padding: 10px;
						    text-align: center;
						    width: 300px;">
		          This Tour Package Is No Longer Available <br /> 
		          <a style="color: #FFF" href="/travel/packages<?php echo $similar_location?"/$similar_location":''?>">View Other Similar Tour Packages Here</a>
		        </div>
		        <br />
		      <?php } ?>
					<?php //var_dump($package); ?>
					<!--
					<div class="title_partition ">
						  <h4>Highlights</h4>
					  </div>
					<?php// echo $package->get('description'); ?>					
					<br />
					<br />-->
					
					<div class="title_partition ">
						<h4>Package Description</h4>
					</div>					
					<?php echo $html_description; ?>					
					<?php if($html_pdf) { ?>
						<br />
						<br />
						<?php echo $html_pdf; ?>
					
					<?php } ?>
					
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