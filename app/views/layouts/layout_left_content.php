<?php 
$available_max_days = Posting::retrieve_max_days();
$available_min_days = Posting::retrieve_min_days();
$min_days = isset($_SESSION['search_min_days'])?$_SESSION['search_min_days']:$available_min_days;
$max_days = isset($_SESSION['search_max_days'])?$_SESSION['search_max_days']:$available_max_days;

$available_max_price = Posting::retrieve_max_price();
$available_min_price = Posting::retrieve_min_price();
$min_price = isset($_SESSION['search_min_price'])?$_SESSION['search_min_price']:$available_min_price;
$max_price = isset($_SESSION['search_max_price'])?$_SESSION['search_max_price']:$available_max_price;
?>
<?php 	
	if (isset($_SESSION['days'])) {$min_max_days = explode('-', $_SESSION['days']);$min_days = $min_max_days[0];$max_days = $min_max_days[1];}			
	if (isset($_SESSION['prices'])) {$min_max_prices = explode('-', $_SESSION['prices']);$min_price = $min_max_prices[0];$max_price = $min_max_prices[1];}			
?>
<link rel="stylesheet" href="/css/jslider.css" type="text/css">
<link rel="stylesheet" href="/css/jslider.blue.css" type="text/css">
<link rel="stylesheet" href="/css/jslider.plastic.css" type="text/css">
<link rel="stylesheet" href="/css/jslider.round.css" type="text/css">
<link rel="stylesheet" href="/css/jslider.round.plastic.css" type="text/css">

<div id="searchbox_container">
	<h1>Search Singapore Tour Packages</h1>
	<div id="searchbox_subcontainer">
		
		<form method="POST" action="/travel/search">
		
		<div class="row_form">
			<label class="title_white">Where</label>
			<input class="searchbox_location" id="id_username" type="text" placeholder="" name="where" value="<?php echo (isset($_SESSION['where']))?$_SESSION['where']:''?>" />
			<input type="hidden" name="to_city" id="to_city" value="<?php echo (isset($_SESSION['to_city']))?$_SESSION['to_city']:''?>" />
			<input type="hidden" name="to_country" id="to_country" value="<?php echo (isset($_SESSION['to_country']))?$_SESSION['to_country']:''?>" />
		</div>
		  
		<div class="row_form">
			<label  class="title_white">When</label>
		
			<select name="departure" class="month">
            <?=$search_filter_month_of_travel?>
         	</select>
			
			
			
		</div>		
		<div class="row_form" style="width:240px">			
			<div class="" style="height: 72px;">
				<label class="label_title" for="amount1" style="color:#41aad3">Days:</label>			
				<div class="clearfix"></div>
				<div class="layout-slider">
					<input style="display: none;" id="search_min_max_days_slider" type="slider" name="search_min_max_days_slider" value="<?php echo $min_days?>;
					<?php echo $max_days?>" />
					<input type="hidden" name="available_max_days" value="<?php echo $available_max_days;?>" />
				</div>
			</div>
		</div>
		
		<div class="row_form" style="width:240px">
			
			<div class="" style="height: 72px;">
				<label class="label_title" for="amount1" style="width: 90px; color:#41aad3">Budget (SGD):</label>			
				<div class="clearfix"></div>
				<div class="layout-slider">
					<input style="display: none;" id="search_min_max_price_slider" type="slider" name="search_min_max_price_slider" value="<?php echo $min_price?>;<?php echo $max_price?>" />
					<input type="hidden" name="available_max_price" value="<?php echo $available_max_price;?>" />
				</div>
			</div>
		</div>
		
		<div class="clearfix">&nbsp;</div>
		
		<div class="row_form">
			<label  class="title_white">Type</label>
			<select class="tour_type" name="search_tour_type">
				<option value="-1">All</option>
				<?php foreach ($GLOBALS['tour_types'] as $k=>$val) { ?>
					<option value="<?php echo $k?>" <?php echo (isset($_SESSION['package_type']) && $_SESSION['package_type'] == $k) ? 'selected' : ''?>><?php echo $val?></option>
				<?php } ?>
			</select>
			
		</div>
		
		<div class="row_form">
		  <div class="search_button ">		  	
			<button class="blue_button" type="submit">Search</button>
		  </div>
					
		</div>
		
		</form>
		<!--<button type="button" id="reset" class="btn btn-default btn-xs">Reset</button>-->
		<a id="reset" style="float:right">Reset</a>
		<div class="clear"></div>
	</div>
</div>

<br />
<a href='http://www.agoda.com/?cid=1583931' rel='nofollow'>
<img src='http://img.agoda.net/banners/agoda.com/106/9395/bangkok_300x239.jpg'/></a>
<br />


<?php if(isset($destination_cities) && count($destination_cities)) { 
	$destination_cities_count = count($destination_cities);	
?>
<!-- <?php echo $destination_cities_count?> -->
<br/>
<div id="searchbox_container">
	<h1>Filter Search Results</h1>
	<div id="searchbox_subcontainer">
		  
		<div class="row_form">
			<label  class="title_white">City</label>
			
			<select class="month" id="destination_city_filter" name="destination_city_filter">
				<?php if($destination_cities_count > 1) { ?>
					<option value="/<?php echo $destination_continent_filter?>/<?php echo $destination_country_filter?>" >All</option>
				<?php } ?>
				<?php foreach ($destination_cities as $destination_city) { ?>
					<option value="/<?php echo $destination_continent_filter?>/<?php echo $destination_country_filter?>/<?php echo url_title($destination_city['name'])?>" <?php echo (isset($destination_city_filter) && $destination_city_filter==url_title($destination_city['name']))?'selected="selected"':'' ?> ><?php echo $destination_city['name']?></option>
				<?php } ?>
			</select>
			
		</div>
		
		<?php if((isset($destination_agencies_filter) && count($destination_agencies_filter)) 
				|| (isset($destination_feat_agencies_filter) && count($destination_feat_agencies_filter)) ) { ?>
		<div class="row_form">
			<label  class="title_white" style="width: 100px;">Travel Agencies</label>
			<div class="clearfix"></div>
			<div class="destination_agencies_filter">
				<?php if(isset($destination_feat_agencies_filter) && count($destination_feat_agencies_filter)) { ?>
					<!-- Display Featured First -->
					<?php foreach($destination_feat_agencies_filter as $destination_agency_id => $destination_agency_name) { ?>
						<label>
							<input onclick="update_travel_agents()" type="checkbox" value="<?php echo $destination_agency_id?>" name="destination_agencies_filter" <?php echo (isset($_SESSION['travel_agents']) && in_array($destination_agency_id, explode(',',$_SESSION['travel_agents'])))?'echo checked':''?> /> <?php echo $destination_agency_name?>
						</label>
						<br />
					<?php } ?>
					
					<!-- Non-featured - Hidden -->
				  <?php if(isset($destination_agencies_filter) && count($destination_agencies_filter)) { ?>
				  <div id="destination_agencies_filter_see_more">
						<?php foreach($destination_agencies_filter as $destination_agency_id => $destination_agency_name) { ?>
							<label>
								<input onclick="update_travel_agents()" type="checkbox" value="<?php echo $destination_agency_id?>" name="destination_agencies_filter" <?php echo (isset($_SESSION['travel_agents']) && in_array($destination_agency_id, explode(',',$_SESSION['travel_agents'])))?'echo checked':''?> /> <?php echo $destination_agency_name?>
							</label>
							<br />
						<?php } ?>
					</div>
					<?php } ?>
					
					<div class="clearfix"></div>
					<div style="float: right; width: 65px;">
						<a href="javascript:toggle_destination_agencies_filter()" id="toggle_destination_agencies_filter">See More</a>
					</div>
				
				<?php } else { ?>
				  <!-- If no featured -->
				  <?php if(isset($destination_agencies_filter) && count($destination_agencies_filter)) { ?>
						<?php foreach($destination_agencies_filter as $destination_agency_id => $destination_agency_name) { ?>
							<label>
								<input onclick="update_travel_agents()" type="checkbox" value="<?php echo $destination_agency_id?>" name="destination_agencies_filter" <?php echo (isset($_SESSION['travel_agents']) && in_array($destination_agency_id, explode(',',$_SESSION['travel_agents'])))?'echo checked':''?> /> <?php echo $destination_agency_name?>
							</label>
							<br />
						<?php } ?>
					<?php } ?>
				
				<?php } ?>
				
			</div>
			<div class="clearfix"></div>	
		</div>
		<?php } ?>
		
	</div>
</div>
<?php } ?>

<br/>
<div id="featurebox_container">
	<div id="featurebox_subcontainer">
		<div class="title_partition ">
			<h4>Featured Tour Packages</h4>
		</div>
		<table>
			<?php 
			$limit = 5;
			$posting_obj = new Posting();
			
			$destination = FALSE;
			$p = explode('/',$_SERVER['REQUEST_URI']);
			if(isset($p[1]) && $p[1]=='destination')
			{
				if(isset($p[3])) {
					$destination = $p[2] . '|' . $p[3];
				} elseif(isset($p[2])) {
					$destination = $p[2];
				}
			} elseif(isset($p[1]) && $p[1]=='travel' && $p[2]=='packages' ) {
				if(isset($p[4])) {
					$destination = $p[3] . '|' . $p[4];
				} elseif(isset($p[3])) {
					$destination = $p[3];
				}
			} elseif(isset($p[1]) && $p[1]=='tour' && $p[2]=='package' ) {
					if($posting_city_name != '')
						$destination = $posting_country_name . '|' . $posting_city_name;
					else
						$destination = $posting_country_name;
			} elseif(isset($_SESSION['country_packages_listing']) 
				&& $_SESSION['country_packages_listing']===TRUE) {
				if(isset($p[3])) {
					$destination = $p[2] . '|' . $p[3];
				} elseif(isset($p[2])) {
					$destination = $p[2];
				}
				unset($_SESSION['country_packages_listing']);
			}
			
			$result = Posting::retrieve_featured($posting_obj, $limit, $destination);
			
			// if feature is less than limit
			if(count($result) < $limit) {
				// try for country
				$temp = explode('|', $destination);
				$destination = $temp[0];
				$result2 = Posting::retrieve_featured($posting_obj, $limit, $destination);
				if(is_array($result))
					$result = array_merge($result, $result2);
				else
					$result = $result2;
			}
			
			if(count($result) < $limit+$limit) { // double limit to cover duplicates
				$result2 = Posting::retrieve_featured($posting_obj, $limit);
				if(is_array($result))
					$result = array_merge($result, $result2);
				else
					$result = $result2;
			}
			
			$displayed = array();
			$count = 0;
			foreach ($result as $k=>$package) {
				if($count >= $limit) { break; }
				
				if(in_array($package['PostingID'], $displayed)) {
					continue;
				}
				$displayed[] = $package['PostingID'];

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
					$country_obj = Country::retrieve_random_posting_country_and_city($reference_id);
					$country_id = $country_obj['CountryID'];
					$file = new File();
					$file = File::retrieve_random_file($country_id,'country');
					if($file->exists() && $file!='')
						$posting_image = TN_PATH.'square/100x100/'.$file->get('FileID').'.'.$file->get('Extension');
					else
						$posting_image = TN_PATH.'square/100x100/no_image.jpg';
				}
				?>
				<tr>
					<td><div class="photo_holder">
						<a <?php echo $destination?'target="_blank"':''?> href="/tour/package/<?php echo $package['PostingID']?>/<?php echo url_title($package['Title'])?>">
							<img src="<?php echo $posting_image; ?>" width="100px"/>
						</a>
						</div></td>
					<td width="5px"></td>
					<td>
						<a <?php echo $destination?'target="_blank"':''?> href="/tour/package/<?php echo $package['PostingID']?>/<?php echo url_title($package['Title'])?>"><b><?php echo $package['Title']; ?></b></a><br/>
						<div class="travel_agency_decription">by <a href="/directory/review/<?php echo $package['CompanyID']?>/<?php echo url_title($package['Company_Name'])?>"><?php echo $package['Company_Name']; ?></a></div>
						<?php echo strtoupper(str_replace('_',' ',$package['Tour_Type'])); ?>
						<!-- <div class="travel_depart_decription">depart every Sunday</div> -->
						<?php if($package['Price']) { ?>
						<div class="travel_price"><?php echo $package['Currency'] . ' ' . preg_replace('~\.0+$~','', number_format(sprintf('%.2f', $package['Price']), 2, '.', ',')); ?></div>
						<?php } ?>
					</td>
				</tr>
				<?php if($count < $limit-1) { ?>
					<tr>
						<td colspan="3"><div class="title_partition" style="margin-top:5px;margin-bottom:5px"></div></td>
					</tr>
					<?php				
				}
				$count++;
			}
			?>
		</table>
	</div>
</div>
<script type="text/javascript">
	$(document).ready(function(){
		$('#reset').click(function(){
	    $.ajax({
	      type:'POST', 
	      url:'/ajax/search_reset', 
	      success:function(){
	        window.location='/travel/packages';
	      }
	    });
	  });
	});
</script>