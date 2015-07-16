<div  style="margin-top:50px;margin-bottom:50px;">
    <div id="property_facts_wrapper">
        <div id="property_img">
          <?php 
            $img_src = get_thumbnail($property->image_details);
           ?>
          <img src="<?= $img_src ?>">
        </div>
        <div id="property_facts">
            <h1><?= $property->property_name; ?></h1>
            <span class="glyphicon glyphicon-map-marker"></span><?php echo "<span>".$property->address."</span>";?>
        </div>
        <div class="clear"></div>
    </div>
</div>
<div  id="hotel_rooms">
    <div class="col-md-3" id="room_des">
        <div>
            <h3>Rating</h3>
            <img src="http://localhost/hotels/web/img/rating/<?= $property->rating;?>star.png">
        </div>
        <div>
            <h3>Amenties</h3>
            <?= $amenity ?> 
        </div>
    </div>
    <div class="col-md-9" id="room_list"><?= $hotel_rooms ?></div>
    <div class="clear"></div>
</div>

<script src="https://maps.googleapis.com/maps/api/js?v=3.exp"></script>
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
.room-title{
    font-size: 20px;
  color: #4b4b4c;
  font-weight: bold;
}
</style>
<?php 
  function get_thumbnail($image_arr){
    $image_arr = unserialize($image_arr);    
    $count      = $image_arr['count'];    
    $prefix     = $image_arr['prefix'];
    $suffix     = $image_arr['suffix'];
    $image_name = rand(1, $count);
    $image_name = 1;
    $src        = $prefix . '/' . $image_name . $suffix;
    list($width, $height, $type, $attr) = @getimagesize($src);
    if (empty($width)) {
        $src = myUrl('/web/img/default.png');        
    }
    return $src;
  }
 ?>