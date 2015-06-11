<?php 
	use GuzzleHttp\Client;
	function _index() {

		$client = new Client();
		$response = $client->get('http://api.zumata.com/autosuggest/malaysia?api_key=rEnlPVvPD6V87RstUqEeoFjaQZt5GnFbNFxwyi2P');
		$result = $response->json();
		foreach ($result as $result) {
			$location_id = $result['value'];
			echo $location_id;echo "<br/>";
			$client = new Client();
			$response = $client->get('http://data.zumata.com/destinations/'.$location_id.'/en_US/long.json');
			$result = $response->json();			
			$test = scrap_to_table($result,$location_id);	
		}
		exit;
			  
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
	  	$lat = $result['latitude'];
	  	$lng = $result['longitude'];
	  	$statement = "SELECT * FROM `t_property` WHERE `zumata_property_id` LIKE ?";
	  	$sql=$dbh->prepare($sql);
        $sql->execute(array($zumata_id));
        $result=$sql->fetch();
        if (!$result) {
        	$statement = 'UPDATE t_property SET lat = ? lng = ? WHERE zumata_property_id = ?';				
			$sql = $dbh->prepare($statement);
			$sql->execute(array($lat,$lng, $zumata_id));	
        }
        else{
        	$statement = 'INSERT INTO t_property 
				(zumata_property_id,property_name, address, city, location_id,description,image_details,lat,lng,status,created_dt) 
				VALUES (?,?,?,?,?,?,?,?,?,1,NOW())';
			$sql = $dbh->prepare($statement);
			$sql->execute(array($zumata_id,$name, $address, $city,$location_id,$description,$image_arr,$lat,$lng));
        }
	  	
	  } 
	  return 'success';

}

 ?>