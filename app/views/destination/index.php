<div class="error" style="height:20px;"></div>
<div id="progressTimer"></div>
<div class="row" style="margin-top:50px;">
	<ul class="hotel-list">
		<?= $hotel_list; ?>
	</ul>    
</div>
<div id="fetch-note"></div>
<style type="text/css">.thumb{width:100%;height: 200px !important;}</style>
<script type="text/javascript">
$(document).ready(function(){
      load_select(0);     
})
// var time = 0;
//       function load_select() {
//                 var cur_url = window.location.href;
//                 var parse_arr = cur_url.split("/");
//                 var destination = parse_arr[5];
//                 var checkin = parse_arr[7];
//                 var checkout = parse_arr[8];
//                 var persons = parse_arr[9];
//                 var rooms = parse_arr[10];
//             $.ajax({
//                 type:"POST",
//                 url: "http://localhost/hotels/ajax/search_hotels",                
//                 data: {destination:destination, checkin:checkin,checkout:checkout,persons:persons,rooms:rooms},
//                 success: function(data) {
//                     if (data.length > 4) {
//                         $(".hotel-list").html(data);
//                     }                            
//                 },
//                 complete: function() {
//                     if (time < 10001) {
//                         console.log(time);
//                         setTimeout(load_select, 5000);
//                         time = time + 5000;
//                     } else if (time > 30001) {
//                         $("#fetch-note").html("<p><center style=\"font-weight:bold;\">Sorry, no available hotels found.. change search criteria...</center></p>");
//                     }
//                 }
//             });
//         }
<?= $footer_script; ?>
</script>
