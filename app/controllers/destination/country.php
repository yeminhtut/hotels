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
    
    $complete = false;
    $counter  = 0;
    // while ( $complete == false && $counter < 50 ) {
    // 	$result = get_avaliable_hotels_result($location_id,$rooms,$persons,$check_in,$check_out);
    // 	sleep(1);
    // 	$complete = $result['searchCompleted'];
    // 	$counter = $counter + 1;
    // }
    
    // if ($complete == true) {
    // $room_arr = $result['content']['hotels'];		
    // $avaliable_room_list = make_avaliable_room_html($room_arr,$checkIn,$checkOut,$persons,$rooms);
    // $content['search_complete'] = $counter;	
    // $content['hotel_list'] = $avaliable_room_list;	
    // $data['pagename']= $location_slug;		  
    // $data['body'][]=View::do_fetch(VIEW_PATH.'destination/index.php',$content);
    // View::do_dump(VIEW_PATH.'layouts/layout.php',$data);
    // }
    
    $content['search_complete'] = $counter;
    $content['hotel_list']      = $avaliable_room_list;
    $data['pagename']           = $location_slug;
    $data['body'][]             = View::do_fetch(VIEW_PATH . 'destination/index.php', $content);
    View::do_dump(VIEW_PATH . 'layouts/layout.php', $data);
    
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

function make_avaliable_room_html($room_arr, $checkIn, $checkOut, $persons, $rooms)
{
    $html = '';
    foreach ($room_arr as $hotel) {
        $hotel_id       = $hotel['id'];
        $cheapest_price = $hotel['rates']['packages'][0]['roomRate'];
        $hotel_detail   = get_room_detail_html($hotel_id, $cheapest_price, $checkIn, $checkOut, $persons, $rooms);
        $html .= $hotel_detail;
    }
    return $html;
}

function get_room_detail_html($hotel_id, $cheapest_price, $checkIn, $checkOut, $persons, $rooms)
{
    $result = get_room_detail_with_id($hotel_id);
    $html   = '';
    foreach ($result as $result) {
        $name      = $result['property_name'];
        $slug      = strtolower(str_replace(' ', '-', $name));
        $hotel_id  = $hotel_id;
        $address   = $result['address'];
        $thumbnail = make_hotel_thumb($result['image_details']);
        // $html.='<div class="col-sm-6 col-md-3 list" style="height:425px;">
        //             <div class="thumbnail">
        //             <a href="/hotels/property/detail/'.$hotel_id.'/'.$slug.'/'.$checkIn.'/'.$checkOut.'/'.$rooms.'/'.$persons.'" target="_blank"><img class="img-rounded thumb" src="'.$thumbnail.'"></a>
        //               <div class="caption text-center">
        //                 <h3>'.$name.'</h3>
        //                 <p>'.$address.'</p> 
        //                 <p>USD:'.$cheapest_price.'</p>                  
        //               </div>
        //             </div>
        //             </div>';
        $html .= '<li class="hotel-row"><div class="hotel-thumbnail left">					
					<img width="150" height="150" src="' . $thumbnail . '">					
					</div>
					<div class="hotel-name left"><h3 class="link-title">' . $name . '</h3></a></div>
					<div class="hotel-price left">
						<a href="/hotels/property/detail/' . $hotel_id . '/' . $slug . '/' . $checkIn . '/' . $checkOut . '/' . $rooms . '/' . $persons . '" target="_blank"><button type="submit" class="btn green-btn detail">Details</button></a>
					</div><div class="clear"></div></li>';
    }
    return $html;
}

function get_room_detail_with_id($hotel_id)
{
    $dbh       = getdbh();
    $statement = "SELECT * FROM `t_property` WHERE `zumata_property_id` LIKE '$hotel_id'";
    $sql       = $dbh->prepare($statement);
    $sql->execute();
    $result = $sql->fetchAll();
    return $result;
}


function make_hotel_thumb($image_arr)
{
    $image_arr  = explode(',', $image_arr);
    $count      = $image_arr[0];
    $prefix     = $image_arr[1];
    $suffix     = $image_arr[2];
    $image_name = rand(1, $count);
    $image_name = 1;
    $src        = $prefix . '/' . $image_name . $suffix;
    return $src;
}