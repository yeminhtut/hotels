<div class="row" style="margin-top:50px;">
	<ul class="hotel-list">
		<?= $hotel_list; ?>
	</ul>
    
</div>
<?= $pagination; ?>
<style type="text/css">
.thumb{width:100%;height: 200px !important;}
</style>
<script type="text/javascript">
$(document).ready(function(){	
	var cur_url = window.location.href;
	var parse_arr = cur_url.split('/');
	var destination = parse_arr[5];
	var checkin = parse_arr[7];
	var checkout = parse_arr[8];
	var persons = parse_arr[9];
	var rooms = parse_arr[10];	
	$.ajax({
	  type:"POST",
      url:'http://localhost/hotels/ajax/search_hotels' ,
      data: {destination:destination, checkin:checkin,checkout:checkout,persons:persons,rooms:rooms},
      dataType:"html",
      success: function(data) {
      	//console.log(data);      	
      	if (data=='null') {
      		setTimeout(function(){location.reload()},2000);
      		console.log('gar gar');
      	}
      	else{
      		$('.hotel-list').html(data);
      	};
      }
    });
})
</script>
<style type="text/css">
.left{float:left;}
.hotel-thumbnail,.hotel-price{width: 25%;}
.hotel-name{width: 50%}
.hotel-list{padding: 10px;background: #FFF;border:1px solid #EBEBEB;}
.hotel-row{margin-bottom: 10px;border-bottom: 1px solid #EBEBEB;padding-bottom: 10px;}
.hotel-row:last-child{padding-bottom:0px;border-bottom: none;}
.detail{margin-top:60px;}
.six-sec-ease-in-out {
    -webkit-transition: width 30s ease-in-out;
    -moz-transition: width 30s ease-in-out;
    -ms-transition: width 30s ease-in-out;
    -o-transition: width 30s ease-in-out;
    transition: width 30s ease-in-out;
}
.progress {
  overflow: hidden;
  height: 2px;
  margin-bottom: 0px;
  background-color: #f5f5f5;
  border-radius: 0px;
  -webkit-box-shadow: none;
  box-shadow: none;
}
</style>