<?php
use GuzzleHttp\Client;
function _booking($booking='',$booking_key = '')
{
    $booking_temp = new Booking_Temp();
    $booking_temp->retrieve_one('room_key=?',$booking_key);   
    $content['booking_summary'] = $booking_temp;
    $content['booking_key'] = $booking_key;
    //$data['body'][] = View::do_fetch(VIEW_PATH . 'property/booking.php', $content);
    $data['body'][] = View::do_fetch(VIEW_PATH . 'property/paypal_booking.php', $content);
    View::do_dump(VIEW_PATH . 'layouts/layout.php', $data);
}


