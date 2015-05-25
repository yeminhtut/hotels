<?php 
function _search(){
	if (isset($_POST['where'])) {		
		$_SESSION['where']= trim($_POST['where']);
	}
	$where = $_SESSION['where'];	
	$check_destination = new Destination();
	$check_destination->retrieve_one("dest_id=?", $where);	
	if (isset($check_destination)) {
		$location_id = $check_destination->dest_id;
		$location_name = str_replace(' ', '-', strtolower($check_destination->city_name));
		redirect('/destination/'.$location_id.'/'.$location_name);
	}
	
}


 ?>