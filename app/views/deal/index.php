<script src="/js/jquery-1.7.2.min.js"></script>
<script src="/js/jquery.masonry.min.js"></script>
<script src="/js/filter.js"></script>
<script type="text/javascript">
//$(document).ready(function() {
$(window).load(function() {
	// grid
	$('#DealBotBoxes').masonry({
		itemSelector: '.DealBox',
		columnWidth: 210,
		gutterWidth: 20
	});
});
</script>
<div role="main"> </div>
<!-- end of main-->

<div class="content_div">
	
	<div class="navbar_left_div">
		<?php include(VIEW_PATH.'layouts/layout_leftsidebar.php');?>
	</div>
  
	<div class="container" style="border:0px solid #000; background-color:#ddd;position:relative;"><!--Container-->
    <?php include(VIEW_PATH . 'layouts/layout_side_ads.php');?>
    <div id="DealContent"><!--DealContent-->
      <div id="DealTopBoxes">
        <div id="DTBoxes_Top">
          <div id="DTBoxes_Top_Content">
            <ul id="DTBoxes_Content">              
              <li style="width:430px;">
                <div id="header_h1"><h1><?php echo $TextHeaderOne ?></h1></div>
                <div id="header_powered"><a href="http://tripzilla.sg/travel/deals" target="_blank"><img src="/img/powered-by.jpg"></a></div>
              </li>
            </ul>
          </div>
        </div>
        <div id="DTBoxes_Bottom">
          <div style="float: left; width: 90px;">
            <font style="color:#FFFFFF;font-weight:bold;font-size:16px;">Show:</font>
          </div>
          
          <div style="float: left; width: auto; padding-right: 30px; position: relative;">    
            <a class="deal-dropdown-toggle" id="alldeals-toggle" style="text-decoration: none" href="javascript:void(0);">
              <span>All Deals</span> <b class="deal-caret"></b>
              <strong class="selected">
              <?php 
              switch($cbDealFilter) { 
              	case 'editors-picks':
              		echo 'Editor\'s Picks';
              		break;
                case 'travel-agencies-only':
              		echo 'Travel Agencies only';
              		break;
                case 'daily-deals-only':
              		echo 'Daily Deals only';
              		break;
              	case 'all-except-daily-deals';
              		echo 'All Except Daily Deals';
              		break;
              }
              ?>
              </strong>
            </a>
            
            <div class="deal-dropdown-menu" id="alldeals">
              <ul>
              	<li><a href="/package-deals" id="deal-types-all">All Deals</a></li>
                <li><a href="/package-deals/editors-picks" id="deal-types-all">Editor's Picks</a></li>
                <li><a href="/package-deals/travel-agencies-only" id="deal-types-all">Travel Agencies only</a></li>
                <li><a href="/package-deals/daily-deals-only" id="deal-types-all">Daily Deals only</a></li>
                <li><a href="/package-deals/all-except-daily-deals" id="deal-types-all">All Except Daily Deals</a></li>
              </ul>
            </div>
          </div>
          
          
          <div style="float: left; width: auto; padding-right: 30px; position: relative;">
            <a class="deal-dropdown-toggle" id="alldestinations-toggle" style="text-decoration: none" href="javascript:void(0);">
              <span>Destination</span> <b class="deal-caret"></b>
              <strong class="selected">
              <?php
              $dealFilter=array(
                    'editors-picks',
                    'travel-agencies-only',
                    'daily-deals-only',
                    'all-except-daily-deals'
                    );
              if(!in_array($cbDealFilter, $dealFilter)){echo $cbDealFilter;}
              ?>
              </strong>
            </a>
            <div id="destinations" class="deal-dropdown-menu" style="width: 450px;">
              <div style="float: left; width: 150px;">
                <ul>
                  <?php
                    $url = '/package-deals';
                  ?>
                  <li><a name="top" href="<?=$url?>" id="destinations-all">All Destinations</a></li>
                  <?php 
                    foreach($continents_arr as $cont) {
                    $url = '/package-deals/'.url_title($cont['name']);                    
                  ?>
                  <li><a href="<?=$url?>"><?=$cont['name']?> (<?=$cont['dealscount']?>)</a></li>
                  <?php } ?>
                </ul>
              </div>              
              <div style="float: left; width: 150px;">
                <ul>
                  <li><strong>Countries</strong></li>
                  <?php 
                  $ctr = 1;
                  foreach($countries_arr as $cnty) { 
                  $url = '/package-deals/'.url_title($cnty['name']);
                  ?>
                  <li <?=($ctr>10)?'class="cy-hidden-dest" style="display: none;"':''?>>
                  <a href="<?=$url?>"><?=$cnty['name']?> (<?=$cnty['dealscount']?>)</a>
                  </li>
                  <?php 
                  $ctr++;
                  }
                  ?>
                  <?php if(count($countries_arr) > 10) { ?>
                  <li>
                     <a id="cy-more-dest" href="javascript:void(0);">[ More ]</a> <a  style="display: none;" id="cy-top" href="#top">[ Top ]</a>
                  </li>
                  <?php } ?>
                </ul>
              </div>
              
              <div style="float: left; width: 150px;">
                <ul>
                  <li><strong>Cities</strong></li>
                  <?php echo $make_city_navigation_html; ?> 
                </ul>
              </div>
            </div>
          </div>
        </div>
      </div>
      
      <div id="DealBotBoxes"><!--DealBoxes-->
          <?php echo $make_html; ?>      
      </div><!--End DealBoxes-->
    </div><!--End DealContent-->
  </div><!--End Container-->
  
</div>
<script>
var pagination_base_url="<?php echo $pagination_base_url?>";
</script>