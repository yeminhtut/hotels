<?php 
function _retrieve_locations() {
	$dbh = getdbh();
	$limit = 10;

	$term = trim($_POST['term']);
	
	$return_arr = array();
	$temp_arr = array();
	
	$sql = 'SELECT `destination`.`name` FROM `destination` WHERE `name` LIKE ? ORDER BY `destination`.`name` LIMIT '.$limit;
	$sth = $dbh->prepare($sql);
	$sth->execute(array('%'.$term.'%'));
	$countries = $sth->fetchAll();
	foreach($countries as $row)
	{
		$row_array['value'] = $row['name'];
		$row_array['to_city'] = '';
		$row_array['to_country'] = $row['name'];
	
		$temp_arr[$row['name']]=$row_array;
	}
	$ctr = 0;
	foreach($temp_arr AS $arr) {
		if($ctr<$limit)
			array_push($return_arr,$arr);
		$ctr++;
	}
	
	echo json_encode($return_arr);
}