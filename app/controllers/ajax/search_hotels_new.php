<?php
header('Access-Control-Allow-Origin: *');  
header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept");
use GuzzleHttp\Client;
function _search_hotels_new()
{
    // $checkInArr = explode('-', $_POST['checkin']);
    // $check_in   = $checkInArr[1] . '/' . $checkInArr[0] . '/' . $checkInArr[2];
    
    // $checkOutArr = explode('-', $_POST['checkout']);
    // $check_out   = $checkOutArr[1] . '/' . $checkOutArr[0] . '/' . $checkOutArr[2];
    
    // $location_id = $_POST['destination'];
    // $rooms = $_POST['rooms'];
    // $persons = $_POST['persons'];
    $client               = new Client();
    // $request              = $client->createRequest('GET', 'http://api.zumata.com/search');
    // $query                = $request->getQuery();
    // $query['destination'] = $location_id;
    // $query['checkin']     = str_replace('%2F', '/', $check_in);
    // $query['checkout']    = str_replace('%2F', '/', $check_out);
    // $query['lang']        = 'en_US';
    // $query['rooms']       = $rooms;
    // $query['adults']      = $persons;
    // $query['currency']    = 'SGD';
    // $query['timeout']     = rand(1, 10);
    // $query['api_key']     = 'rEnlPVvPD6V87RstUqEeoFjaQZt5GnFbNFxwyi2P';
    
    $response             = $client->get('http://api.zumata.com/search?destination=f75a8cff-c26e-4603-7b45-1b0f8a5aa100&checkin=08/22/2015&checkout=08/23/2015&lang=en_US&rooms=1&adults=1&currency=SGD&timeout=0&api_key=rEnlPVvPD6V87RstUqEeoFjaQZt5GnFbNFxwyi2P');
    $result               = $response->json();    
    $search_completed     = array("search_completed"=> $result['searchCompleted']);
    $avaliable_room_arr   = $result['content']['hotels'];

    $hotel_rooms = array();
    if (count($avaliable_room_arr) > 0) {
      $loc_response = $client->get('http://data.zumata.com/destinations/f75a8cff-c26e-4603-7b45-1b0f8a5aa100/en_US/long.json');
      $location_result   = $loc_response->json();
      $hotel_rooms = merge_location_avaliable($location_result,$avaliable_room_arr);
      $hotel_list = array('hotels'=>$hotel_rooms);
      $hotel_list_arr = array_merge($search_completed,$hotel_list);      
    }
    echo json_encode($hotel_list_arr);exit;

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
