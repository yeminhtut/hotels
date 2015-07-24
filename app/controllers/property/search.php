<?php 
use GuzzleHttp\Client;
function _search(){

	$where = $_POST['where'];
	$where_arr = array_reverse(explode(',', $where));
	$country = $where_arr[0];
	$city = $where_arr[1];
	$dest_code = $_POST['destination_code'];	
	$dest_code = '1b6d1de9-c0db-438d-53a9-428b140f57b9';
	echo $dest_code;
	$check_dest = check_dest($dest_code);
	var_dump($check_dest);
	if (empty($check_dest)) {
		echo 'need to crawl';
		$result = crawl_hotel_info_with_loc($dest_code);	
		//var_dump($result);exit;	
		foreach ($result as $k => $v) {
            $address     = $result[$k]['address'];
            $zumata_id   = $result[$k]['id'];
            $location_id = $dest_code;
            $lat         = $result[$k]['latitude'];
            $lng         = $result[$k]['longitude'];
            $description = $result[$k]['description'];
            $status      = 0;
            $result_id   = check_existing($zumata_id, $location_id);
            
            if ($result_id) {
                $dbh       = getdbh();
                $statement = "UPDATE `hotels`.`t_property` SET `description` = '$description' WHERE `t_property`.`property_id` = '$result_id'";
                $sql       = $dbh->prepare($statement);
                $sql->execute();
            } else {
                $data     = $result[$k];
                $insertID = scrap_to_table($result[$k], $location_id);
                echo $$zumata_id;
                echo "<br/>";
                echo $insertID;
                echo "<hr/>";
            }
        }
	}
	
}


function check_dest($dest_code){
	$dbh       = getdbh();
    $statement = "SELECT id FROM `t_destination` WHERE `dest_id` LIKE '$dest_code'";
    $sql       = $dbh->prepare($statement);
    $sql->execute();
    $result = $sql->fetchAll();
    return $result;
}

function crawl_hotel_info_with_loc($dest_code){
	$client               = new Client();
	$loc_response = $client->get('http://data.zumata.com/destinations/'.$dest_code.'/en_US/short.json');
	$location_result   = $loc_response->json();
	return $location_result;
}

function scrap_to_table($data, $location_id)
{
    $dbh           = getdbh();
    $location_id   = $location_id;    
    $zumata_id 	   = $data['id'];    
    $name    	   = $data['name'];
    $address 	   = $data['address'];    
    $description   = $data['description'];
    $latitude      = $data['latitude'];
    $longitude     = $data['longitude'];
    $postal_code   = $data['postal_code'];
    $rating        = $data['rating'];
    $phone         = $data['phone'];
    $email         = $data['email'];
    $website       = $data['website'];
    $image_details = serialize($data['image_details']);
    $amenities     = serialize($data['amenities']);
    $status        = 1;
    
    $location_id = $location_id;
    $statement   = "INSERT INTO `t_property`( `zumata_property_id`, `property_name`, `address`, `location_id`,`description`,`image_details`,`lat`,`lng`,`rating`,`phone`,`email`,`website`,`created_dt`, `updated_dt`) 
		VALUES (?,?,?,?,?,?,?,?,?,?,?,?,NOW(),NOW())";
    
    $sql = $dbh->prepare($statement);
    $sql->execute(array($zumata_id,$name,$address,$location_id,$description,$image_details,$latitude,$longitude,$rating,$phone,$email,$website));
    $last_id = $dbh->lastInsertId();
    
    $statementTwo = "INSERT INTO `t_property_amenities`(`zumata_id`, `amenities`, `created_dt`, `updated_dt`) 
		VALUES (?,?,NOW(),NOW())";
    $sql          = $dbh->prepare($statementTwo);
    $sql->execute(array(
        $zumata_id,
        $amenities
    ));
    return $last_id;
    
    
}

function check_existing($zumata_id, $location_id)
{
    $dbh       = getdbh();
    $statement = "SELECT property_id  FROM `t_property` WHERE `zumata_property_id` LIKE ? LIMIT 1";
    $sql       = $dbh->prepare($statement);
    $sql->execute(array(
        $zumata_id
    ));
    $result = $sql->fetch();
    return $result['property_id'];
}
?>