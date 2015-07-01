<?php 
	use GuzzleHttp\Client;
	function _index() {
		echo 'index';exit;
		// $client = new Client();
		// $response = $client->get('http://api.zumata.com/autosuggest/malaysia?api_key=rEnlPVvPD6V87RstUqEeoFjaQZt5GnFbNFxwyi2P');
		// $result = $response->json();
		// var_dump($result);exit;
		// foreach ($result as $result) {
		// 	$location_id = $result['value'];
		// 	echo $location_id;echo "<br/>";
		// 	$client = new Client();
		// 	$response = $client->get('http://data.zumata.com/destinations/'.$location_id.'/en_US/long.json');
		// 	$result = $response->json();			
		// 	$test = scrap_to_table($result,$location_id);	
		// }
		// exit;
		$client = new Client();
		$response = $client->get('http://data.zumata.com/destinations/659197a6-62c1-4636-768b-3fcb2ebc0ecf/en_US/long.json');
		$result = $response->json();
		$location_id = '659197a6-62c1-4636-768b-3fcb2ebc0ecf';
		foreach ($result as $k => $v) {
			$address = $result[$k]['address'];
			$zumata_id = $result[$k]['id'];
		  	$location_id = $location_id;
		  	$lat = $result[$k]['latitude'];
		  	$lng = $result[$k]['longitude'];
		  	$description = $result[$k]['description'];
		  	//echo $description;exit;
		  	$status = 0;		  	
	        $result_id = check_existing($zumata_id,$location_id);
	        echo $result_id;echo "<br>";
	        if ($result) {
	        	$dbh = getdbh();
	        	//$statement = "UPDATE `t_property` SET `status`= '$status',`description`= '$description',`lat`= '$lat',`lng`=$lng,`updated_dt`= NOW() WHERE property_id = '$result_id'";	
	        	$statement = "UPDATE `hotels`.`t_property` SET `description` = '$description' WHERE `t_property`.`property_id` = '$result_id'";
	        	//echo $statement;exit;
	        	$sql = $dbh->prepare($statement);
				$sql->execute();			
	        }
	           
		}
		
	}

function scrap_to_table($result,$location_id){	
	$dbh = getdbh();
	$location_id = $location_id;
	// foreach ($result as $result) {
	// 	# code...
	// }
	foreach ($result as $k => $v) {
		$address = $result[$k]['address'];
		$zumata_id = $result[$k]['id'];
	  	$location_id = $location_id;
	  	$lat = $result[$k]['latitude'];
	  	$lng = $result[$k]['longitude'];
	  	$status = 0;
	  	$statement = "SELECT property_id  FROM `t_property` WHERE `zumata_property_id` LIKE ? LIMIT 1";
	  	$sql=$dbh->prepare($statement);
        $sql->execute(array($zumata_id));
        $result = $sql->fetch();
        if ($result) {
        	$statement = 'UPDATE t_property SET lat = ? AND lng = ? AND status = ? WHERE zumata_property_id = ?';				
			$sql = $dbh->prepare($statement);
			$sql->execute(array($lat,$lng,$status,$zumata_id));	
        }
	}
	//return $result;
	// foreach ($result as $result) {
	//   	$address = $result['address'];
	//   	$image_arr = $result['image_details'];
	//   	$image_arr = implode(',', $image_arr);
	//   	$zumata_id = $result['id'];
	//   	$location_id = $location_id;
	//   	$description = $result['description'];
	//   	$city = $result['city'];
	//   	$name = $result['name'];
	//   	$lat = $result['latitude'];
	//   	$lng = $result['longitude'];
	//   	$status = 0;
	//   	$statement = "SELECT property_id  FROM `t_property` WHERE `zumata_property_id` LIKE ? LIMIT 1";
	//   	$sql=$dbh->prepare($sql);
 //        $sql->execute(array($zumata_id));
 //        $result=$sql->fetch();
 //        if ($result) {
 //        	$statement = 'UPDATE t_property SET lat = ? AND lng = ? AND status = ? WHERE zumata_property_id = ?';				
	// 		$sql = $dbh->prepare($statement);
	// 		$sql->execute(array($lat,$lng,$status,$zumata_id));	
 //        }
   //      else{
   //      	$statement = 'INSERT INTO t_property 
			// 	(zumata_property_id,property_name, address, city, location_id,description,image_details,lat,lng,status,created_dt) 
			// 	VALUES (?,?,?,?,?,?,?,?,?,1,NOW())';
			// $sql = $dbh->prepare($statement);
			// $sql->execute(array($zumata_id,$name, $address, $city,$location_id,$description,$image_arr,$lat,$lng));
   //      }
	  	
	  //} 
	  return 'success';

}
function check_existing($zumata_id,$location_id){
	$dbh = getdbh();
	$statement = "SELECT property_id  FROM `t_property` WHERE `zumata_property_id` LIKE ? LIMIT 1";
	$sql=$dbh->prepare($statement);
    $sql->execute(array($zumata_id));
    $result = $sql->fetch();
    return $result['property_id'];
}

 ?>