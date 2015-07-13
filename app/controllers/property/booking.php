<?php
use GuzzleHttp\Client;
function _booking($booking='',$booking_key = '')
{
    $content['booking_key'] = $booking_key;
    $data['body'][] = View::do_fetch(VIEW_PATH . 'property/booking.php', $content);
    View::do_dump(VIEW_PATH . 'layouts/layout.php', $data);
}


