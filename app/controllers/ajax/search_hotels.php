<?php
use GuzzleHttp\Client;
function _search_hotels()
{
    
    $checkInArr = explode('-', $_POST['checkin']);
    $check_in   = $checkInArr[1] . '/' . $checkInArr[0] . '/' . $checkInArr[2];
    
    $checkOutArr = explode('-', $_POST['checkout']);
    $check_out   = $checkOutArr[1] . '/' . $checkOutArr[0] . '/' . $checkOutArr[2];
    
    $client               = new Client();
    $request              = $client->createRequest('GET', 'http://api.zumata.com/search');
    $query                = $request->getQuery();
    $query['destination'] = $_POST['destination'];
    $query['checkin']     = str_replace('%2F', '/', $check_in);
    $query['checkout']    = str_replace('%2F', '/', $check_out);
    $query['lang']        = 'en_US';
    $query['rooms']       = 1;
    $query['adults']      = 1;
    $query['currency']    = 'SGD';
    $query['timeout']     = rand(1, 10);
    $query['api_key']     = 'rEnlPVvPD6V87RstUqEeoFjaQZt5GnFbNFxwyi2P';
    
    $response             = $client->get($request->getUrl());
    $result               = $response->json();
    $room_arr             = $result['content']['hotels'];
    $avaliable_room_list  = make_avaliable_room_html($room_arr, $_POST['checkin'], $_POST['checkout'], $_POST['persons'], $_POST['rooms']);
    if (empty($avaliable_room_list)) {
        echo 'null';
        exit;
    }
    $overhead = '<div style="display: none;" id="status">1</div>';
    echo $avaliable_room_list.$overhead;
}

function make_avaliable_room_html($room_arr, $checkIn, $checkOut, $persons, $rooms)
{
    $html = '';
    $html  .= '<ul class="hotel-list">';
    foreach ($room_arr as $hotel) {
        $hotel_id       = $hotel['id'];
        $cheapest_price = $hotel['rates']['packages'][0]['roomRate'];
        $hotel_detail   = get_room_detail_html($hotel_id, $cheapest_price, $checkIn, $checkOut, $persons, $rooms);    
        $html .= $hotel_detail;
    }
    $html  .= '</ul>';
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
        $html .= '<li class="hotel-row"><div class="hotel-thumbnail left">                    
                    <img width="180" height="120" src="' . $thumbnail . '" onerror="imgError(this);">                    
                    </div>
                    <div class="hotel-name left"><h3 class="link-title">' . $name . '</h3><p><span class="hotel_address">'.$address.'</span></p></div>
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
    $image_arr = unserialize($image_arr);    
    $count      = $image_arr['count'];    
    $prefix     = $image_arr['prefix'];
    $suffix     = $image_arr['suffix'];
    $image_name = rand(1, $count);
    $image_name = 1;
    $src        = $prefix . '/' . $image_name . $suffix;
    // list($width, $height, $type, $attr) = @getimagesize($src);
    // if (empty($width)) {
    //     $src = myUrl('/web/img/default.png');        
    // }
    return $src;
}