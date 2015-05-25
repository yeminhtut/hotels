<?php 
function _country($continent='', $country='', $city='', $offset='') {
	// check for cities	
	if(is_numeric($city)) {
		$offset = $city;
		$city = '';
	}
	// echo $continent;echo"<br/>";
	// echo $country;echo"<br/>";
	// echo $city;echo"<br/>";exit;
	// for sidebar filter
	$content['destination_continent_filter'] = $continent;
	$content['destination_country_filter'] = $country;
	$content['destination_city_filter'] = $city;
	$content['destination_agencies_filter'] = array();
	$content['destination_feat_agencies_filter'] = array();
	
	$content['city'] = ucwords(str_replace('-',' ',$city));
	$content['country'] = ucwords(str_replace('-',' ',$country));
	$country_obj2 = new Destination();
	
	$filters['destination'] = $content['country'];
	if($city!='')
		$filters['destination'] = $content['country'].'|'.$content['city'];
	if(isset($_SESSION['search_sort']))
		$filters['sort'] = $_SESSION['search_sort'];
	if(isset($_SESSION['search_sort_direction']))
		$filters['sort_dir'] = $_SESSION['search_sort_direction'];

	if ($city!='') {
		$country_obj2->retrieve_one("name=?", $content['city']);
		$content['destination_cities'] = retrieve_all_cities_with_postings_by_country_id($country_obj2->get('parentid'));
		$content['country_image']= destination_thumb_url($country_obj2->get('parentid'),'country');	
		$content['destination'] = $country_obj2->retrieve_one("name=?", $content['city']);
		$content['destinationID'] = $country_obj2->get('id');
	}
	else{
		$country_obj2->retrieve_one("name=?", $content['country']);
		$content['destination_cities'] = retrieve_all_cities_with_postings_by_country_id($country_obj2->get('id'));
		$content['country_image']= destination_thumb_url($country_obj2->get('id'),'country');
		$content['destinationID'] = $country_obj2->get('id');
		$_SESSION['where'] = $country_obj2->get('name');
		//var_dump($content['destination']);exit;	
	}	
	$params['destination']=$country_obj2->get('id');	
	$package_ids=Package::search($params,$offset,$GLOBALS['pagination']['per_page'],FALSE);	
    $total_packages=Package::search($params,'','',TRUE);
    
    $content['total_packages']=$total_packages;
    
    $content['make_html']=make_html($package_ids);  
    
    //var_dump($content['make_html']);exit;
	// foreach($total_packages as $total_package) {
	// 	$company_result = Company::retrieve_company_by_posting_id($total_package['PostingID']);
	// 	$company = new Company($company_result['CompanyID']);
	
	// 	if($company->Featured == 'y')
	// 		$content['destination_feat_agencies_filter'][$company->CompanyID] = $company->Name;
	// 	else
	// 		$content['destination_agencies_filter'][$company->CompanyID] = $company->Name;
	// }
	// asort($content['destination_feat_agencies_filter']);
	// asort($content['destination_agencies_filter']);
	
	// if(isset($_SESSION['travel_agents']))
	// 	$filters['companies'] = $_SESSION['travel_agents'];
	
	// if(isset($country_obj2) && is_object($country_obj2)) {
	
	// 	$country_experts = Country::retrieve_country_expert($country_obj2->CountryID);
	
	// 	// if country experts is more than one, display each on every page
	// 	$ce_multiple = FALSE;
	// 	if(count($country_experts)>1) {
	// 		$ce_multiple = TRUE;
	// 	}
		
	// 	$ce_limit = count($country_experts);
	
	// 	// Display only $ce_limit country experts every page
	// 	$ce_package_ids = array();
	// 	$ce_offset = '';
	// 	$country_experts_packages = Posting::retrieve_posting($filters, $ce_limit, $ce_offset, TRUE, $ce_multiple);
	// 	if(count($country_experts_packages) < $ce_limit) {
	// 		// try without grouping
	// 		$country_experts_packages2 = array_merge($country_experts_packages, Posting::retrieve_posting($filters, $ce_limit, $ce_offset, TRUE, FALSE));
	// 		$country_experts_packages = array();
	// 		foreach($country_experts_packages2 as $row) {
	// 			$country_experts_packages[$row['PostingID']] = $row;
	// 		}
	// 		if(count($country_experts_packages) > $ce_limit)
	// 			$country_experts_packages = array_slice($country_experts_packages, 0, $ce_limit);
	// 	}
	// 	if(count($country_experts_packages)) {
	// 		shuffle($country_experts_packages);
	// 		foreach($country_experts_packages as $ce_package) {
	// 			$ce_package_ids[$ce_package['PostingID']] = $ce_package['PostingID'];
	// 		}
	// 		$filters['exclude'] = $ce_package_ids;
	// 	}
	
	// }
	
	// // For Pagination Total
	// $content['total_packages'] = Posting::retrieve_posting($filters, '', '', FALSE, FALSE, FALSE, TRUE);
	
	// $content['packages'] = array();
	// $packages = Posting::retrieve_posting($filters, 10, $offset);
	
	// if(isset($country_experts_packages) && count($country_experts_packages)>0) {
	// 	$packages = array_merge($country_experts_packages, $packages);
	// }
	
	// foreach($packages as $k=>$package) {
	// 	$reference_type='posting';
	// 	$reference_id=$package['PostingID'];
		
	// 	$posting_image = TN_PATH.'square/100x100/no_image.jpg';
	// 	if($package['Image_Type']=='custom')
	// 	{
	// 		$file = new File();
	// 		$file = File::retrieve_random_file($reference_id,$reference_type);
				
	// 		if($file!='' && $file->exists())
	// 			$posting_image = TN_PATH.'square/100x100/'.$file->get('FileID').'.'.$file->get('Extension');				
	// 	}
	// 	else
	// 	{
	// 		$country_obj = Country::retrieve_random_posting_country_and_city($reference_id);
	// 		$country_id = $country_obj['CountryID'];
	// 		$file = new File();
	// 		$file = File::retrieve_random_file($country_id,'country');
	// 		if($file!='' && $file->exists())
	// 			$posting_image = TN_PATH.'square/100x100/'.$file->get('FileID').'.'.$file->get('Extension');
	// 	}
			
	// 	$company_result = Company::retrieve_company_by_posting_id($package['PostingID']);
	// 	$company = new Company($company_result['CompanyID']);
		
	// 	$country_expert = '';
	// 	if(isset($package['Country_Expert_Status']) && $package['Country_Expert_Status']=='active') {
	// 		$ce_country = new Country($package['Country_Expert_Country']);
		
	// 		$country_expert = ' ('.$ce_country->get('Name').' Expert)';
	// 	}
		
	// 	$content['packages'][$k] = array(
	// 		'Title' => $package['Title'],
	// 		'PostingID' => $package['PostingID'],
	// 		'Tour_Type' => $package['Tour_Type'],
	// 		'CompanyID' => $package['CompanyID'],
	// 		'Description' => $country_expert,
	// 		'CompanyName' => $company->Name,
	// 		'Currency' => $package['Currency'],
	// 		'Price' => $package['Price'],
	// 		'Image' => $posting_image
	// 	);
	// }
	
	$content['pagination_base_url'] = myUrl($continent.'/'.$country.($city!=''?'/'.$city:''));
	$content['pagination']=pagination::makePagination($offset,$content['total_packages'],$content['pagination_base_url'],$GLOBALS['pagination']);
	
	$data['body'][]=View::do_fetch(VIEW_PATH.'destination/country.php',$content);
	
	$per_page_title = '';
	if(is_numeric($offset))
		$per_page_title .= ' - Page '.(($offset / $GLOBALS['pagination']['per_page']) + 1);
	
	$data['page_title'] = 'Tour Packages from Singapore to '.($city!=''?$content['city'].', ':'').$content['country'].$per_page_title;
	$data['meta_description'] = 'Tour Packages from Singapore to '.($city!=''?$content['city'].', ':'').$content['country'].'. Find Free & Easy, Group Tour, Muslim Tour, and Cruises to '.($city!=''?$content['city'].', ':'').$content['country'].$per_page_title;
	
	View::do_dump(VIEW_PATH.'layouts/layout.php',$data);
}

function retrieve_all_cities_with_postings_by_country_id($country_id){
	$dbh = getdbh();
      
    $statement="SELECT DISTINCT package_destination.destination_id,destination.name,COUNT(DISTINCT package.id) AS pkgscount FROM package_destination 
				LEFT JOIN package ON package.id = package_destination.package_id LEFT JOIN destination ON destination.id = package_destination.destination_id WHERE package.status = 'active' 
				AND destination.parentid = $country_id AND destination.type = 'city' GROUP BY destination.name  ORDER BY `destination`.`name` ASC
              ";    
    $sql = $dbh->prepare($statement);
    $sql->execute();
    $result=$sql->fetchAll();    
    return $result;  
}
function make_html($package_ids=''){
  if($package_ids=='')
    $html='<div class="alert alert-warning text-center">We are not able to find any package that meets your search criteria.<br />You may want to refine your search and try again.</div>';
  else{    
      $listing=package_listing($package_ids);
      $html=package_html_desktop($listing);    
  }
  //return $listing;
  return $html;
}

function package_listing($package_ids=''){
  $dbh=getdbh();
  
  $statement="
  SELECT 
  package.id, 
  package.cid, 
  package.display_title, 
  package.maxday, 
  package.type, 
  package.travel_period_from, 
  package.travel_period_to,
  package.price,
  package.price_usd 
  FROM package WHERE package.id IN ($package_ids) GROUP BY package.id ORDER BY FIELD(package.id, $package_ids) 
  ";
  
  $sql=$dbh->prepare($statement);
  $sql->execute();
  $result=$sql->fetchAll(PDO::FETCH_ASSOC);
  
  return $result;
}

function package_html_desktop($listing=array()){
  if(!empty($listing) && count($listing)>0){
    $html='';
    
    foreach($listing as $row){
      if($row['cid']==0)
        continue;
      
      if (empty($row['price']))
        $price = 'N/A';
      else
        $price = $row['price'];
        $price ='<div class="travel_price">'.$price.'</div> ';
      $company=new Company($row['cid']);
      $thumb_url=thumb_url($row['id']);
      // $expert='';
      // $package_destination=new Package_Destination();
      // $package_destinations=$package_destination->retrieve_many("package_id=?",$row['id']);
      // $countries_expert=array();
      
      // foreach($package_destinations as $package_destination)
      //   $countries_expert[]=$package_destination->get('destination_id');
      
      // $company_expert=new Destination_Expert();
      // if(!empty($countries_expert)){
      //   $company_expert->retrieve_one('country_id IN ('.implode(',', $countries_expert).') AND status="active" AND cid=? AND (? BETWEEN start_date and end_date)', array($row['cid'], date('Y-m-d')));
        
      //   if($company_expert->exists()){
      //     $tag_destination=new Destination($company_expert->get('country_id'));
      //     $expert_tag=expert_tag($tag_destination->get('name'));
      //     $expert='<div class="icon-white"></div><div title="'.$tag_destination->get('name').' Expert" class="icon-experts"><span aria-hidden="true" class="'.$expert_tag.'" style="font-size: 80px; color: #F7941E;"></span><div class="icon-hollow"></div></div>';
      //   }
      // }
    
		$html .='
                <li>
      <div class="thumb">
                <div class="photo_holder">
                <a href="'.Package::makepermalink($row['id'], $row['display_title']).'">
        <img src="'.$thumb_url.'" width="200" height="128"">
        <a href="'.Package::makepermalink($row['id'], $row['display_title']).'">
      </div>
            </div>
            <div class="desc">
              <a href="'.Package::makepermalink($row['id'], $row['display_title']).'"><b>'.$row['display_title'].'</b></a><br/>
        <div class="travel_decription">
          '.ucwords(str_replace('_', ' ', $row['type'])).'
        </div>
            </div>
            <div class="pkgs_right">
              <small>from</small><div class="travel_price">'.$price.'</div> 
        <div class="travel_agency_name"><a href="/directory/review/'.$company->get('id').'/'.makeslug($company->get('display_name')).'">'.$company->display_name.'</a>         
        </div>  
            </div>
            <div class="clear" style="clear:both;"></div>       
        </li>
    ';
    
    }
  }else
    return false;
  
  return $html;
}
function destination_thumb_url($id,$type){
  if(!isset($id) || !is_numeric($id) || empty($id))
    $thumb_url="http://tripzilla.sg/files/no_image.jpg";
  
  $file=new File();
  $file->retrieve_one('destination_id=? and reference_type=?', array($id, $type));
  
  $thumb_url=FILES_PATH."no_image.jpg";
  if($file->exists())
    $thumb_url=FILES_PATH.$file->get('id').".".$file->get('extension');
  
  return $thumb_url;
}
function thumb_url($id){
  if(!isset($id) || !is_numeric($id) || empty($id))
    $thumb_url=FILES_PATH."no_image.jpg";
  
  $file=new File();
  $file->retrieve_one('reference_id=? and reference_type=? ORDER BY id DESC', array($id, 'package'));
  
  $thumb_url=FILES_PATH."no_image.jpg";
  if($file->exists())
    $thumb_url=FILES_PATH.$file->get('id').".".$file->get('extension');
  else{
    $country_id=package_countryid($id);
    
    $file=new File();
    $file->retrieve_one('destination_id=? and reference_type=? ORDER BY RAND()', array($country_id, 'country'));
    
    $thumb_url=FILES_PATH."no_image.jpg";
    if($file->exists())
      $thumb_url=FILES_PATH.$file->get('id').".".$file->get('extension');
  }
  
  return $thumb_url;
}

function package_countryid($id){
  $dbh=getdbh();
  
  $statement="SELECT destination_id FROM package_destination LEFT JOIN destination ON destination.id=package_destination.destination_id WHERE package_destination.package_id=? AND destination.type='country' ORDER BY RAND() LIMIT 1";
  
  $sql=$dbh->prepare($statement);
  $sql->execute(array($id));
  $result=$sql->fetch(PDO::FETCH_COLUMN);
  
  if($result==''){
  $statement="SELECT destination_id FROM package_destination LEFT JOIN destination ON destination.id=package_destination.destination_id WHERE package_destination.package_id=? AND destination.type='global' ORDER BY RAND() LIMIT 1";
  $sql=$dbh->prepare($statement);
  $sql->execute(array($id));
  $result=$sql->fetch(PDO::FETCH_COLUMN);  
  }
  return $result;
}
