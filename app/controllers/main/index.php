<?php
use GuzzleHttp\Client;
function _index()
{
    $content['location'] = '';
    $data['pagename']    = 'Hotel Search';
    
    $data['body'][] = View::do_fetch(VIEW_PATH . 'main/index.php', $content);
    View::do_dump(VIEW_PATH . 'layouts/layout_main.php', $data);
}
