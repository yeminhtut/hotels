<?php
header('Access-Control-Allow-Origin: *');  
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
    
    $response             = $client->get('http://api.zumata.com/search?destination=c35b1149-4336-4065-76c4-0afbbe495b5c&checkin=08/22/2015&checkout=08/23/2015&lang=en_US&rooms=1&adults=1&currency=SGD&timeout=0&api_key=rEnlPVvPD6V87RstUqEeoFjaQZt5GnFbNFxwyi2P');
    $result               = $response->json();    
    $search_completed     = array("search_completed"=> $result['searchCompleted']);
    $avaliable_room_arr   = $result['content']['hotels'];

    $hotel_rooms = array();
    if (count($avaliable_room_arr) > 0) {
      $loc_response = $client->get('http://data.zumata.com/destinations/c35b1149-4336-4065-76c4-0afbbe495b5c/en_US/short.json');
      $location_result   = $loc_response->json();
      $hotel_rooms = merge_location_avaliable($location_result,$avaliable_room_arr);
      $hotel_list = array('hotels'=>$hotel_rooms);
      $hotel_list_arr = array_merge($search_completed,$hotel_list);
      //var_dump($hotel_list_arr);exit;
    }
    echo json_encode($hotel_list_arr);exit;

    echo json_encode($hotel_rooms);
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
                               <a href="/hotels/property/detail/' . $hotel_id . '/' . $slug . '/' . $checkIn . '/' . $checkOut . '/' . $rooms . '/' . $persons . '" target="_blank" class="btn green-btn">Details</a>
                            </p>
                        
                      </div>
                   </div>
                   <div class="clear"></div>
                </li>
        ';
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

function make_hotel_thumb($image_details){    
    $count      = $image_details['count'];    
    $prefix     = $image_details['prefix'];
    $suffix     = $image_details['suffix'];
    $image_name = rand(1, $count);
    $image_name = 1;
    $src        = $prefix . '/' . $image_name . $suffix;    
    return $src;
}