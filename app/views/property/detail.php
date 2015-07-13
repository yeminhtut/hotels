<div  style="margin-top:50px;margin-bottom:50px;">
  <div id="property_facts_wrapper">
    <div id="property_img" class="col-md-6">
      <img src="https://s3-ap-southeast-1.amazonaws.com/zumata/assets/hotels/2.0/ee1029c1-ad51-4c77-7410-35a908c8632a/images/25.jpg">
    </div>
    <div id="property_facts" class="col-md-6"> 
    <h1><?php echo $property->property_name; ?></h1>     
      <?php echo "<p>".$property->address."</p>";?>

  </div>
      <div class="clear"></div>
</div>

</div>

<div  id="hotel_rooms">
   <div class="col-md-4" id="room_des"><?= $amenity ?></div>
   <div class="col-md-8" id="room_list"><?= $hotel_rooms ?></div>
   <div class="clear"></div>
</div>	
</div>
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
      title: ''
  });
}

google.maps.event.addDomListener(window, 'load', initialize);
</script>

<style type="text/css">
#room_des li{list-style-type: none;margin: 0px;}
#hotel_rooms{background:#FFF;padding:10px;}
</style>