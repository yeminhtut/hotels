<div class="error" style="height:20px;"></div>
<div id="progressTimer"></div>
<div class="row" id="avaliable-list"></div>
<div id="fetch-note"></div>



<script type="text/javascript">
$(document).ready(function(){
      load_select(0);     
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

line.animate(1.0);  // Number from 0.0 to 1.0

</script>
<style type="text/css">
.thumb{border-radius: 6px;}
.link-title{font-size: 20px;
  color: #4b4b4c;
  font-weight: bold;
  margin-top: 0px; }
.hotel_address{color:#898989;}
#avaliable-list{margin: 0px;margin-top:-4px;}
</style>