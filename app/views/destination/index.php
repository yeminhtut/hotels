<?php// include('C:\xampp\htdocs\hotels\web\caching.php') ?>
<div class="row" id="avaliable-list">
  <div id="image" style="background:#FFF;height:400px;width:100%;text-align:center;">
    <img src="http://localhost/hotels/web/img/ajax-loader.gif" height="14px;" width="256px;" style="margin-top:200px;">
  </div>
</div>
<div class="alert alert-success" role="alert" id="results-bar" style="margin-top:20px;"></div>

<div id="sorting_select">
<select class="form-control" id="sort_by">
  <option value="best_deals">Best Deals</option>
  <option value="price_lth">Price (Low to High)</option>
  <option value="price_htl">Price (High to Low)</option>
</select>
</div>
<div class="clear"></div>
<ul class="hotel-list" style="display:none;"></ul>
<div id="status" style="display:none;"></div>
<script type="text/javascript" src="http://localhost/hotels/web/js/hotel-listing.js"></script>
<script type="text/javascript">
$(document).ready(function(){
    load_select(0);    
});
<?= $footer_script; ?>
</script>
<style type="text/css">
#rating{
  margin:10px 0px 10px 0px;
}
.amenities{text-transform: capitalize;margin-left:6px;}
.price_td{text-align: right;}
.tab-list .glyphicon{margin-left:4px;}
.tab-details-item{margin-right: 10px;}
.tab-content{display: none;}
.hotel_content{margin-bottom: 10px;}
.collapse-expand{margin-right: 10px;}
.best_deal{  position: absolute;
  background-color: #da4453;
  color: #FFF;
  padding: 4px;
  bottom: 0px;
  padding-right: 10px;}
#sorting_select{float: right;margin-bottom: 20px;display: none;}
.ori_price{display: block;text-decoration: line-through;}
#results-bar{display: none;}
.price-title h3{font-size: 20px;}
.price-title span{font-size: 16px;}
.thumb{border-radius: 6px}.link-title{font-size: 20px;color: #4b4b4c;font-weight: bold;margin-top:0px;}.hotel_address{color:#898989}#avaliable-list{margin: 0px;margin-top:-4px}.price_list{display: table;font-size: 22px;color: #e74c3c;width: 100%;margin-left: -15px}.price_list small{font-size: 10px}
</style>
 