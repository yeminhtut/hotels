<?php
function _search_new()
{    
    $loc             = $_POST['where'];
    $loc_arr = explode(',', $loc);
    $location_name = $loc_arr[0];
    $check_in          = date("d-m-Y", strtotime($_POST['check-in-date']));
    $check_out         = date("d-m-Y", strtotime($_POST['check-out-date']));
    // if (empty($where) || $check_out<$check_in || $check_in=='01-01-1970' ) {
    //     redirect('/');exit;
    // }
    $guests            = $_POST['no_of_guests'];
    $rooms             = $_POST['no_of_rooms'];
    
    $location_id = trim(strip_tags($_POST['destination_code']));
    redirect('/destination/' . $location_id . '/' . $location_name . '/' . $check_in . '/' . $check_out . '/' . $guests . '/' . $rooms);
    // $check_destination = new Destination();
    // $check_destination->retrieve_one("dest_id=?", $where);
    // if (isset($check_destination)) {
    //     $location_id   = $check_destination->dest_id;
    //     $location_name = str_replace(' ', '-', strtolower($check_destination->city_name));
    //     redirect('/destination_code/' . $location_id . '/' . $location_name . '/' . $check_in . '/' . $check_out . '/' . $guests . '/' . $rooms);
    // }
}