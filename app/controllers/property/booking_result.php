<?php
use GuzzleHttp\Client; 
	function _booking_result(){

		var_dump($_POST);exit;

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

		// $booking = [
		//    "booking_key"=>"3e395b6490f88a5f",
		//    "guest"=>[
		//       "salutation"=>"Mr.",
		//       "first_name"=>"Robert",
		//       "last_name"=>"Paterson",
		//       "email"=>"ye.minhtut@travelogy.com",
		//       "street"=>"123 Singapore St",
		//       "city"=>"Beverly Hills",
		//       "state"=>"California",
		//       "postal_code"=>"90210",
		//       "country"=>"USA",
		//       "room_remarks"=>"Baby's crib"
		//    ],
		//    "payment"=>[
		//       "type"=> "credit_card",
		//       "contact"=>[
		//          "first_name"=>"robert",
		//          "last_name"=>"paterson",
		//          "email"=>"ye.minhtut@travelogy.com",
		//          "street"=>"123 Singapore St",
		//          "city"=>"Beverly Hills",
		//          "state"=>"California",
		//          "postal_code"=>"90210",
		//          "country"=>"USA"
		//       ],
		//       "details"=>[
		//          "card"=>"4111111111111111",
		//          "cvv"=>"123",
		//          "expiration_month"=>"08",
		//          "expiration_year"=>"2020",
		//          "amount"=>100,
		//          "currency"=>"SGD"
		//       ]
		//    ]
		// ];
		// $data = json_encode($booking);
		// $client               = new Client();
		// $client->setDefaultOption('verify', false);
		// $url = 'https://api.zumata.com/staging/book?api_key=rEnlPVvPD6V87RstUqEeoFjaQZt5GnFbNFxwyi2P';
		// $response = $client->post($url, ['json' => $booking]);
		// $result   = $response->json();
		// var_dump($result);echo "<hr/>";
		// var_dump($response);

}
