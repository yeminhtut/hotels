<?php
use GuzzleHttp\Client; 
	function _booking_result(){

		$booking_key = $_POST['booking_key'];
		$salutation = $_POST['salutation'];
		$first_name = $_POST['first_name'];
		$last_name = $_POST['last_name'];
		$email = $_POST['email'];
		$credit_card_number = $_POST['credit_card_number'];
		$name_on_card = $_POST['name_on_card'];
		$credit_month = $_POST['credit_month'];
		$credit_year = $_POST['credit_year'];
		$credit_month = $_POST['credit_month'];
		$secruity = $_POST['secruity'];
		$billing_city = $_POST['billing_city'];
		$billing_country = $_POST['billing_country'];
		$billing_postal = $_POST['billing_postal'];		
		$billing_address = $_POST['billing_address'];
		
		$result = store_booking($booking_key,$salutation,$first_name,$last_name,$email,$credit_card_number,$name_on_card,$credit_month,$credit_year,$secruity,$billing_city,$billing_country,$billing_postal,$billing_address);
		var_dump($result);
		

}
function store_booking($booking_key,$salutation,$first_name,$last_name,$email,$credit_card_number,$name_on_card,$credit_month,$credit_year,$secruity,$billing_city,$billing_country,$billing_postal,$billing_address)
{
    $dbh           = getdbh();    
    
    $location_id = $location_id;
    $statement   = "INSERT INTO `t_booking_info`( `booking_key`, `salutation`, `first_name`, `last_name`, `email`, `credit_card_number`, `name_on_card`, `credit_month`, `credit_year`, `secruity`, `billing_address`, `billing_city`, `billing_country`, `billing_postal`, `created_dt`)
    				 VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?,NOW())";
    
    $sql = $dbh->prepare($statement);
    $sql->execute(array($booking_key,$salutation,$first_name,$last_name,$email,$credit_card_number,$name_on_card,$credit_month,$credit_year,$secruity,$billing_address,$billing_city,$billing_country,$billing_postal));
    $last_id = $dbh->lastInsertId();   
    
    return $last_id;
}
