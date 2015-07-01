<div class="row" style="margin-top:50px;margin-bottom:50px;">
	<div id="property_facts_wrapper">
		<div id="property_img">
			<img src="https://s3-ap-southeast-1.amazonaws.com/zumata/assets/hotels/2.0/ee1029c1-ad51-4c77-7410-35a908c8632a/images/25.jpg">
		</div>
		<div id="property_facts">
			<?php echo "<p>".$property->address."</p>";?>
		</div>
		<div class="clear"></div>
	</div>
	
</div>

<div class="row">
	 <div class='tabs tabs_default'>
              <ul class='horizontal'>
                <li><a href="#tab-1">Rooms</a></li>
                <li><a href="#tab-2">Details</a></li>
                <li><a href="#tab-3">Map</a></li>
              </ul>
              <div id='tab-1'><span><?= $hotel_rooms ?></span></div>
              <div id='tab-2'>
              </div>
              <div id='tab-3'><div id="map-canvas"></div></div>
            </div>	
</div>
<style type="text/css">
#property_facts_wrapper{
	background:#FFF;
	height:400px;
	width:100%;
}
#property_img{
	width: 50%;
	float:left;
}
#property_facts{
	width:50%;
	float:left;
}
#property_img img{height:400px;}
.clear{
	clear:both;
}
.room-name,.room-des,.room-price,.room-book{width:25%;}
.room-title,.room-des{color:#4b4b4c;}
.book{margin-top: 30px;}
.tab-cls{padding:10px;}
#map-canvas {
        height: 400px;
        margin: 0px;
        padding: 0px
      }
</style>
<script src="https://maps.googleapis.com/maps/api/js?v=3.exp"></script>
<script type="text/javascript">
$(document).ready(function(){
	$('.tabs').tabslet();
})
</script>

    <script>
function initialize() {
  var myLatlng = new google.maps.LatLng(5.41747,100.34026);
  var mapOptions = {
    zoom: 12,
    center: myLatlng
  }
  var map = new google.maps.Map(document.getElementById('map-canvas'), mapOptions);

  var marker = new google.maps.Marker({
      position: myLatlng,
      map: map,
      title: 'Hello World!'
  });
}

google.maps.event.addDomListener(window, 'load', initialize);

    </script>