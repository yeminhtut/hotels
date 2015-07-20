<div class="topic">
    <div class="container"></div>
    <div class="topic__infos">
        <div class="container">
            <form class="form-inline" action="property/search_new" method="POST" autocomplete="off" data-toggle="validator">
                <div class="form-group">
               					
                        <input class="required search_table_cell_input form-control" id="where" name="where" type="text" placeholder="eg. Singapore"/>            
                        <input class="" id="destination_code" type="hidden" name="destination_code" value="">
                        <input class="" id="autocomplete" type="hidden" name="autocomplete" value="n">
               
                </div>
                <div class="form-group">			    
                    <input type="text" class="form-control" id="datepicker" placeholder="Check-in" name="check-in-date" data-error="plz select check in date" >
                </div>
                <div class="form-group">			   
                    <input type="text" class="form-control" id="datepickerCheckout" placeholder="Check-out" name="check-out-date" disabled data-error="plz select check out date" >
                </div>
                <div class="form-group">
                    <select class="form-control" name="no_of_guests">
                        <option value="1">1 Guests</option>
                        <option value="2">2 of Guests</option>
                        <option value="3">3 of Guests</option>
                        <option value="4">4 of Guests</option>
                    </select>
                </div>
                <div class="form-group">
                    <select class="form-control" name="no_of_rooms">
                        <option value="1">1 rooms</option>
                        <option value="2">2 rooms</option>
                        <option value="3">3 rooms</option>
                        <option value="4">4 rooms</option>
                    </select>
                </div>
                <button type="submit" class="btn green-btn">Search</button>
            </form>
        </div>
    </div>
</div>


<script>
$(document).ready(function() {
    var now = new Date();
    var d = new Date(new Date().getTime() + 168 * 60 * 60 * 1000);
    var checkInStart = d.format("mm/dd/yyyy");

    $('#datepicker').datepicker({
        startDate: checkInStart,
        autoclose: true
    });
    $('#datepicker').change(function() {
        var changedate = $('#datepicker').val();
        var myDate = new Date(changedate);
        var checkout = myDate.setDate(myDate.getDate() + 1);
        var time = 1435334400000;
        var date = new Date(checkout);
        var t = date.toString("MMMM yyyy");
        var newdate = new Date(t);
        var can = (newdate.getMonth() + 1) + '/' + newdate.getDate() + '/' + newdate.getFullYear();
        $("#datepickerCheckout").removeAttr('disabled');
        $('#datepickerCheckout').datepicker({
            startDate: can,
            autoclose: true,

        });
    });
});
</script>
<script type="text/javascript" src="https://code.jquery.com/ui/1.11.4/jquery-ui.min.js"></script>
<link rel="stylesheet" type="text/css" href="https://code.jquery.com/ui/1.11.4/themes/excite-bike/jquery-ui.css">
<script type="text/javascript">
$(document).ready(function(){
        $( "#where" ).autocomplete({
          source: function( request, response ) {
              $.ajax({
              url: "http://localhost/hotels/scraper",
              dataType: 'json',
              type: 'POST',
              data: request,
              success: function(data){
                console.log(data);
                  response(data);                
            }
            });
          },
          minLength: 2,
          select: function( event, ui ) {
            $('#destination_code').val(ui.item.id); 
            $('#autocomplete').val('y');
          },
          open: function() {
            $( this ).removeClass( "ui-corner-all" ).addClass( "ui-corner-top" );
            $( this ).autocomplete( 'widget' ).css( 'z-index' , 100);
          },
          close: function() {
            $( this ).removeClass( "ui-corner-top" ).addClass( "ui-corner-all" );
          }
        }); 

});

</script>
