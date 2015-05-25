<?php
function _destination_pkgs_sort() {
  if($_SERVER['REQUEST_METHOD']=='POST'){   
    $type = (isset($_POST['type'])) ? $_POST['type'] : '';
    echo $type;exit; 
    //=======================================
    // Config & Prework
    //=======================================
    $offset='';
    $expert_pkgids=false;
    $params=$sort_param=array();
    $on_submit = isset($_POST['on_submit'])?$_POST['on_submit']:'no';
    $type = (isset($_POST['type'])) ? $_POST['type'] : '';
    $tour_agency = (isset($_POST['tour_agency'][0])) ? $_POST['tour_agency'][0] : '';
    $pagination_url = pagination_url($type, $tour_agency);
    
    //=======================================
    // Inputs & Sanitisation
    //=======================================
    $country_name=((isset($_SESSION['where']))?$_SESSION['where']:'');
    
    
    
    /*SEARCH*/
    //by destination
    if(isset($_POST['tour_destination']) && $_POST['tour_destination']!=''){
      list($destination_id, $destination_name)=explode('|', $_POST['tour_destination']);
      if(isset($destination_id) && (int)(str_replace(',', '', $destination_id))){
        $params['destination']=$destination_id;
        $pagination_url = pagination_url($type, $tour_agency,$destination_name);
      }
    }
    
   
    $package_ids=Package::search($params,$offset,$GLOBALS['pagination']['per_page'],FALSE);
    
    $total_packages=Package::search($params,'','',TRUE);
    
    //=======================================
    // Business Logic
    //=======================================
    //for listing html
    $make_html=make_html($package_ids);
    
    //for pagination
    $pagination=pagination::makePagination('',$total_packages,$pagination_url,$GLOBALS['pagination']);
    
    // if($country_name!='')
    //   $subheading=subheading($params,$country_name);
    // else{    
    //   $subheading="Showing tour packages to worldwide destinations";
    // }
    //=======================================
    // View
    //=======================================
    echo $make_html;
    echo '<li style="text-align:center">'.$pagination.'</li>';
    // if($on_submit=="no"){
    //   if(!isset($_SESSION['mobile']))
    //     echo '<h1>'.$subheading.'</h1>';
    //   echo $make_html;
    //   echo '<li style="text-align:center">'.$pagination.'</li>';
    // }else
    //   echo "submit";
  }
}

//=======================================
// Private Functions
//=======================================
function pagination_url($from, $comp_id, $param1=FALSE, $param2=FALSE){
  if($from =='directory'){
    $company = new Company($comp_id);    
    if ($param1 && $param2)
      $pagination_url = myUrl('directory/review/'.$company->get('id').'/'.makeslug($company->get('display_name')).'/'.makeslug($param1).'/'.makeslug($param2));
    else if ($param1)
      $pagination_url = myUrl('directory/review/'.$company->get('id').'/'.makeslug($company->get('display_name')).'/'.makeslug($param1));
    else
      $pagination_url = myUrl('directory/review/'.$company->get('id').'/'.makeslug($company->get('display_name')));
      
  }else if ($from =='microsite'){
    $microsite=new Microsite();
    $microsite->retrieve_one("cid=?",array($comp_id));
    if ($param1 && $param2)
      $pagination_url = myUrl($microsite->get('folder_name').'/packages/'.makeslug($param1).'/'.makeslug($param2));
    else if ($param1)
      $pagination_url = myUrl($microsite->get('folder_name').'/packages/'.makeslug($param1));
    else
      $pagination_url = myUrl($microsite->get('folder_name').'/packages');
      
  }else{
    
    if ($param1 && $param2)
      $pagination_url = myUrl('travel/packages/'.makeslug($param1).'/'.makeslug($param2));
    else if ($param1)
      $pagination_url = myUrl('travel/packages/'.makeslug($param1));
    else
      $pagination_url = myUrl('travel/packages');

  }
  
  return $pagination_url;
}

//returns array of tour package ids, one from each expert
function get_expert_pkgids($type, $keyword, $params, $offset='') {
  if(!$keyword)
    return false;
  
  $get_destination=new Destination($params['destination']);
  $get_destinations=$get_destination->get_parents();
  
  $destinations_arr=array();
  foreach($get_destinations as $get_destination)
    $destinations_arr[]=$get_destination->get('id');
  
  $add_stm="";
  if(!empty($destinations_arr))
    $add_stm=" OR country_id IN (".implode(",", $destinations_arr).") ";
  
  $dest_expert=new Destination_Expert();
  $arr=$dest_expert->select('GROUP_CONCAT(cid) cids',"(keyword=? $add_stm ) AND (? BETWEEN start_date AND end_date) AND Status='active'", array($keyword, date('Y-m-d')));
  $cids = $arr[0]['cids'];
  if (!$cids)
    return false;
  
  if($type!="microsite")
    $params['agency'] = $cids; //TODO: what if agency param is already set?

  $package_ids=Package::search($params,'','',FALSE);
  
  $pkgids = array();
  if($package_ids!=''){
    $package_listings=package_listing($package_ids);
    shuffle($package_listings);
    foreach($package_listings as $package_listing){
      if(!isset($country_expert_companies[$package_listing['cid']])){
        $pkgids[]=$package_listing['id'];
        $country_expert_companies[$package_listing['cid']] = $package_listing['cid'];
      }
    }
  }
    
  return $pkgids;
}

function expert_tag($country_name){
  $expert_tag=array(
    "Vietnam" => "icon-vn",
    "Taiwan" => "icon-twn",
    "Thailand" => "icon-th",
    "South Korea" => "icon-skr",
    "Singapore" => "icon-sin",
    "Philippines" => "icon-ph",
    "Nepal" => "icon-nep",
    "Malaysia" => "icon-my",
    "Mauritius" => "icon-mau",
    "Maldives" => "icon-mal",
    "Laos" => "icon-lao",
    "Japan" => "icon-jap",
    "India" => "icon-ind",
    "Indonesia" => "icon-id",
    "Hong Kong" => "icon-hk",
    "Germany" => "icon-ger",
    "Europe" => "icon-eur",
    "China" => "icon-chi",
    "Cambodia" => "icon-cam",
    "Bhutan" => "icon-bhu",
    "Australia" => "icon-aus", 
    "Sabah" => "icon-sabah"
  );
  
  return $expert_tag[$country_name];
}

function thumb_url($id){
  if(!isset($id) || !is_numeric($id) || empty($id))
    $thumb_url=FILES_PATH.'no_image.jpg';
  
  $file=new File();
  $file->retrieve_one('reference_id=? and reference_type=?', array($id, 'package'));
  
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
  
  return $thumb_url;
}

function package_countryid($id){
  $dbh=getdbh();
  
  $statement="SELECT destination_id FROM package_destination LEFT JOIN destination ON destination.id=package_destination.destination_id WHERE package_destination.package_id=? AND destination.type='country' ORDER BY RAND() LIMIT 1";
  
  $sql=$dbh->prepare($statement);
  $sql->execute(array($id));
  $result=$sql->fetch(PDO::FETCH_COLUMN);
  
  return $result;
}

function make_html($package_ids=''){
  if($package_ids=='')
    $html='<div class="alert alert-warning text-center">We are not able to find any package that meets your search criteria.<br />You may want to refine your search and try again.</div>';
  else
    if(isset($_SESSION['mobile'])){
      $listing=package_listing($package_ids);
      $html=package_html_mobile($listing);
    }else{
      $listing=package_listing($package_ids);
      $html=package_html_desktop($listing);
    }
  
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
        $price ='<small>from</small><div class="travel_price">'.$price.'</div> ';
      $company=new Company($row['cid']);
      $thumb_url=thumb_url($row['id']);
      $expert='';
      $package_destination=new Package_Destination();
      $package_destinations=$package_destination->retrieve_many("package_id=?",$row['id']);
      $countries_expert=array();
      
      foreach($package_destinations as $package_destination)
        $countries_expert[]=$package_destination->get('destination_id');
      
      $company_expert=new Destination_Expert();
      if(!empty($countries_expert)){
        $company_expert->retrieve_one('country_id IN ('.implode(',', $countries_expert).') AND status="active" AND cid=? AND (? BETWEEN start_date and end_date)', array($row['cid'], date('Y-m-d')));
        
        if($company_expert->exists()){
          $tag_destination=new Destination($company_expert->get('country_id'));
          $expert_tag=expert_tag($tag_destination->get('name'));
          $expert='<div class="icon-white"></div><div title="'.$tag_destination->get('name').' Expert" class="icon-experts"><span aria-hidden="true" class="'.$expert_tag.'" style="font-size: 80px; color: #F7941E;"></span><div class="icon-hollow"></div></div>';
        }
      }
    
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

