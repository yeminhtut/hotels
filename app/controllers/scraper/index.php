<?php
use GuzzleHttp\Client;
function _index()
{
    
    // $client = new Client();
    // $response = $client->get('http://api.zumata.com/autosuggest/malaysia?api_key=rEnlPVvPD6V87RstUqEeoFjaQZt5GnFbNFxwyi2P');
    // $result = $response->json();	
    
    
    $locationIdArr = array(
        '659197a6-62c1-4636-768b-3fcb2ebc0ecf',
        '5fcce326-06e9-4991-5438-8d82bbcff759',
        '2e93aac3-0b3c-42d9-5d3f-585d0c309680',
        'b679803d-8b3f-4c43-7134-810729ed4c28',
        '113442e0-3a0b-474f-5175-09131f8d1bf6',
        '81998095-cdff-42ca-67f1-e6bd63336c09',
        'fc8b942d-391d-4d8b-7d15-34a109b9e55f',
        'daad9f96-78b7-4835-7079-16a21b3e8cd7',
        '3c2ca084-1679-40c5-69de-75fa316ce93b',
        '7d960051-b87b-4e00-6004-94ab028a9836',
        '5fc09d0b-4624-46f9-7aa5-249e336fc4fb',
        'c54d81a7-ca5b-4186-6ca9-7129285ae3e2',
        '373f2864-029e-4581-51e6-06612aca599d',
        'e45296c0-6d5e-416f-7102-c7b782520b48',
        'eccb1972-4a41-4454-548c-0c4da76546f6',
        'f21ec08a-ba70-4017-7634-31187ecb9cd2',
        '7747d433-77dd-42fe-5f7f-9b9cf6debb7d',
        '2b523177-9386-4de5-6191-38a679aba1f9',
        '8950887c-d0aa-4d6e-52a2-38653b505afd',
        '90b49e00-13d6-49f5-5e83-30a8b358b89c',
        'a6f79813-86cd-4f43-6d12-54cf4d336cde',
        'a82211df-c9e9-411f-4eb9-d5b400ac96a7',
        '679982e8-e27f-4b3e-4f7d-f38190da61ca',
        '1f863c72-748e-4bb1-7969-45b32baa1306',
        'b6b89a41-38a2-442d-4d9d-8cdd8bdc0062',
        '0ae0bcde-491c-4519-448d-d2a4a410764b',
        '1d8514f7-1151-48c3-5284-2392a0b9cd0a',
        '68e4466f-e5d3-4f7f-5642-fe99d65ceb21',
        '218233c8-8432-4c80-419d-baabca73856b',
        'aa257c52-7d08-42f9-736b-84129ffb74ef'
    );
    foreach ($locationIdArr as $k => $v) {
        $client   = new Client();
        $response = $client->get('http://data.zumata.com/destinations/' . $locationIdArr[$k] . '/en_US/long.json');
        $result   = $response->json();
        
        $location_id = $locationIdArr[$k];
        foreach ($result as $k => $v) {
            $address     = $result[$k]['address'];
            $zumata_id   = $result[$k]['id'];
            $location_id = $location_id;
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