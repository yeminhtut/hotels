<?php 
function _continent($continent=FALSE) {
	
	if(!$continent)
		redirect('');
	
	$continent = str_replace('-',' ',$continent);
	$content['continent'] = $continent;
	$check_destination = new Destination();
	$check_destination->retrieve_one("name=?", $continent);
	$continent_id = $check_destination->get('id');
	$continent_url = str_replace(' ', '-', $continent);	
	//$countries_arr = Package::get_all_package_destinations($continent_url);
	$countries_arr = get_all_country_package($continent_id);	
	$content['make_html']=make_country_html($countries_arr,$continent_url);	
	$data['body'][]=View::do_fetch(VIEW_PATH.'destination/continent.php',$content);	
	$data['page_title'] = 'Tour Packages from Singapore to Asia, Europe, Americas, Africa, Oceania';
	$data['meta_description'] = 'Thousands of Tour Packages from Singapore to Asia, Europe, Americas, Africa, Oceania. Find Free & Easy, Group Tour, Muslim Tour, and Cruises to popular and exotic countries.';
	View::do_dump(VIEW_PATH.'layouts/layout.php',$data);
}

function make_country_html($countries_arr,$continent_url){
	$html='';
	foreach ($countries_arr as $country) {
		$destination_id = $country['destination_id'];
		
		$params['destination']=$destination_id;
		$pkgs_count=Package::search($params,'','',TRUE);
		$country_name = $country['name'];
		$thumb_url = thumb_url($destination_id);
		$html .='<div class="span2 continent" style="min-height:150px;background-color:#FFF;border:2px solid #F5F6F7;margin-left:10px;">
			<a href="'.$continent_url.'/'.url_title($country_name).'">
				<img src="'.$thumb_url.'" width="175" height="110"/>
			</a>
			<div style="float: left; width: 125px;" class="left"><a href="'.$continent_url.'/'.url_title($country_name).'">'.$country_name.'</a></div>
			<div style="float: left; width: 35px;" class="right">'.$pkgs_count.'</div>
		  	</div>
	      ';
	}
	return $html;
}
function thumb_url($id){
  if(!isset($id) || !is_numeric($id) || empty($id))
    $thumb_url="http://tripzilla.sg/files/no_image.jpg";
  
  $file=new File();
  $file->retrieve_one('destination_id=? and reference_type=?', array($id, 'country'));
  
  $thumb_url=FILES_PATH."no_image.jpg";
  if($file->exists())
    $thumb_url=FILES_PATH.$file->get('id').".".$file->get('extension');
  
  return $thumb_url;
}
function get_all_country_package($continent_id){
    $dbh = getdbh();   
      
  	if ($continent_id == 1) {        
	    $sql_filter = 'AND destination.parentid NOT IN (2,3,4,5,6,8)';        
	    $sql_filter .= ' AND destination.type = \'country\'';
  	} 
  	else{
	    $sql_filter = ' AND destination.parentid = '.$continent_id.'';
	    $sql_filter .= ' AND destination.type = \'country\'';
  	}
  
    $statement="SELECT DISTINCT package_destination.destination_id,destination.parentid,destination.name,COUNT(DISTINCT package.id) AS pkgscount 
              FROM package_destination LEFT JOIN package ON package.id = package_destination.package_id 
              LEFT JOIN destination ON destination.id = package_destination.destination_id 
              WHERE package.status = 'active' AND flags != 'draft' AND package.valid_to > '".date('Y-m-d')."'
              "; 
    $statement .= $sql_filter;
    $statement .= ' GROUP BY destination.name ORDER BY COUNT(DISTINCT package.id) DESC';     
    $sql = $dbh->prepare($statement);
    $sql->execute();
    $result=$sql->fetchAll();    
    return $result;    
  }

