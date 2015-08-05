<?php 
function _insert_hotels_temp() {
	$room_key = $_POST['room_key'];
    $room_des = $_POST['room_des'];
    $price = $_POST['price'];
    $hotel_id = $_POST['hotel_id'];
    $hotel_img = $_POST['hotel_img'];
    $hotel_name = $_POST['hotel_name'];    
    $result = store_booking_temp($hotel_name, $room_key, $room_des, $price, $hotel_id, $hotel_img);
    echo $result;
}
function store_booking_temp($hotel_name, $room_key, $room_des, $price, $hotel_id, $hotel_img)
{
    $dbh           = getdbh();    
    
    $statement   = "INSERT INTO `booking_temp`(`hotel_name`, `room_key`, `room_des`, `price`, `hotel_id`, `hotel_img`, `created_dt`) 
                    VALUES (?,?,?,?,?,?,NOW())";
    
    $sql = $dbh->prepare($statement);
    $sql->execute(array($hotel_name, $room_key, $room_des, $price, $hotel_id, $hotel_img));
    $last_id = $dbh->lastInsertId();   
    
    return $last_id;
}
?>