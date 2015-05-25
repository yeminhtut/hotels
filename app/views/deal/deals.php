<div role="main"> </div>
<!-- end of main-->

<div class="content_div" onmouseover="hide_all()">
	
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
				<div class="title_partition_content ">
					<h4>SEARCH RESULT</h4>
					<div id="search_sort">
						Sort by: 
						<a sort="asc" name="search_price" id="search_price_asc" href="javascript:;">Lowest Price</a>&nbsp;|&nbsp;  
						<a sort="desc" name="search_price" id="search_price_desc" href="javascript:;">Highest Price</a>&nbsp;|&nbsp;  
						<a sort="asc" name="search_days" id="search_days_asc" href="javascript:;">Shortest Tour</a>&nbsp;|&nbsp;  
						<a sort="desc" name="search_days" id="search_days_desc" href="javascript:;">Longest Tour</a>&nbsp;  
					</div>
				</div>
				<?php 
				$total_packages = count($packages);
				if($total_packages > 0) { 
				?>
				<table style="width:100%" id="search_result">
					<?php 
					$count = 1;
					foreach ($packages as $package) { 
						$reference_type='posting';
						$reference_id=$package['PostingID'];
						
						if($package['Image_Type']=='custom')
						{
							$file = new File();
							$file = File::retrieve_random_file($reference_id,$reference_type);
						
							if($file!='' && $file->exists())
								$posting_image = TN_PATH.'square/100x100/'.$file->get('FileID').'.'.$file->get('Extension');
							else
								$posting_image = TN_PATH.'square/100x100/no_image.jpg';
						}
						else
						{
							$country = Country::retrieve_random_posting_country_and_city($reference_id);
							$country_id = $country['CountryID'];
							$file = new File();
							$file = File::retrieve_random_file($country_id,'country');
							if($file!='' && $file->exists())
								$posting_image = TN_PATH.'square/100x100/'.$file->get('FileID').'.'.$file->get('Extension');
							else
								$posting_image = TN_PATH.'square/100x100/no_image.jpg';
						}				

						$short_description =  $package['Description'];
						if(strlen($short_description) > 160) {
							$short_description = substr($short_description, 0, 160).'...';
						}
						
						$company_result = Company::retrieve_company_by_posting_id($package['PostingID']);
						$company = new Company($company_result['CompanyID']);
					?>
					<tr>
						<td width="120px"><div class="photo_holder">
							<a href="/tour/package/<?php echo $package['PostingID']?>/<?php echo url_title($package['Title'])?>">
								<img src="<?php echo $posting_image?>" width="100px"/>
							</a>	
							</div></td>
						<td width="5px"></td>
						<td>
							<a href="/tour/package/<?php echo $package['PostingID']?>/<?php echo url_title($package['Title'])?>"><b><?php echo $package['Title']?></b></a><br/>
							<div class="travel_decription">
								<?php echo $short_description?>
							</div>
							<?php echo strtoupper(str_replace('_',' ',$package['Tour_Type'])); ?>
							<!-- <div class="travel_depart_decription">depart every Sunday:  Aug 5 till Dec 30</div> -->
							
						</td>
						<td width="5px"></td>
						<td width="100px">
							<?php if($package['Price']) { ?>
								<small>from</small><div class="travel_price"> <?php echo $package['Currency'] . ' ' . number_format($package['Price']); ?></div>
							<?php } else { ?>
								<small>from</small><div class="travel_price"> N/A</div>
							<?php } ?>
							<div class="travel_agency_name">
								<a href="/directory/review/<?php echo $company->CompanyID?>/<?php echo url_title($company->Name)?>"><?php echo $company->Name?></a>
							</div>
							
							<img class="pkg_shortlist_btn" id="shortlist<?php echo $package['PostingID']?>" onClick="short_list(this)" posting_id="<?php echo $package['PostingID']?>" src="/img/bookmark.png" width="15px" alt="Shortlist" title="Shortlist" style="cursor:pointer"/>&nbsp;
							<a class="pkg_btn_lnks" href="/tour/enquire/<?php echo $package['PostingID']?>/<?php echo url_title($package['Title'])?>">
								<img id="pkg_email_btn" src="/img/email.png" width="15px"  alt="Email Agency"  title="Email Agency" style="cursor:pointer"/>
							</a>
							<a class="pkg_btn_lnks" href="/tour/package/<?php echo $package['PostingID']?>/<?php echo url_title($package['Title'])?>">
								<img id="pkg_view_btn" src="/img/view.png" width="20px"  alt="View Itinerary"  title="View Itinerary" style="cursor:pointer"/>
							</a>
						</td>
					</tr>
					<?php if ($count < $total_packages) { ?>
					<tr>
						<td colspan="5"><div class="title_partition" style="margin-top:10px;margin-bottom:10px"></div></td>
					</tr>
					<?php } ?>
					
					<?php 
						$count++;
					} ?>
					
					<tr>
						<td colspan="5" align="center">
							<?php echo $pagination?>
						</td>
					</tr>
						
				</table>
				<?php } else { ?>
					<p>We are not about to find any package that meets your search criteria. <br> You may want to refine your search and try again.</p>
				<?php } ?>
				<!--Content end here-->
			</div>
		</div>
	</div>
</div>
<script>
var pagination_base_url="<?php echo $pagination_base_url?>";
</script>