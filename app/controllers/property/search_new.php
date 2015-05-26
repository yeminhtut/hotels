<?php 
	function _search_new(){
		$where = $_POST['where'];
		$check_in = date("d-m-Y", strtotime($_POST['check-in-date']));
		$check_out = date("d-m-Y", strtotime($_POST['check-out-date']));
		//$_SESSION['checkIn']= trim($_POST['check-in-date']);
		$guests = $_POST['no_of_guests'];
		$rooms = $_POST['no_of_rooms'];		
		$check_destination = new Destination();
		$check_destination->retrieve_one("dest_id=?", $where);	
		if (isset($check_destination)) {
			$location_id = $check_destination->dest_id;
			$location_name = str_replace(' ', '-', strtolower($check_destination->city_name));
			redirect('/destination/'.$location_id.'/'.$location_name.'/'.$check_in.'/'.$check_out.'/'.$guests.'/'.$rooms);
		}
	}