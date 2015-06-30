<?php
use GuzzleHttp\Client;
function _index()
{
    $client              = new Client();
    $response            = $client->get('http://api.zumata.com/autosuggest/malaysia?api_key=rEnlPVvPD6V87RstUqEeoFjaQZt5GnFbNFxwyi2P');
    $result              = $response->json();
    $html                = make_html($result);
    $content['location'] = make_html($result);
    $data['pagename']    = 'Hotel Search';
    
    $data['body'][] = View::do_fetch(VIEW_PATH . 'main/index.php', $content);
    View::do_dump(VIEW_PATH . 'layouts/layout_main.php', $data);
}

function make_html($result)
{
    $html = '';
    foreach ($result as $result) {
        $location     = $result['label'];
        $location_val = $result['value'];
        $html .= '<option value=' . $location_val . '>' . $location . '</option>';
        
    }
    return $html;
}
