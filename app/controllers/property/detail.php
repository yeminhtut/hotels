<?php
use GuzzleHttp\Client;
function _detail($detail = '', $hotel_id = '', $hotel_slug = '', $checkIn, $checkOut, $persons, $rooms)
{
    
    $property = new Property();
    $property->retrieve_one("zumata_property_id=?", $hotel_id);

    $amenity = new Amenity();
    $amenity->retrieve_one('zumata_id=?',$hotel_id);

    $content['amenity'] = make_hotel_amenity($amenity);

    $content['property'] = $property;         
    $checkInArr = explode('-', $checkIn);
    $check_in   = $checkInArr[1] . '/' . $checkInArr[0] . '/' . $checkInArr[2];
    
    $checkOutArr = explode('-', $checkOut);
    $check_out   = $checkOutArr[1] . '/' . $checkOutArr[0] . '/' . $checkOutArr[2];
    
    $client  = new Client();
    $timeout = rand(1, 20);
    
    $request = $client->createRequest('GET', 'http://api.zumata.com/single_search/' . $hotel_id);
    $query   = $request->getQuery();
    
    $query['checkin']  = str_replace('%2F', '/', $check_in);
    $query['checkout'] = str_replace('%2F', '/', $check_out);
    $query['lang']     = 'en_US';
    $query['rooms']    = $rooms;
    $query['adults']   = $persons;
    $query['currency'] = 'SGD';
    $query['timeout']  = rand(1, 20);
    $query['api_key']  = 'rEnlPVvPD6V87RstUqEeoFjaQZt5GnFbNFxwyi2P';
    
    $response = $client->get($request->getUrl());
    $result   = $response->json();
    $rooms    = $result['content']['hotels'][0]['rates']['packages'];    
    
    $hotel_rooms = make_hotel_rooms_html($rooms);
    
    $content['hotel_rooms'] = $hotel_rooms;
    $data['pagename']       = $hotel_slug;
    
    $data['body'][] = View::do_fetch(VIEW_PATH . 'property/detail.php', $content);
    View::do_dump(VIEW_PATH . 'layouts/layout.php', $data);
}

function make_hotel_amenity($amenity){
    $html = '';
    $amenity = unserialize($amenity->amenities);
    foreach ($amenity as $k => $v) {
        if ($amenity[$k] == 'true') {
            $k = preg_split('/(?=[A-Z])/',$k);
            
            $amenities = ucfirst($k[0]).' '.ucfirst($k[1]);
            if (strlen($k[0]) == 1) {
               $amenities = ucfirst($k[0]).ucfirst($k[1]);
            }
            
            $html .= '<li><span class="glyphicon glyphicon-ok-circle"></span>'.$amenities.' </li>';
        }
    }
    return $html;
}
function make_hotel_rooms_html($rooms)
{
    $html = '';
    
    foreach ($rooms as $room) {
        $key = $room['key'];
        $room_type   = $room['normalizedRoomDescription'];
        $description = $room['roomDescription'];
        $price       = $room['roomRate'];
        $html .= '<div class="hotel-row">
                    <div class="col-md-6">                  
                    <h3 class="room-title">'.$room_type.'</h3>                
                    </div>
                    
                    <div class="col-md-3">                  
                    <h3>SGD '.$price.'</h3>                
                    </div>
                    <div class="col-md-3">                    
                    <a href="/hotels/property/booking/'.$key.'" class="btn green-btn" style="margin-top:20px;">Book</a>
                    </div><div class="clear"></div></div>';
    }    
    return $html;
}


