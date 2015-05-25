<?php
function _review($directory_id='',$postslug='', $offset='') {  
  
  $company = new Company($directory_id);

  $content['company'] = $company;
  $content['agency_image'] = html_company_thumbnail($company);
  
  $params['agency'] = $company->get('id');
  $package_ids=Package::search($params,$offset,$GLOBALS['pagination']['per_page'],FALSE);
  $total_packages=Package::search($params,'','',TRUE);
  
  $content['make_html']=make_html($package_ids);
  //var_dump($content['make_html']);exit;

  // $filters['sort'] = 'featured';
  // $filters['sort_dir'] = '';
  // $filters['min_day']='';
  // $filters['max_day']='';
  
  // if(isset($_SESSION['search_sort']))
  // 	$filters['sort'] = $_SESSION['search_sort'];
  // if(isset($_SESSION['search_sort_direction']))
  // 	$filters['sort_dir'] = $_SESSION['search_sort_direction'];
  
  $content['total_packages']=$total_packages;
	
	$enquiry_button="disable";
	$microsite=new Microsite();
	$microsite->retrieve_one("cid=?", array($directory_id));
	if($microsite->exists() && $microsite->get('status')=='active'){
		$enquiry_button="enable";
	}
	
	$content['enquiry_button']=$enquiry_button;
 
  $content['pagination_base_url'] = myUrl('directory/review/'.$directory_id.'/'.$postslug);
  $content['pagination']=pagination::makePagination($offset,$total_packages,$content['pagination_base_url'],$GLOBALS['pagination']);  
  $data['body'][]=View::do_fetch(VIEW_PATH.'directory/review.php',$content);
  
  $per_page_title = '';
  if(is_numeric($offset))
  	$per_page_title .= ' - Page '.(($offset / $GLOBALS['pagination']['per_page']) + 1);
  
  $data['page_title'] = $content['company']->get('display_name').' Tour Packages'.$per_page_title;
  $data['meta_description'] = 'Travel with convenience and peace of mind with '.$content['company']->get('display_name').$per_page_title;
  
  View::do_dump(VIEW_PATH.'layouts/layout.php',$data);
}

//=======================================
// Private Functions
//=======================================
function make_html($package_ids=''){
  if($package_ids=='')
  {
     $html='<div class="alert alert-warning text-center">We are not able to find any package that meets your search criteria.<br />You may want to refine your search and try again.</div>'; 
  }
  
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
    //return $html;
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
      $expert='';
      $package_destination=new Package_Destination();
      $package_destinations=$package_destination->retrieve_many("package_id=?",$row['id']);
      $countries_expert=array();
      
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
          <a class="pkg_btn_lnks" href="/tour/enquire/'.$package_id.'/">
                <img id="pkg_email_btn" src="/img/email.png" width="15px"  alt="Email Agency"  title="Email Agency" style="cursor:pointer"/>
              </a>
              <a class="pkg_btn_lnks" href="/tour/package/'.$package_id.'/">
                <img id="pkg_view_btn" src="/img/view.png" width="20px"  alt="View Itinerary"  title="View Itinerary" style="cursor:pointer"/>
              </a>  
            </div>
            <div class="clear" style="clear:both;"></div>       
        </li>
    ';
    
    }
  }else
    return false;
  
  return $html;
}
function html_company_thumbnail($company){
  $file=new File();
  $file->retrieve_one('reference_id=? and reference_type=? and type=?', array($company->get('id'), 'company', 'image'));
  
  $thumb_url=FILES_PATH.'no_image.jpg';
  if($file->exists())
    $thumb_url=FILES_PATH.$file->get('id').'.'.$file->get('extension').'';
  
  $html='<img src="'.$thumb_url.'" alt="'.$company->get('display_name').'" width="154" height="auto"/>';
  
  return $html;
}

function thumb_url($id){
  if(!isset($id) || !is_numeric($id) || empty($id))
    $thumb_url=FILES_PATH.'no_image.jpg';
  
  $file=new File();
  $file->retrieve_one('reference_id=? and reference_type=? and type=?', array($id, 'package', 'image'));
  
  $thumb_url=FILES_PATH.'no_image.jpg';
  if($file->exists())
    $thumb_url=FILES_PATH.$file->get('id').'.'.$file->get('extension').'';
  else{
    $country_id=package_countryid($id);
    
    $file=new File();
    $file->retrieve_one('destination_id=? and reference_type=? ORDER BY RAND()', array($country_id, 'country'));
    
    $thumb_url=FILES_PATH.'no_image.jpg';
    if($file->exists())
      $thumb_url=FILES_PATH.$file->get('id').'.'.$file->get('extension').'';
  }
  //$thumb_url = 'bla';
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