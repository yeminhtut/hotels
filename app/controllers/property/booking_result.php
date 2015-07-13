<?php
use GuzzleHttp\Client; 
	function _booking_result(){

		// $booking_key = $_POST['booking_key'];
		// $salutation = $_POST['salutation'];
		// $first_name = $_POST['first_name'];
		// $last_name = $_POST['last_name'];
		// $billing_city = $_POST['billing_city'];
		// $billing_country = $_POST['billing_country'];
		// $billing_postal = $_POST['billing_postal'];
		// $email = $_POST['email'];
		// $billing_address = $_POST['billing_address'];
		// $booking = array('booking_key'=>$booking_key);
		// $booking = array(
		// 	'booking_key'=> $booking_key,
		// 	'guest'=>array(
		// 		'salutation'=> $salutation,
		// 		'first_name'=> $first_name,
		// 		'last_name' => $last_name,
		// 		'email' => $email,
		// 		'street' => '',
		// 		'city' => $billing_city,
		// 		'country' => $billing_country,
		// 		'postal_code' => $billing_postal
		// 		),
		// 	'payment'=>array(
		// 		"type"=> "credit_card",
		// 	    "contact"=>array(
		// 	        "first_name"=> $first_name,
		// 	        "last_name"=> $last_name,
		// 	        "email"=> $email,
		// 	        "street"=>$billing_address,
		// 	        "city"=> $billing_city,
		// 	        "state"=>'',
		// 	        "postal_code"=> $billing_postal,
		// 	        "country"=> $billing_country
		// 	        )
		// 		),
		// 	"details"=>array(
		//         "card"=>"4111111111111111",
		//         "cvv"=>"123",
		//         "expiration_month"=>"08",
		//         "expiration_year"=>"2020",
		//         "amount"=>100,
		//         "currency"=>"SGD"
		//         )
		// 	);
		//echo json_encode($booking);
}
	// $client  = new Client();
 //    $response = $client->post('https://api.zumata.com/book?api_key=rEnlPVvPD6V87RstUqEeoFjaQZt5GnFbNFxwyi2P');
 //    $result   = $response->json();
 //    var_dump($result);echo "<hr/>";
 //    var_dump($response);


	$agents = array(
          'Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1; SV1; .NET CLR 1.0.3705; .NET CLR 1.1.4322)',
          'Mozilla/5.0 (Windows; U; Windows NT 5.1; en-GB; rv:1.9.1.1) Gecko/20090715 Firefox/3.5.1 (.NET CLR 3.5.30729)',
          'Mozilla/5.0 (X11; Ubuntu; Linux x86_64; rv:13.0) Gecko/20100101 Firefox/13.0.1',
          'Mozilla/5.0 (Windows NT 6.1; rv:12.0) Gecko/20100101 Firefox/12.0'
    );
  	shuffle($agents);
  	
  	$url = 'https://api.zumata.com/book?api_key=rEnlPVvPD6V87RstUqEeoFjaQZt5GnFbNFxwyi2P'; 	
  	$url = trim($url);
  	echo $url;"<hr/>";
	$curl = curl_init();
	curl_setopt($ch,CURLOPT_URL,$url);
    curl_setopt($ch,CURLOPT_POST, 1);                //0 for a get request
    //curl_setopt($ch,CURLOPT_POSTFIELDS,$postvars);
    curl_setopt($ch,CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch,CURLOPT_CONNECTTIMEOUT ,3);
    curl_setopt($ch,CURLOPT_TIMEOUT, 20);
    $response = curl_exec($ch);
	print "curl response is:" . $response;
 //  curl_close ($ch);	
	// curl_close($curl);
	// $result = json_decode($response);
 //  	var_dump($result);
//=======================================
// Business Logic
//=======================================  

//=======================================
// View
//=======================================
