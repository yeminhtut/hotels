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
					<div class="row d_container"> 
						<div class="span3 destinationdetail_container_left">
							<div class="agency_name"><?php echo $country ?></div>
							<?php /* if($country_description!='') { ?>
							<div class="agency_description">
							  <?php echo $country_description?>
							</div>
							<?php } */ ?>
							<div class="agency_name"><?php echo $total_packages ?> Travel Packages</div>
						</div>
						<div class="span3 destinationdetail_container_right">
							<?php if($country_image!='') { ?>
								<img src='<?php echo $country_image?>'/>
							<?php } ?>
						</div>
					</div>
				</div>
				<div class="row gamma">
					<div class="title_partition_contentwithpadding ">
						<h1><?php echo $city!=''?$city.', ':''?><?php echo $country?> Tour Packages</h1>
						<div id="search_sort">
							Sort by: 
							<a sort="asc" name="search_price" id="search_price_asc" href="javascript:;">Lowest Price</a>&nbsp;|&nbsp;  
							<a sort="desc" name="search_price" id="search_price_desc" href="javascript:;">Highest Price</a>&nbsp;|&nbsp;  
							<a sort="asc" name="search_days" id="search_days_asc" href="javascript:;">Shortest Tour</a>&nbsp;|&nbsp;  
							<a sort="desc" name="search_days" id="search_days_desc" href="javascript:;">Longest Tour</a>&nbsp;  
							<input type="hidden" name="tfdest" value="<?php echo $destinationID ?>"/>							
						</div>
					</div>
					
					<ul id="listing_result" class="result" >					
						<?=$make_html?>					
						<li style="text-align:center;"><?php echo $pagination?></li>
					</ul>
				</div>
				
				<!--Content end here-->
		</div>
	</div>
</div>
<script>
var pagination_base_url="<?php echo $pagination_base_url?>";
</script>
<style type="text/css">
	.photo_holder img{width:100px;height:100px;}
	#listing_result{margin-left:0px;}
	.desc>h1 {
		margin: 0 0 30px 0;		
		overflow: hidden;
		font-size: 14px;
		font-weight: bold;		
		overflow: hidden;
		white-space: nowrap;
		-o-text-overflow: ellipsis;
		-ms-text-overflow: ellipsis;
		text-overflow: ellipsis;
	}
	#listing_result li{list-style-type: none;}
	.thumb{width:120px; float:left;}
	.desc{width: 370px;float:left;margin-left: 5px;}
	.pkgs_right{margin-left:5px;text-align: center;width:100px;float:left;}	
</style>
<script type="text/javascript">
	$(document).ready(function(){
			/*start sorting*/
	  $("a[sort]").click(function(){	  	
    	var vsort=$(this).attr('sort');
	    var vby=$(this).attr('by');
	    var vtype=$(this).attr('type');
	    var sort_on, sort_by;
	    sort_by=$(this).attr('by');
	    if($(this).attr('sort')=='price')
	       sort_on='sort_by_price';    
	    else if($(this).attr('sort')=='days')
	      sort_on='sort_by_days';	    
	    $.ajax({
	      type: 'POST', 
	      url:'/ajax/sort_by/', 
	      data: {type: 'package', sort_on: sort_on, sort_by: sort_by}, 
	      success:function(data){
	        if(vtype=="package")
	          listing_result_package();
	        // else
	        //   listing_result_deal();
	        //   console.log(data);
	      }
	    });
  	});

	/*listing resutl*/
	function listing_result_package(){
	  var listing=$('#listing_result'); 
	  var tour_type=[], tour_theme=[], tour_agency=[];	  
	  var tour_type = $( "select.tour_type option:selected").val();	  
	  listing.html('<div class="tzloading"><img src="/img/tz_loading.gif"><label>Loading</label></div>');	  
	  $.ajax({
	    type:'POST', 
	    url:'/ajax/packages_refine_search', 
	    data: {	      
	      'tour_type[]':tour_type,	      
	      'type':'packages'
	    }, 
	    dataType: 'html', 
	    success:function(data){
	      listing.html('');
	      listing.html(data);
	    }
	  });
	}
})
</script>