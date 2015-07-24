<div class="row" id="avaliable-list">
  <div id="image" style="background:#FFF;height:400px;width:100%;text-align:center;">
    <img src="http://localhost/hotels/web/img/ajax-loader.gif" height="14px;" width="256px;" style="margin-top:200px;">
  </div>
</div>

<div id="tst"></div>
<div id="status"></div>
<div id="table-wrapper">
    <table class="table">
        <tbody>

        </tbody>
    </table>
    <div class="pagination">
        <div class="pagination-buttons page">
            <a href="#" class="btn btn-primary to-first float-left disabled">« FIRST</a> <a href="#" class="btn btn-primary to-previous float-left">PREVIOUS</a>
        </div>
        <div class="pagination-pages page">
        </div>
        <div class="pagination-buttons page">
            <a href="#" class="btn btn-primary to-next float-left">NEXT</a> <a href="#" class="btn btn-primary to-last float-left disabled">LAST »</a>
        </div>
    </div>
</div>

<script type="text/javascript" src="http://localhost/hotels/web/js/listing.js"></script>
<script type="text/javascript" src="http://localhost/hotels/web/js/table-renderer.js"></script>
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
                        
                        //newhtml  += '<ul class="hotel-list">';   
                        if (count > 1) {
                            var j = 1;
                            $.each(data,function(i,item){
                              newhtml = '<li class="hotel-row '+j+'" ><div class="col-lg-4 col-md-4 col-sm-4" style="padding-left:0px;"><div class="img_list"><img width="180" height="120" src="'+item.image_details.prefix+'/1'+item.image_details.suffix+'" onerror="imgError(this);"></div></div><div class="col-lg-6 col-md-6 col-sm-6"><div class="rooms_list_desc"><h3 class="link-title">'+item.name+'</h3><span class="glyphicon glyphicon-map-marker"></span><span>'+item.address+'</span></div></div><div class="clear"></div></li>'
                                $( "#tst" ).append(newhtml);
                                $('#avaliable-list').remove();
                                j++;
                            });
                            $('#status').html('1');
                        };                             
                        
                        //newhtml += '</ul>'; 
                        // if (count>1) {
                        //   $('#avaliable-list').html(newhtml); 
                        // };   
                               
                },
               complete: function() {
                    var status = $("#status").html();
                    console.log(status);
                    if (status !== '1') {
                        console.log('need to call again');
                        if (time < 5001) {
                        console.log(time);
                        if (status !== 1) {};
                        setTimeout(load_select, 5000);
                        time = time + 5000;
                        } else if (time > 5001 && status !== 1) {
                            //$("#avaliable-list").html("<p><center style=\"font-weight:bold;\">Sorry, no available hotels found.. change search criteria...</center></p>");
                        }
                    }
                    else{
                        console.log('no need to call');
                    }
                    ;
                    
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
</script>

<style type="text/css">
.page-divider{margin-right:6px;display: inline-block;}
.to-page{margin-right:6px;}
.page{display: inline-block;}
#table-wrapper{background: #FFF;margin-top: 50px;}
.pagination{display: none;text-align: center;padding-bottom: 20px;}
.thumb{border-radius: 6px}.link-title{font-size: 20px;color: #4b4b4c;font-weight: bold}.hotel_address{color:#898989}#avaliable-list{margin: 0px;margin-top:-4px}.price_list{display: table;font-size: 22px;color: #e74c3c;width: 100%;margin-left: -15px}.price_list small{font-size: 10px}
</style>