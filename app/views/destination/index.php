<div class="error" style="height:20px;"></div>
<div style="display: none;" id="status">0</div>
<div id="progressTimer"></div>
<div class="row" id="avaliable-list">

  <div id="image" style="background:#FFF;height:400px;width:100%;text-align:center;">
    <img src="http://localhost/hotels/web/img/ajax-loader.gif" height="14px;" width="256px;" style="margin-top:200px;">
  </div>

  <?//= $hotel_list; ?>
</div>
<div id="fetch-note"></div>



<script type="text/javascript">
$(document).ready(function(){
      load_select(0); 
      // var status = $('#status').html();
});
function imgError(image){
	image.onerror = "";
    image.src = "http://localhost/hotels/web/img/default.png";
    return true;
}
<?= $footer_script; ?>

var line = new ProgressBar.Line('#progressTimer', {
    color: '#1abc9c',
    duration: 15000,
    easing: "linear",
    strokeWidth: 0.5,
});

line.animate(1.0,function(){
  line.destroy();
});  // Number from 0.0 to 1.0

</script>
<style type="text/css">
.thumb{border-radius: 6px;}
.link-title{font-size: 20px;
  color: #4b4b4c;
  font-weight: bold;
 }
.hotel_address{color:#898989;}
#avaliable-list{margin: 0px;margin-top:-4px;}
.price_list {
  display: table;
  font-size: 22px;
  color: #e74c3c;
  width: 100%;
  margin-left: -15px;
}
.price_list small{font-size: 10px;}
</style>