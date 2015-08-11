<?php
function _search()
{    
    $loc               = $_POST['where'];
    $loc_arr           = explode(',', $loc);
    $location_name     = $loc_arr[0];
    $location_name     = strtolower(str_replace(' ', '-', $location_name));
    $check_in          = date("d-m-Y", strtotime($_POST['check-in-date']));
    $check_out         = date("d-m-Y", strtotime($_POST['check-out-date']));
    // if (empty($where) || $check_out<$check_in || $check_in=='01-01-1970' ) {
    //     redirect('/');exit;
    // }
    $_SESSION['where']      = $location_name;
    $_SESSION['check_in']   = $check_in;
    $_SESSION['check_out']  = $check_out;
    $guests                 = $_POST['no_of_guests'];
    $rooms                  = $_POST['no_of_rooms'];
    
    $location_id = trim(strip_tags($_POST['destination_code']));
    redirect('/destination/' . $location_id . '/' . $location_name . '/' . $check_in . '/' . $check_out . '/' . $guests . '/' . $rooms);
    
}