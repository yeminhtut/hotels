<?php 
function _retrieve_locations() {
	$term = trim($_POST['term']);    
    $client = new Client();
    $response = $client->get('http://api.zumata.com/autosuggest/'.$term.'?api_key=rEnlPVvPD6V87RstUqEeoFjaQZt5GnFbNFxwyi2P');
    $tags = $response->json();	
    $tags = array_map(function($tag) {
        return array(
            'label' => $tag['label'],
            'id' => $tag['value']
        );
    }, $tags);
    echo json_encode($tags);exit;
}

?>