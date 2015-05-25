<div role="main"> </div>
<!-- end of main-->

<div class="content_div" onmouseover="hide_all()">
	
	<div class="navbar_left_div">
		<?php include(VIEW_PATH . 'layouts/layout_leftsidebar.php');?>
	</div>
	<!-- Body Content-->
	<div class="container">
		
		<div class="row">		  
		
			<h2>Shortlisted Packages</h2>
		
		  <?php 
		  if(isset($packages) && count($packages)) {
				$total_packages = count($packages);
			
			?>
			<table style="width: 100%; text-align: left;">
				<?php 
				$count = 1;
				foreach ($packages as $package) { 
				?>
				<tr id="shortlist<?php echo $package['PostingID']?>">
					<td width="120px"><div class="photo_holder">
					<a href="/tour/package/<?php echo $package['PostingID']?>/<?php echo url_title($package['Title'])?>">
						<img src="<?php echo $package['Image']?>" width="100px"/>
					</a>	
					</div></td>
				<td width="5px"></td>
				<td>
					<a href="/tour/package/<?php echo $package['PostingID']?>/<?php echo url_title($package['Title'])?>"><b><?php echo $package['Title']?></b></a><br/>
					<div class="travel_decription">
						<?php echo $package['Description']?>
					</div>
					<?php echo strtoupper(str_replace('_',' ',$package['Tour_Type'])); ?>
					<!-- <div class="travel_depart_decription">depart every Sunday:  Aug 5 till Dec 30</div> -->
					
				</td>
				<td width="5px"></td>
				<td width="100px">
					<?php if($package['Price']) { ?>
						<small>from</small><div class="travel_price"> <?php echo $package['Currency'] . ' ' . preg_replace('~\.0+$~','', number_format(sprintf('%.2f', $package['Price']), 2, '.', ',')); ?></div>
					<?php } else { ?>
						<small>from</small><div class="travel_price"> N/A</div>
					<?php } ?>
					<div class="travel_agency_name">
						<a href="/directory/review/<?php echo $package['CompanyID']?>/<?php echo url_title($package['CompanyName'])?>"><?php echo $package['CompanyName']?></a>
					</div>
					
					<img class="pkg_shortlist_btn" id="shortlist<?php echo $package['PostingID']?>" onClick="short_list(this)" posting_id="<?php echo $package['PostingID']?>" src="/img/bookmarked.png" width="15px" alt="Remove from Shortlist" title="Remove from Shortlist" style="cursor:pointer"/>&nbsp;
					<a class="pkg_btn_lnks" href="/tour/enquire/<?php echo $package['PostingID']?>/<?php echo url_title($package['Title'])?>">
						<img id="pkg_email_btn" src="/img/email.png" width="15px"  alt="Email Agency"  title="Email Agency" style="cursor:pointer"/>
					</a>
					<a href="/tour/package/<?php echo $package['PostingID']?>/<?php echo url_title($package['Title'])?>">
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
				
			</table>
			<?php } else { ?>
				<p>You have no shortlisted packages.</p>
			<?php } ?>
		</div>
	</div>
</div>