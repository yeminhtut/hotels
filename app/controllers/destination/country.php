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

    // $foot_script = '
    //     var time = 0;
    //     function load_select() {
    //             var cur_url = window.location.href;
    //             var parse_arr = cur_url.split("/");
    //             var destination = parse_arr[5];
    //             var checkin = parse_arr[7];
    //             var checkout = parse_arr[8];
    //             var persons = parse_arr[9];
    //             var rooms = parse_arr[10];
    //         $.ajax({
    //             type:"POST",
    //             url: "http://localhost/hotels/ajax/search_hotels",                
    //             data: {destination:destination, checkin:checkin,checkout:checkout,persons:persons,rooms:rooms},
    //             success: function(data) {
    //                 console.log(data.length);                    
    //                 if(data !== "null" || data.lenght > 10){                    
    //                     $("#avaliable-list").html(data);
    //                 }                                              
    //             },
    //            complete: function() {
    //                 var status = $("#status").html();
    //                 if (time < 5001) {
    //                     console.log(time);
    //                     setTimeout(load_select, 5000);
    //                     time = time + 5000;
    //                 } else if (time > 5001 && status !== 1) {
    //                     //$("#avaliable-list").html("<p><center style=\"font-weight:bold;\">Sorry, no available hotels found.. change search criteria...</center></p>");
    //                 }
    //             }
    //         });
    //     }';
    $content['footer_script']   = $foot_script;
    $data['pagename']           = $location_slug;
    $data['body'][]             = View::do_fetch(VIEW_PATH . 'destination/index.php', $content);
    View::do_dump(VIEW_PATH . 'layouts/layout-lumen.php', $data); 
    //Angular view//
    // $content['footer_script']   = $foot_script;
    // $data['pagename']           = $location_slug;
    // $data['body'][]             = View::do_fetch(VIEW_PATH . 'destination/angular_index.php', $content);
    // View::do_dump(VIEW_PATH . 'layouts/layout-angular.php', $data);   
}

function merge_location_avaliable($location_result,$avaliable_room_arr){
    $result = array();
    foreach($location_result as $values){
        foreach($avaliable_room_arr as $values2){
            if($values['id'] == $values2['id']){
                $result[] = array_merge_recursive($values, $values2);
                break;
            }
        }
    }
    return $result;
}


function get_avaliable_hotels_result($location_id, $rooms, $persons, $check_in, $check_out)
{    
    $client               = new Client();
    $request              = $client->createRequest('GET', 'http://api.zumata.com/search');
    $query                = $request->getQuery();
    $query['destination'] = $location_id;
    $query['checkin']     = str_replace('%2F', '/', $check_in);
    $query['checkout']    = str_replace('%2F', '/', $check_out);
    $query['lang']        = 'en_US';
    $query['rooms']       = $rooms;
    $query['adults']      = $persons;
    $query['currency']    = 'SGD';
    $query['timeout']     = rand(1, 10);
    $query['api_key']     = 'rEnlPVvPD6V87RstUqEeoFjaQZt5GnFbNFxwyi2P';
    $response             = $client->get($request->getUrl());
    $result               = $response->json();
    return $result;
}

function make_avaliable_room_html($hotel_rooms)
{
    $html = '';
    $html  .= '<ul class="hotel-list">';
        foreach ($hotel_rooms as $hotel) {
        $name = $hotel['name'];
        $address = $hotel['address'];
        $id = $hotel['id'][0];
        $thumbnail = make_hotel_thumb($hotel['image_details']);
        $cheapest_price = $hotel['rates']['packages'][0]['roomRate'];
        
        $html .= '<li class="hotel-row" data-price='.$cheapest_price.'>
                    <div class="col-lg-4 col-md-4 col-sm-4" style="padding-left:0px;">
                         <div class="img_list">
                            <a href=""><img width="180" height="120" src="'.$thumbnail.'" onerror="imgError(this);"></a>
                        </div>
                    </div>   
                   <div class="col-lg-6 col-md-6 col-sm-6">
                      <div class="rooms_list_desc">
                         <h3 class="link-title">'.$name.'</h3>
                         <span class="glyphicon glyphicon-map-marker"></span><span>'.$address.'</span>
                      </div>
                   </div>
                   <div class="col-lg-2 col-md-2 col-sm-2 ">
                      <div class="price_list">
                        
                            <sup>SGD</sup>'.$cheapest_price.'<small>/Per night</small>
                            <p>
                               <a href="" target="_blank" class="btn green-btn">Details</a>
                            </p>
                        
                      </div>
                   </div>
                   <div class="clear"></div>
                </li>
        ';
    }
    $html  .= '</ul>';
    return $html;

}



function make_hotel_thumb($image_details){    
    $count      = $image_details['count'];    
    $prefix     = $image_details['prefix'];
    $suffix     = $image_details['suffix'];
    $image_name = rand(1, $count);
    $image_name = 1;
    $src        = $prefix . '/' . $image_name . $suffix;    
    return $src;
}

/*from db*/
function make_hotel_thumb_db($image_arr)
{
    $image_arr = unserialize($image_arr);    
    $count      = $image_arr['count'];    
    $prefix     = $image_arr['prefix'];
    $suffix     = $image_arr['suffix'];
    $image_name = rand(1, $count);
    $image_name = 1;
    $src        = $prefix . '/' . $image_name . $suffix;    
    return $src;
}