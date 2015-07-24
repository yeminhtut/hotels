(function($) {
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
                          newhtml += '<li class="hotel-row" ><div class="col-lg-4 col-md-4 col-sm-4" style="padding-left:0px;"><div class="img_list"><img width="180" height="120" src="'+item.image_details.prefix+'/1'+item.image_details.suffix+'" onerror="imgError(this);"></div></div><div class="col-lg-6 col-md-6 col-sm-6"><div class="rooms_list_desc"><h3 class="link-title">'+item.name+'</h3><span class="glyphicon glyphicon-map-marker"></span><span>'+item.address+'</span></div></div><div class="clear"></div></li>'
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
})(jQuery);


/*using table json*/

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
            var rowTemplate = '<tr>' +
                                '<td class="col-lg-4 col-md-4 col-sm-4"><img src="<%this.image_details.prefix%>'+'/1'+'<%this.image_details.suffix%>" width="150px" height="120px;" onerror="imgError(this);"></td>' + 
                                '<td><div><h3 class="link-title"><%this.name%></h3></div><span class="glyphicon glyphicon-map-marker"></span><span><%this.address%></span></td>' +
                                '<td><span>$</span><span><%this.rates.packages[0].chargeableRate%></span></td>' +                                                           
                              '</tr>';

        $.ajax({
            type:"POST",
            url: "http://localhost/hotels/ajax/search_hotels", 
            dataType: 'json',               
            data: {destination:destination, checkin:checkin,checkout:checkout,persons:persons,rooms:rooms},
            success: function(data) {    
                    var count = Object.keys(data).length;
                    console.log(count);                    
                    
                    if (count>1) {
                      $('.pagination').css("display","block");
                      $('#avaliable-list').remove();
                      $('#table-wrapper').renderTable({
                            template: rowTemplate,
                            data: data,
                            defaultSortField: '',                            
                            pagination: {
                            rowPageCount: 8
                        },
                      });
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

