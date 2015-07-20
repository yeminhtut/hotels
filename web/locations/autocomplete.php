<?php 
	$term = trim($_POST['term']);
	echo json_encode($term);exit;
	$data_array[] = array(
			'value' => 'test val',
			'hotel' => 'test name',
			'lat' => 'test lat',
			'lng' => 'test lng',
			'city' =>	'test city',
			'country' => 'test country',
			'country_code' => 'test country_code',
			'search_type' => 'test search type'
		);

	echo json_encode($data_array);

 ?>