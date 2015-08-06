<?php 
function _insert_hotels_temp() {
    
    $room_key = $_POST['room_key'];
    $room_des = $_POST['room_des'];
    $price = $_POST['price'];
    $hotel_id = $_POST['hotel_id'];
    $hotel_img = $_POST['hotel_img'];
    $hotel_name = $_POST['hotel_name'];    
    $checkinDate = DateTime::createFromFormat('d-m-Y', $_POST['checkin']);
    $check_in =  $checkinDate->format('Y-m-d');
    $checkoutDate = DateTime::createFromFormat('d-m-Y', $_POST['checkout']);
    $check_out =  $checkoutDate->format('Y-m-d');    
    $persons = $_POST['persons'];
    $rooms = $_POST['rooms'];
    
    $result = store_booking_temp($hotel_name, $room_key, $room_des, $price, $hotel_id, $hotel_img,$check_in,$check_out,$persons,$rooms);
    
    echo $result;
}
function store_booking_temp($hotel_name, $room_key, $room_des, $price, $hotel_id, $hotel_img,$check_in,$check_out,$persons,$rooms)
{
    $dbh           = getdbh();

    $statement = "INSERT INTO `booking_temp`( `hotel_name`, `room_key`, `room_des`, `price`, `hotel_id`, `hotel_img`, `check_in`, `check_out`,`persons`, `rooms`, `created_dt`) 
    VALUES (?,?,?,?,?,?,?,?,?,?,NOW())";
    $sql = $dbh->prepare($statement);
    $sql->execute(array($hotel_name, $room_key, $room_des, $price, $hotel_id, $hotel_img,$check_in,$check_out,$persons,$rooms));
    $last_id = $dbh->lastInsertId();
    return $last_id;  
}
?>