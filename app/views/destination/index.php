<div class="error" style="height:20px;"></div>
<div style="display: none;" id="status">0</div>
<div id="progressTimer"></div>
<div class="row" id="avaliable-list">

  <div id="image" style="background:#FFF;height:400px;width:100%;text-align:center;">
    <img src="http://localhost/hotels/web/img/ajax-loader.gif" height="14px;" width="256px;" style="margin-top:200px;">
  </div>

</div>
<div id="fetch-note"></div>
<div id="example2"></div>


<script type="text/javascript">
$(document).ready(function(){
      load_select(0); 
      
});
var time = 0;
        function load_select() {
                var cur_url = window.location.href;
                var parse_arr = cur_url.split("/");
                var destination = parse_arr[5];
                var checkin = parse_arr[7];
                var checkout = parse_arr[8];
                var persons = parse_arr[9];
                var rooms = parse_arr[10];
                var newhtml = '';
            $.ajax({
                type:"POST",
                url: "http://localhost/hotels/ajax/search_hotels", 
                dataType: 'json',               
                data: {destination:destination, checkin:checkin,checkout:checkout,persons:persons,rooms:rooms},
                success: function(data) {    
                        var count = Object.keys(data).length;
                        console.log(count);                    
                        
                        newhtml  += '<ul class="hotel-list">';                                 
                        $.each(data,function(i,item){
                          var jsonObj = JSON.stringify(item);
                          newhtml += '<li class="hotel-row"><div class="col-lg-4 col-md-4 col-sm-4" style="padding-left:0px;"><div class="img_list"><img width="180" height="120" src="'+item.image_details.prefix+'/1'+item.image_details.suffix+'" onerror="imgError(this);"></div></div><div class="col-lg-6 col-md-6 col-sm-6"><div class="rooms_list_desc"><h3 class="link-title">'+item.name+'</h3><span class="glyphicon glyphicon-map-marker"></span><span>'+item.address+'</span></div></div><div class="clear"></div><span  data-id="'+item.id[0]+'" data-obj="'+item+'" onclick="goDoSomething(this);">check</span></li>'
                          $('#'+item.id[0]).data('key',item);
                        });
                        newhtml += '</ul>'; 
                        if (count>1) {
                          $('#avaliable-list').html(newhtml); 
                        };   
                               
                },
               complete: function() {
                    var status = $("#status").html();
                    if (time < 5001) {
                        console.log(time);
                        setTimeout(load_select, 5000);
                        time = time + 5000;
                    } else if (time > 5001 && status !== 1) {
                        //$("#avaliable-list").html("<p><center style=\"font-weight:bold;\">Sorry, no available hotels found.. change search criteria...</center></p>");
                    }
                }
            });
        }

function imgError(image){
	image.onerror = "";
  image.src = "http://localhost/hotels/web/img/default.png";
  return true;
}
function goDoSomething(d){
  console.log(d.getAttribute("data-obj"));
}
<?= $footer_script; ?>

// var line = new ProgressBar.Line('#progressTimer', {
//     color: '#1abc9c',
//     duration: 15000,
//     easing: "linear",
//     strokeWidth: 0.5,
// });

// line.animate(1.0,function(){
//   line.destroy();
// });  // Number from 0.0 to 1.0

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