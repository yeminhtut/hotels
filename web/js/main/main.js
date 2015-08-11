(function($) {
	$("#main-search-wrapper").backstretch("/hotels/web/img/slide_hero.jpg");
	$( "#where" ).autocomplete({
          source: function( request, response ) {
              $.ajax({
              url: "/hotels/ajax/retrieve_destinations",
              dataType: 'json',
              type: 'POST',
              data: request,
              success: function(data){                
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

    var now = new Date();
    var d = new Date(new Date().getTime() + 168 * 60 * 60 * 1000);
    var checkInStart = d.format("mm/dd/yyyy");
    console.log(checkInStart);
    $('#datepicker').datepicker({
        startDate: checkInStart,
        autoclose: true
    });
    // $('#datepicker').change(function() {
    //     var changedate = $('#datepicker').val();
    //     var myDate = new Date(changedate);
    //     var checkout = myDate.setDate(myDate.getDate() + 1);
    //     var time = 1435334400000;
    //     var date = new Date(checkout);
    //     var t = date.toString("MMMM yyyy");
    //     var newdate = new Date(t);
    //     var can = (newdate.getMonth() + 1) + '/' + newdate.getDate() + '/' + newdate.getFullYear();
    //     $("#datepickerCheckout").removeAttr('disabled');
    //     $('#datepickerCheckout').datepicker({
    //         startDate: can,
    //         autoclose: true,

    //     });
    // });
	   
    $("#from" ).datepicker({ minDate: +7, maxDate: "+1M +10D"});
    $('#from').change(function() {
        var changedate = $('#from').val();
        var myDate = new Date(changedate);
        var checkout = myDate.setDate(myDate.getDate() + 1);        
        var date = new Date(checkout);
        var t = date.toString("MMMM yyyy");
        var newdate = new Date(t);
        var can = (newdate.getMonth() + 1) + '/' + newdate.getDate() + '/' + newdate.getFullYear();
        console.log(can);
        $("#to").removeAttr('disabled');
        $("#to" ).datepicker({ defaultDate: can,minDate: can, maxDate: "+1M +10D"});
    });
    

})(jQuery);