<ul class="hotel-list">
<li class="hotel-row" data-price='89'>
    <div class="col-lg-4 col-md-4 col-sm-4" style="padding-left:0px;">
	     <div class="img_list">
	        <a href=""><img width="180" height="120" src="https://s3-ap-southeast-1.amazonaws.com/zumata/assets/hotels/2.0/9490efc9-51db-44ba-583c-f5f53cb4fc4b/images/1.jpg" alt=""></a>
	    </div>
    </div>   
   <div class="col-lg-6 col-md-6 col-sm-6">
      <div class="rooms_list_desc">
         <h3 class="link-title">Sun Inns Hotel Sunway City Ipoh</h3>
      </div>
   </div>
   <div class="col-lg-2 col-md-2 col-sm-2">
      <div class="price_list">
         <div>
            <sup>$</sup>89<small>*Pax/Per night</small>
            <p>
               <a href="/hotels/property/detail/9490efc9-51db-44ba-583c-f5f53cb4fc4b/sun-inns-hotel-sunway-city-ipoh/22-07-2015/23-07-2015/1/1" target="_blank" class="btn green-btn">Details</a>
            </p>
         </div>
      </div>
   </div>
   <div class="clear"></div>
</li>

<li class="hotel-row" data-price='100'>
    <div class="col-lg-4 col-md-4 col-sm-4" style="padding-left:0px;">
	     <div class="img_list">
	        <a href=""><img width="180" height="120" src="https://s3-ap-southeast-1.amazonaws.com/zumata/assets/hotels/2.0/9490efc9-51db-44ba-583c-f5f53cb4fc4b/images/1.jpg" alt=""></a>
	    </div>
    </div>   
   <div class="col-lg-6 col-md-6 col-sm-6">
      <div class="rooms_list_desc">
         <h3 class="link-title">Sun Inns Hotel Sunway City Ipoh</h3>
      </div>
   </div>
   <div class="col-lg-2 col-md-2 col-sm-2">
      <div class="price_list">
         <div>
            <sup>$</sup>100<small>*Pax/Per night</small>
            <p>
               <a href="/hotels/property/detail/9490efc9-51db-44ba-583c-f5f53cb4fc4b/sun-inns-hotel-sunway-city-ipoh/22-07-2015/23-07-2015/1/1" target="_blank" class="btn green-btn">Details</a>
            </p>
         </div>
      </div>
   </div>
   <div class="clear"></div>
</li>

<li class="hotel-row" data-price='50'>
    <div class="col-lg-4 col-md-4 col-sm-4" style="padding-left:0px;">
	     <div class="img_list">
	        <a href=""><img width="180" height="120" src="https://s3-ap-southeast-1.amazonaws.com/zumata/assets/hotels/2.0/9490efc9-51db-44ba-583c-f5f53cb4fc4b/images/1.jpg" alt=""></a>
	    </div>
    </div>   
   <div class="col-lg-6 col-md-6 col-sm-6">
      <div class="rooms_list_desc">
         <h3 class="link-title">Sun Inns Hotel Sunway City Ipoh</h3>
      </div>
   </div>
   <div class="col-lg-2 col-md-2 col-sm-2">
      <div class="price_list">
         <div>
            <sup>$</sup>50<small>*Pax/Per night</small>
            <p>
               <a href="/hotels/property/detail/9490efc9-51db-44ba-583c-f5f53cb4fc4b/sun-inns-hotel-sunway-city-ipoh/22-07-2015/23-07-2015/1/1" target="_blank" class="btn green-btn">Details</a>
            </p>
         </div>
      </div>
   </div>
   <div class="clear"></div>
</li>
</ul>
<style type="text/css">
.hotel-row{list-style-type: none;}
.clear{clear:both;}
.price_list {
  display: table;
  font-size: 38px;
  color: #e74c3c;
  width: 100%;
  margin-left: -15px;
}

</style>
<hr/>
<a href="" id="price_low">sort by lowest price</a>
<a href="" id="price_high">sort by highest price</a>
<script type="text/javascript">
$(document).ready(function(){
	$('#price_low').click(function(){
		var $wrapper = $('.hotel-list');
		$wrapper.find('.hotel-row').sort(function (a, b) {
		    return +a.getAttribute('data-price') - +b.getAttribute('data-price');
		})
		.appendTo( $wrapper )
	});

	$('#price_high').click(function(){
		var $wrapper = $('.hotel-list');
		$wrapper.find('.hotel-row').sort(function (a, b) {
		    return +b.getAttribute('data-price') - +a.getAttribute('data-price');
		})
		.appendTo( $wrapper )
	});

});
</script>