<div role="main"> </div>
<!-- end of main-->

<div class="content_div">
	
	<div class="navbar_left_div">
		<?php include(VIEW_PATH."layouts/layout_leftsidebar.php");?>
	</div>
	<!-- Body Content-->
	<div class="container" style="position:relative;">
		<?php include(VIEW_PATH . 'layouts/layout_side_ads.php');?>
		<div class="row">
		  
		  <!--left side bar-->
			<div class="span4">
				<?php include(VIEW_PATH."layouts/layout_left_content.php");?>
			</div>
			<!-- End left side bar-->
			
			<!-- Content body-->
				
			<div class="span8" style="text-align: left">
				<?php
					
					$agency_description =  $company->description;
					
				  ?>
				  <div class="row gamma">
					  <div class="span3 agencydetail_container_left">
					  	<?php if($agency_image) { ?>
						  <div class="agency_photo">
						  	<?= $agency_image;  ?>
						  </div>
						  <?php } ?>
						  <div class="agency_name"><?php echo $company->display_name?></div>
						  <!-- <div class="agency_description">
								<?php echo $agency_description?>
						  </div> -->
						  <?php if($company->website1 != '') { ?>
						  <img id="agency_website_btn" src="/images/website.png" width="15px"/>&nbsp;&nbsp;
						  <small><b><a href="<?php echo $company->Website1?>" target="_blank" rel="nofollow" ><?php echo $company->website1?></a></b></small><br/>
						  <?php } ?>
						  
							<?php
							$microsite=new Microsite();
							$microsite->retrieve_one("cid=?", array($company->id));
							
							$enquiry_url="http://tripzilla.sg/directory/review/".$company->id."/".url_title($company->display_name);
							if($microsite->exists())
								$enquiry_url="http://tripzilla.sg/".$microsite->get('folder_name')."/enquire";
							?>
							
						  <div style="">
				        <table>
				          <tr>
				            <!--<td class="tour_enquire_button"><a href="/directory/enquire/<?php echo $company->id?>/<?php echo url_title($company->display_name)?>">Email Agency</a>&nbsp;&nbsp;</td>-->
										<?php if($enquiry_button=="enable"){ ?>
											<td class="tour_enquire_button"><a href="<?=$enquiry_url?>">Send Enquiry</a>&nbsp;&nbsp;</td>
										<?php }else{ ?>
										<td>&nbsp;</td>
										<?php } ?>
				            <td></td>
				            <td class="tour_call_button" phone style="display:none;"><?php echo $company->contact1?></td>
				          </tr>
				        </table>
				      </div>						  
						  
					  </div>
					  <div class="span3 agencydetail_container_right" style="margin-left:25px">
					  	<?php if($company->lat!='' && $company->lng!='') {
								$savedaddress = str_ireplace("'","",$company->address1.' '.$company->address2);
								$savedaddress = str_ireplace("&","and",$savedaddress);
								$savedaddress = str_ireplace("/","--",$savedaddress);
								$savedaddress = str_ireplace(" ","_",$savedaddress);
								$savedaddress = str_ireplace(",","",$savedaddress);
								?>
								<div id="map_canvas" <?=($company->Directory_Category_ID != 6 && ($company->lat!='' && $company->lng!=''))?'style="margin: 0 0 25px 3px;"':'style="display:none;"'?> >
								<?php if($company->Directory_Category_ID != 6) { ?>
								<a target="_blank" alt="Nearby <?=$company->address1.' '.$company->address2?>" href="http://www.nearby.sg/location/<?php echo $company->lat?>/<?php echo $company->lng?>/<?=$savedaddress?>"><img style="width:305px;height:250px;" src="http://maps.googleapis.com/maps/api/staticmap?center=<?php echo $company->lat?>,<?php echo $company->lng?>&zoom=15&size=305x250&markers=color:red%7Ccolor:red%7C<?php echo $company->lat?>,<?php echo $company->lng?>&sensor=false&key=AIzaSyBmWJgs7TnrlEDMN3h-Y9qUmGaFN-lKaVw"/></a>
								<?php } ?>
								</div>
							<?php } ?>
					  
						  <div class="agency_address">
							<b>Address:</b><br/>
							<div class="agency_description">
							  <?php echo $company->address1?> <br/><?php echo $company->address2?>
							</div>
						  </div>
					  </div>
				  </div>
				  
				 <div class="span8" style="text-align: left">
				<div class="title_partition_content ">
					<h4>SEARCH RESULT</h4>					
					<div class="search_sort">
			          Sort by:			         
			           <a sort="price" type="package" by="asc" href="javascript:void(0)">Lowest Price</a>&nbsp;|&nbsp; 
			           <a sort="price" type="package" by="desc" href="javascript:void(0)">Highest Price</a>&nbsp;|&nbsp; 
			           <a sort="days" type="package" by="asc" href="javascript:void(0)">Shortest Tour</a>&nbsp;|&nbsp; 
			           <a sort="days" type="package" by="desc" href="javascript:void(0)">Longest Tour</a>&nbsp;|&nbsp; 
			           <input type="hidden" name="tfcompany" value="<?php echo $company->id?>"/>		          
			        </div>
			        
				</div>				
				<ul id="listing_result" class="result" >					
					<?=$make_html?>					
					<li style="text-align:center;"><?php echo $pagination?></li></ul>						
				<!--Content end here-->
			</div>
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
	        }
	    });
  	});

	/*listing resutl*/
	function listing_result_package(){
	  var listing=$('#listing_result'); 
	  var tour_type=[], tour_theme=[], tour_agency=[];
	  var tour_type = $( "select.tour_type option:selected").val();	
	  var tfcompany = $('input[name=tfcompany]').val();	
	  listing.html('<div class="tzloading"><img src="/img/tz_loading.gif"><label>Loading</label></div>');	  
	  $.ajax({
	    type:'POST', 
	    url:'/ajax/packages_refine_search', 
	    data: {	      
	      'tour_type[]':tour_type,      
	      'tour_agency[]':tfcompany,
      	  'type':'directory'
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