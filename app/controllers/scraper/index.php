<?php 
	use GuzzleHttp\Client;
	function _index($location_id='') {
	
	  $client = new Client();
	  $response = $client->get('http://data.zumata.com/destinations/'.$location_id.'/en_US/long.json');
	  $result = $response->json();  
	  //var_dump($result);exit;
	  $test = scrap_to_table($result,$location_id);
	  var_dump($test);
	  
	}

function scrap_to_table($result,$location_id){	
	$dbh = getdbh();
	$location_id = $location_id;
	foreach ($result as $result) {
	  	$address = $result['address'];
	  	$image_arr = $result['image_details'];
	  	$image_arr = implode(',', $image_arr);
	  	$zumata_id = $result['id'];
	  	$location_id = $location_id;
	  	$description = $result['description'];
	  	$city = $result['city'];
	  	$name = $result['name'];
	  	$statement = 'INSERT INTO t_property 
				(zumata_property_id,property_name, address, city, location_id,description,image_details,created_dt) 
				VALUES (?,?,?,?,?,?,?,NOW())';
		$sql = $dbh->prepare($statement);
		$sql->execute(array($zumata_id,$name, $address, $city,$location_id,$description,$image_arr));	
	  } 
	  return 'success';

}

 ?>