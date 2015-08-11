<?php
use GuzzleHttp\Client;
function _country($location_id = '', $location_slug = '', $checkIn, $checkOut, $persons, $rooms, $offset = 1)
{
    $checkInArr = explode('-', $checkIn);
    $check_in   = $checkInArr[1] . '/' . $checkInArr[0] . '/' . $checkInArr[2];
    
    $checkOutArr = explode('-', $checkOut);
    $check_out   = $checkOutArr[1] . '/' . $checkOutArr[0] . '/' . $checkOutArr[2];
    
    $rooms       = $rooms;
    $persons     = $persons;
    $location_id = trim(strip_tags($location_id));    

    $foot_script = '
    var time = 0;
    var destination = "'.$location_id.'";
    var checkin = "'.$check_in.'";
    var checkout = "'.$check_out.'";
    var persons = "'.$persons.'";
    var rooms = "'.$rooms.'";
            function load_select() {
                    var newhtml = "";
                    var data = {
                                destination: destination,
                                checkin: checkin,
                                checkout: checkout,
                                persons: persons,
                                rooms: rooms
                                };
                    $.ajax({
                        type: "POST",
                        url: "/hotels/ajax/search_hotels",
                        dataType: "json",
                        data: data,
                        success: function(response) {                           
                                    var response_count = Object.keys(response).length;
                                    var count = 0;
                                    if (response_count > 0) {
                                        var search_complete = response.search_completed;
                                        var data = response.hotels;
                                        count = Object.keys(data).length;
                                        console.log(count);
                                    };

                                    if (count > 1) {
                                        var j = 1;
                                        var hotel_ids_arr = [];
                                        $.each(data, function(i, item) {
                                            hotel_ids_arr.push(item.id[0]);
                                            newhtml = hotel_listing_view(j, item);
                                            $(".hotel-list").append(newhtml);
                                            $(".hotel-list").css("display", "block");
                                            $("#avaliable-list").remove();
                                            j++;
                                        });
                                        $("#status").html("1");
                                        var hotel_count = j - 1;
                                        var result_list_count = "<strong>" + hotel_count + "</strong> hotels found";
                                        $("#results-bar").html(result_list_count);
                                        $("#results-bar").css("display", "block");
                                        $("#sorting_select").css("display", "block");
                                    };
                                },
                                complete: function() {
                                    var status = $("#status").html();
                                    console.log(status);
                                    if (status !== "1") {
                                        console.log("need to call again");
                                        if (time < 30001) {
                                            console.log(time);
                                            if (status !== 1) {};
                                            setTimeout(load_select, 5000);
                                            time = time + 5000;
                                        } else if (time > 30001 && status !== 1) {
                                            $("#avaliable-list").html("<p><center style=\"font-weight:bold;\">Sorry, no available hotels found.. change search criteria...</center></p>");
                                        }
                                    } else {
                                        console.log("no need to call");
                                        sort_by_best_deals();
                                    };
                                }
                            });
                        };
                    function book_hotel(element){
                        var room_key = $(element).attr("data-roomKey");
                        var room_des = $("#" +room_key+"des").text();
                        var price = $(element).parent().parent().attr("data-price");
                        var hotel_id = $(element).closest( "div" ).attr("id").replace("panel","");
                        var hotel_name = $("#" +hotel_id+"title").text();
                        var hotel_img =   $("#" +hotel_id+"img").attr("src"); 
                        var data = {
                                    checkin: checkin,
                                    checkout: checkout,
                                    persons: persons,
                                    rooms: rooms,
                                    room_key: room_key,
                                    room_des: room_des,
                                    price: price,
                                    hotel_id: hotel_id,
                                    hotel_img: hotel_img,
                                    hotel_name: hotel_name    
                                    };
                        
                        $.ajax({
                            type: "POST",
                            url: "/hotels/ajax/insert_hotels_temp",
                            dataType: "json",
                            cache: false,
                            data: data,
                            success: function(response) {
                                if (response > 0) {
                                    window.location.href = "http://localhost/hotels/property/booking/"+room_key;
                                };
                            }
                        });
                    }
    ';
    
    $content['foot_script']     = $foot_script;
    $data['pagename']           = $location_slug;
    $data['body'][]             = View::do_fetch(VIEW_PATH . 'destination/index.php', $content);
    View::do_dump(VIEW_PATH . 'layouts/layout-lumen.php', $data);      
}
