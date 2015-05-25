<?php
function _packages($country_name='', $city_name='', $offset='') {
//=======================================
// Config & Prework
//=======================================
  // if ($_SERVER['REQUEST_URI'] == '/travel/packages')
  //   header('Location: '.WEB_DOMAIN.'/travel',TRUE,301);

  $params=array();
  $pagetitle="Find travel packages from Singapore travel agencies";
  $description="Find the best tour packages to destinations like Bangkok, Korea, Europe, Australia, Taiwan, Hong Kong from Singapore travel agencies.";
  $pagination_url = myUrl('travel/packages');
  $tfdestination=$tfcontinent=$tfcountry=$tfcity=$tfkeyword='';
  $search_by_keyword=FALSE;
  $package_type=$package_theme=false;
  /*$dom_int_redirect=false; REMOVE!!*/
  
//=======================================
// Inputs & Sanitisation
//=======================================
  $country_name=ucwords(str_replace('-', ' ', $country_name));
  $city_name=ucwords(str_replace('-', ' ', $city_name));
  
  if(is_numeric($country_name)){
    $offset=$country_name;
    $country_name='';
  }else if(is_numeric($city_name)){
    $offset=$city_name;
    $city_name='';
  }
  
  //City and Country dictionary
  $map_dictionary=map_dictionary($city_name, $country_name);
  if($map_dictionary){
    $city_name=$map_dictionary['city_name'];
    $country_name=$map_dictionary['country_name'];
  }
  //get and set the $_SESSION['where'] as the $search keyword
  if(isset($_SESSION['where']) && $_SESSION['where'] !=''){
    if($country_name=='' && ($country_name!='international' && $country_name!='domestic')){
      if(strpos($_SESSION['where'], ', ')!==FALSE)
        list($city_name, $country_name)=explode(', ', $_SESSION['where']);
      else
        $country_name = $_SESSION['where'];
    }else
      unset($_SESSION['where']);
  }
  
  if(strpos($country_name, "%20")!==FALSE)
    redirect('travel/packages/'.makeslug(str_replace("%20", "-", $country_name)));
  
  //the process of checking keywords
  if(!empty($country_name) && !empty($city_name)){
    $check_city=new Destination();
    $check_city->retrieve_one("name=?", $city_name);
    
    if($check_city->exists()){
      header('Location: '.WEB_DOMAIN.'/travel/packages/'.makeslug($check_city->get('name')),TRUE,301);
      exit;
    }else
      $search_by_keyword=TRUE;
  }else if(!empty($country_name)){
    $check_destination=new Destination();
    $check_destination->retrieve_one("name=?", $country_name);
    
    if($check_destination->exists()){
      $params['destination']=$check_destination->get('id');
      $tfdestination=$check_destination->get('id').'|'.$check_destination->get('name');
      $pagination_url = myUrl('travel/packages/'.makeslug($check_destination->get('name')));
      
      if($check_destination->get('id')==23){
        if(!isset($_SESSION['external_redirect'])){
          $_SESSION['external_redirect']=TRUE; //just create one-time session.So when the users get the url from google, it will auto-redirect to the correct page.
          //$_SESSION['dom_int_redirect']=TRUE; //set the auto-redirect session as "TRUE". /*REMOVE!!*/
        }
      }
      
      if($check_destination->get('type')=="global"){
        $pagetitle='Compare tour packages to '.$country_name;
        $description='Tour Packages to '.$country_name.' from Singapore. Find and compare tour packages to '.$country_name.' from Singapore travel agencies.';
      }else if($check_destination->get('type')=="country"){
        $pagetitle='Compare tour packages to '.$country_name;
        $description='Tour Packages to '.$country_name.' from Singapore. Find and compare tour packages to '.$country_name.' from Singapore travel agencies.';
      }else if($check_destination->get('type')=="city"){
        $check_country=new Destination();
        $check_country->retrieve_one("id=?", $check_destination->get('parentid'));
        
        $pagetitle='Compare tour packages to '.$check_destination->get('name').', '.$check_country->get('name');
        $description='Tour Packages to '.$check_destination->get('name').', '.$check_country->get('name').' from Singapore. Find and compare tour packages to '.$check_destination->get('name').', '.$check_country->get('name').' from Singapore travel agencies.';
      }
    }else
      $search_by_keyword=TRUE;
  }
  
  if($search_by_keyword){
    foreach($GLOBALS['tour_types'] as $k=>$v){
      if( strtolower(str_replace(' ', '_', $country_name)) == $k){
        $_SESSION['package_type']=$k;
        
        if($k=='free_and_easy'){
          $pagetitle='Free and easy travel packages from Singapore travel agencies';
          $description='Find the best free and easy tour packages/pakej percutian to destinations like Bangkok, Korea, Europe, Singapore, Australia, Taiwan, Hong Kong from Singapore travel agencies.';
        }else if($k=='group_tour'){
          $pagetitle='Group travel packages from Singapore travel agencies';
          $description='Find the best group tour packages, pakej percutian to destinations like Bangkok, Korea, Singapore, Europe, Australia, Taiwan, Hong Kong from Singapore travel agencies.';
        }else if($k=='land_tour'){
          $pagetitle='Land tour packages from Singapore';
          $description='Find the best land tour packages/pakej percutian to destinations like Bangkok, Korea, Europe, Singapore, Australia, Taiwan, Hong Kong from Singapore travel agencies.';
        }else if($k=='cruise'){
          $pagetitle='Cruise packages from Singapore';
          $description='Find cruise packages, pakej percutian from Star Cruises, Royal Caribbean, Costa Cruises, Azamara Club Cruises, Celebrity Cruises, Crystal Cruises, Cunard Cruises, Fred. Oslen Cruise Lines, Hapag-Lloyd Cruises, Holland America Line, Nowegian Cruise Line, Oceania Cruises, Orion Expedition Cruises, P & O Cruises, Ponant, Princess Cruises, Regent Seven Seas Cruises, Saga Cruises, Seabourn Cruises, Silversea Cruises.';
        }
        
        $country_name='';
        $package_type=true;
      }
    }
    
    if(!$package_type){
      foreach($GLOBALS['tour_themes'] as $k=>$v){
        if( strtolower(str_replace(' ', '_', $country_name)) == $k){
          $_SESSION['package_theme']=$k;
          $country_name='';
          $package_theme=true;
        }
      }
    }    
    //checking by keyword on Title
    if(!$package_type || !$package_theme){
      $params['keyword']=$country_name;
      $tfkeyword=$country_name;
      $pagination_url = myUrl('travel/packages/'.makeslug($country_name));
    }
  }  
  //set the new session
  $_SESSION['where']=(!empty($city_name)?$city_name:$country_name);  
  //page title and description for int and dom  
  
  //filter search by travel period
  if(isset($_SESSION['departure']) && $_SESSION['departure']!='' && $_SESSION['departure']!=-1)
    $params['departure']=$_SESSION['departure'];
  
  //filter search by no of days
  if(isset($_SESSION['days']) && $_SESSION['days']!='' && $_SESSION['days']!=-1)
    $params['days']=$_SESSION['days'];
  
  //filter search by no of prices
  if(isset($_SESSION['prices']) && $_SESSION['prices']!='' && $_SESSION['prices']!=-1)
    $params['prices']=$_SESSION['prices'];
  
  //filter search by type
  if(isset($_SESSION['package_type']) && $_SESSION['package_type']!='' && $_SESSION['package_type']!=-1)
    $params['type']=$_SESSION['package_type'];
  
  //filter search by theme
  if(isset($_SESSION['package_theme']) && $_SESSION['package_theme']!='' && $_SESSION['package_theme']!=-1)
    $params['theme']=$_SESSION['package_theme'];
  
  //filter search by agency
  if(isset($_SESSION['package_agency']) && $_SESSION['package_agency']!='' && $_SESSION['package_agency']!=-1)
    $params['agency']=$_SESSION['package_agency'];
  
  //country expert section
  //algo: use search params, get up to 3 packageids who are destination experts
  $expert_pkgids=get_expert_pkgids($country_name,$params);
  
  if($expert_pkgids){
    $params['exclude']=implode(',', $expert_pkgids);
    
    $per_page=$GLOBALS['pagination']['per_page'] - count($expert_pkgids);
    $ce_offset='';
    if($offset!='')
      $ce_offset=($offset / $GLOBALS['pagination']['per_page']) * $per_page;
    $package_ids=Package::search($params,$ce_offset,$per_page,FALSE);
    
    if(!empty($package_ids))
      $package_ids=implode(',', array(implode(',', $expert_pkgids),$package_ids));
  }
  else
    $package_ids=Package::search($params,$offset,$GLOBALS['pagination']['per_page'],FALSE);  
  	$total_packages=Package::search($params,'','',TRUE);
    //echo $total_packages;exit;
	//to display all agencies on the "search filter"
	$company_ids=Package::retrieve_company_by_package();  
  
  //track the keyword search
  if(!isset($_SESSION['sk_nonclear']))
    $_SESSION['sk_nonclear']=$_SESSION['where'];
  $user=User::getUser();
  $user_id=(($user)?$user->get('id'):0);
  $sk_nonclear=((isset($_SESSION['sk_nonclear']))?$_SESSION['sk_nonclear']:'');
  $where=((isset($_SESSION['where']))?$_SESSION['where']:''); 
  
//=======================================
// Business Logic
//=======================================
  
  $content['headerone']=$pagetitle;
  if(empty($country_name)){
    $content['subheading']="Showing tour packages to worldwide destinations";
  }else
    $content['subheading']=subheading($params,$country_name);
  
  //for search filters html
  /*$content['search_tab']=search_tab($_SESSION['dom_int']); REMOVE!!*/
  $content['search_filter_month_of_travel']=search_filter_month_of_travel();
  $content['search_filter_no_of_days']=search_filter_no_of_days();
  $content['search_filter_budget']=search_filter_budget();
  $content['search_filter_type']=search_filter_type();
  $content['search_filter_theme']=search_filter_theme();
  $content['search_filter_agency']=search_filter_agency($company_ids);
  $content['make_html']=make_html($package_ids);
  //var_dump($content['make_html']);exit;
  $content['articles']=retrieve_articles($country_name,$city_name);
  $content['search_dropdown']=search_dropdown();
  $content['header']=$pagetitle;
  
  //for hidden textfield search
  $content['tfdestination']=$tfdestination;
  $content['tfcontinent']=$tfcontinent;
  $content['tfcountry']=$tfcountry;
  $content['tfcity']=$tfcity;
  $content['tfkeyword']=$tfkeyword;  
 
  //for pagination
  $content['total_packages']=$total_packages;
  $content['pagination']=pagination::makePagination($offset,$total_packages,$pagination_url,$GLOBALS['pagination']);
  if(is_numeric($offset))
    $canonical_url = WEB_DOMAIN.$pagination_url;
//=======================================
// View
//=======================================
	$per_page_title = '';
	if(is_numeric($offset))
		$per_page_title .= ' - Page '.(($offset / $GLOBALS['pagination']['per_page']) + 1);
  	$data['page_title'] = 'Tour Packages from Singapore to Asia, Europe, Americas, Africa, Oceania'.$per_page_title;
  	$data['meta_description'] = 'Thousands of Tour Packages from Singapore to Asia, Europe, Americas, Africa, Oceania. Find Free & Easy, Group Tour, Muslim Tour, and Cruises to popular and exotic countries.'.$per_page_title;
  	$data['body'][]=View::do_fetch(VIEW_PATH.'travel/packages.php',$content);
  	View::do_dump(VIEW_PATH.'layouts/layout.php',$data);
}

//=======================================
// Private Functions
//=======================================
//returns array of tour package ids, one from each expert
function get_expert_pkgids($keyword,$params,$offset='') {
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

function retrieve_articles($country_name=false, $city_name=false){
  $dest = '';
  if(isset($city_name) && !empty($city_name) && !is_numeric($city_name)){
    $dest = '?'.strtolower($country_name).'&'.strtolower($city_name);
  }else if(isset($country_name) && !empty($country_name) && !is_numeric($country_name)){
    $dest = '?'.strtolower($country_name);
  }
  $iframe = '
  <h2 class="article">Travel Articles</h2>
  <iframe src="http://magazine.tripzilla.com/widget-page'.$dest.'" class="article_iframe"></iframe>';
  return $iframe;
}

function make_html($package_ids=''){
  if($package_ids=='')
    $html='<div class="alert alert-warning text-center">We are not able to find any package that meets your search criteria.<br />You may want to refine your search and try again.</div>';
  else{
    if(isset($_SESSION['mobile'])){
      $listing=package_listing($package_ids);
      $html=package_html_mobile($listing);
    }else{
      $listing=package_listing($package_ids);
      $html=package_html_desktop($listing);
    }
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
      $package_id = $row['id'];
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
                <li style="padding:10px 0px 10px 0px;border-bottom:1px solid #dbdada;">
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

function package_html_mobile($listing=array()){
  if(!empty($listing) && count($listing)>0){
    $html='';
    
    $num=1;
    foreach($listing as $row){
      $link = "document.location.href = '".Package::makepermalink($row['id'], $row['display_title'])."'";
      $company=new Company($row['cid']);
      $thumb_url=thumb_url($row['id']);
      
      if (empty($row['price']))
        $row['price'] = 'Enquire for price';
      else
        $row['price'] = '<span>From</span> '.$row['price'];
      
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
          $expert='<div class="expert_tag"><b>'.$tag_destination->get('name').' Expert</b></div>';
        }
      }
      
      $html.='
      <div class="item item-avatar" onclick="'.$link.'">
        <div class="right">
          '.$expert.'
          <img src="'.$thumb_url.'" alt="'.$row['display_title'].'" width="115" height="90"/>
        </div>
        <div class="left">
          <h2 class="clamp multiline_clamp">'.$row['display_title'].'<span class="ellipsis">&#133;</span><span class="fill"></span></h2>
          <p class="company"><a href="/directory/review/'.$company->get('id').'/'.makeslug($company->get('display_name')).'">'.$company->get('display_name').'</a></p>
          <p>'.ucwords(str_replace('_', ' ', $row['type'])).'</p>
          <p class="price">'.$row['price'].'</p>
        </div>
        <div style="clear: both;"></div>
      </div>
      ';
      
      if($num==5)
        $html.='<div style="margin:0; padding: 20px; text-align: center; border-bottom: 1px solid #ddd;">'.mobileGoogleAd("top").'</div>';
  
      $num++;
    }
  }else
    return false;
  
  return $html;
}

function search_filter_month_of_travel(){
  $html='';
  $time=time();
  $month=(int)date("m",$time);
  $year=(int)date("Y",$time);
  $index=0;
  $months=array();
  $month_to=(int)($month+12);
  
  for($i=$month;$i<$month_to;$i++){
    if($month>12){
      $month-=12;
      $year++;
    }
    
    $months[$index]['date']=$year."_".$month."_01";
    $months[$index]['display']=date("M",strtotime($year."-".$month."-01"))." ".$year;
    
    if(isset($_SESSION['departure']) && in_array($months[$index]['date'], explode(',',$_SESSION['departure']))) {
      $months[$index]['selected']='selected="selected"';
    }else{
      $months[$index]['selected']='';
    }
    
    $month++;
    $index++;
  }
  
  $html.='<option value="-1">Flexible Dates</option>';
  foreach ($months as $month) {
    $html.='<option value="'.$month['date'].'" '.$month['selected'].'>'.$month['display'].'</option>';
  }
  
  return $html;
}

function search_filter_no_of_days(){
  $html='';
  
  $days_array=array(
  '1-3', 
  '4-7', 
  '8-14', 
  '15-33' 
  );
  
  $html.='<option value="-1">Any</option>';
  foreach($days_array as $day){
    $html.='<option value="'.$day.'" '.((isset($_SESSION['days']) && $_SESSION['days']!='' && $_SESSION['days']!='-1')?($_SESSION['days']==$day)?'selected="selected"':'':'').'>'.$day.'</option>';
  }
  
  return $html;
}

function search_dropdown(){
  $html = '
    <div class="nsearchbox_countries">
    <ul>
      <span class="nsearchbox_cclose">X</span>
      <div class="nsearchbox_column">
        <li class="nsearchbox_continent_int"><a href="/travel/packages/malaysia">Malaysia</a></li>
        <li><a href="/travel/packages/legoland">Legoland</a></li>
        <li><a href="/travel/packages/malaysia/redang">Redang Islands</a></li>
        <li><a href="/travel/packages/malaysia/penang">Penang</a></li>
        <li><a href="/travel/packages/malaysia/malacca">Malacca</a></li>
      </div>
      <div class="nsearchbox_column">
        <li class="nsearchbox_continent_int"><a href="#">&nbsp;</a></li>
        <li><a href="/travel/packages/malaysia/langkawi">Langkawi Islands</a></li>
        <li><a href="/travel/packages/malaysia/pangkor-island">Pangkor Islands</a></li>
        <li><a href="/travel/packages/mount-kinabalu">Mount Kinabalu</a></li>
        <li><a href="/travel/packages/malaysia/genting">Genting Highlands</a></li>
      </div>
      <div class="nsearchbox_column">
        <li class="nsearchbox_continent_int"><a href="#">&nbsp;</a></li>
        <li><a href="/travel/packages/malaysia/cameron-highlands">Cameron Highlands</a></li>
        <li><a href="/travel/packages/malaysia/tioman-island">Tioman Islands</a></li>
        <li><a href="/travel/packages/malaysia/kuching">Kuching</a></li>
        <li><a href="/travel/packages/malaysia/cherating">Cherating Beach</a></li>
      </div>
      
      <div class="nsearchbox_column">
        <li class="nsearchbox_continent_int"><a href="/travel/packages/asia" >Asia</a></li>
        <li><a href="/travel/packages/bhutan">Bhutan</a></li>
        <li><a href="/travel/packages/cambodia">Cambodia</a></li>
        <li><a href="/travel/packages/china">China</a></li>
        <li><a href="/travel/packages/hong-kong">Hong Kong</a></li>
        <li><a href="/travel/packages/india">India</a></li>
        <li><a href="/travel/packages/indonesia">Indonesia</a></li>
        <li><a href="/travel/packages/israel">Israel</a></li>
        <li><a href="/travel/packages/japan">Japan</a></li>
        <li><a href="/travel/packages/laos">Laos</a></li>
        <li><a href="/travel/packages/malaysia">Malaysia</a></li>
        <li><a href="/travel/packages/maldives">Maldives</a></li>
        <li><a href="/travel/packages/nepal">Nepal</a></li>
        <li><a href="/travel/packages/philippines">Philippines</a></li>
        <li><a href="/travel/packages/singapore">Singapore</a></li>
        <li><a href="/travel/packages/south-korea">South Korea</a></li>
        <li><a href="/travel/packages/taiwan">Taiwan</a></li>
        <li><a href="/travel/packages/thailand">Thailand</a></li>
        <li><a href="/travel/packages/vietnam">Vietnam</a></li>
      </div>
      
      <div class="nsearchbox_column">
        <li class="nsearchbox_continent_int"><a href="/travel/packages/europe">Europe</a></li>
        <li><a href="/travel/packages/vatican-city">Vatican City</a></li>
        <li><a href="/travel/packages/norway">Norway</a></li>
        <li><a href="/travel/packages/austria">Austria</a></li>
        <li><a href="/travel/packages/belgium">Belgium</a></li>
        <li><a href="/travel/packages/czech-republic">Czech Republic</a></li>
        <li><a href="/travel/packages/finland">Finland</a></li>
        <li><a href="/travel/packages/france">France</a></li>
        <li><a href="/travel/packages/germany">Germany</a></li>
        <li><a href="/travel/packages/greece">Greece</a></li>
        <li><a href="/travel/packages/hungary">Hungary</a></li>
        <li><a href="/travel/packages/italy">Italy</a></li>
        <li><a href="/travel/packages/netherlands">Netherlands</a></li>
        <li><a href="/travel/packages/portugal">Portugal</a></li>
        <li><a href="/travel/packages/spain">Spain</a></li>
        <li><a href="/travel/packages/sweden">Sweden</a></li>
        <li><a href="/travel/packages/switzerland">Switzerland</a></li>
        <li><a href="/travel/packages/united-kingdom">United Kingdom</a></li>
      </div>
      
      <div class="nsearchbox_column">
        <li class="nsearchbox_continent_int"><a href="/travel/packages/oceania">Oceania</a></li>
        <li><a href="/travel/packages/australia">Australia</a></li>
        <li><a href="/travel/packages/new-zealand">New Zealand</a></li>
        <li class="nsearchbox_continent_int"><a href="/travel/packages/africa">Africa</a></li>
        <li><a href="/travel/packages/mauritius">Mauritius</a></li>
        <li><a href="/travel/packages/morocco">Morocco</a></li>
        <li><a href="/travel/packages/south-africa">South Africa</a></li>
        <li class="nsearchbox_continent_int"><a href="/travel/packages/america">America</a></li>
        <li><a href="/travel/packages/brazil">Brazil</a></li>
        <li><a href="/travel/packages/canada">Canada</a></li>
        <li><a href="/travel/packages/united-states">United States</a></li>
      </div>
    </ul>
    </div>
  ';
  return $html;
}

function search_filter_budget(){
  $html='';
  
  $prices_array=array(
  '1-200'=>'< SGD 200', 
  '200-499'=>'SGD 200 - 499', 
  '500-999'=>'SGD 500 - 999', 
  '1000-1999'=>'SGD 1000 - 1999', 
  '2000-3999'=>'SGD 2000 - 3999', 
  '4000-5000'=>'> SGD 4000' 
  );
  
  foreach($prices_array as $price=>$label){
    $html.='
    <div class="checkbox">
      <label>
        <input ckbx_budget="" name="prices" type="checkbox" id="bck6" value="'.$price.'" '.((isset($_SESSION['prices']) && $_SESSION['prices']!='' && $_SESSION['prices']!='-1')?($_SESSION['prices']==$price)?'checked="checked"':'':'').' > '.$label.'
      </label>
    </div>
    ';
  }
  
  return $html;
}

function search_filter_type(){
  $html='';
  $num=1;
  foreach($GLOBALS['tour_types'] as $k=>$v){
    $html.='
    <div class="checkbox">
      <label>
        <input ckbx_type="" name="tour_type[]" type="checkbox" id="type'.$num.'" value="'.$k.'" '.((isset($_SESSION['package_type']) && $_SESSION['package_type']!='')?(in_array($k, explode(',', $_SESSION['package_type'])))?'checked="checked"':'':'').' /> '.$v.'
      </label>
    </div>
    ';
    $num++;
  }
  
  return $html;
}

function search_filter_theme(){
  $html='';
  $num=1;
  foreach($GLOBALS['tour_themes'] as $k=>$v){
    $html.='
    <div class="checkbox">
      <label>
        <input ckbx_theme="" name="tour_theme[]" type="checkbox" id="theme'.$num.'" value="'.$k.'" '.((isset($_SESSION['package_theme']) && $_SESSION['package_theme']!='')?(in_array($k, explode(',', $_SESSION['package_theme'])))?'checked="checked"':'':'').' /> '.$v.'
      </label>
    </div>
    ';
    $num++;
  }
  
  return $html;
}

function search_filter_agency($company_ids){
  $dbh = getdbh();
  $html='';
  $num=1;
  
  if(!isset($company_ids) && empty($company_ids))
    return false;

  $statement_featured='
  SELECT id, display_name 
  FROM company 
  WHERE id in ('.$company_ids.') AND `flags` LIKE "featured"
  ORDER BY display_name
  ';
  /*
  $statement_nonfeatured='
  SELECT id, display_name 
  FROM company 
  WHERE id in ('.$company_ids.') AND `flags` NOT LIKE "featured"
  ORDER BY display_name
  ';
  */
  $sql = $dbh->prepare($statement_featured);
  $sql->execute();
  $result=$sql->fetchAll(PDO::FETCH_ASSOC);
  
  $html .='<div class="text">Featured</div>';
  foreach($result as $row){
    $html.='
    <div class="checkbox">
      <label>
        <input ckbx_agency="" name="tour_agency[]" type="checkbox" id="agency'.$num.'" value="'.$row['id'].'" '.((isset($_SESSION['package_agency']) && $_SESSION['package_agency']!='')?(in_array($row['id'], explode(',', $_SESSION['package_agency'])))?'checked="checked"':'':'').' /> '.$row['display_name'].'
      </label>
    </div>
    ';
    $num++;
  }
  
  return $html;
}

function mobile_search_filter_1(){ /*No of Days*/
  $html='<label for="days">No. of days</label><select id="days" name="days"><option value="-1">Select No. of days</option>';
  
  $days_array=array(
  '1-3', 
  '4-7', 
  '8-14', 
  '15-33' 
  );
  
  foreach($days_array as $day){
    $html.='<option value="'.$day.'" '.((isset($_SESSION['days']) && $_SESSION['days']!='' && $_SESSION['days']!='-1')?($_SESSION['days']==$day)?'selected="selected"':'':'').'>'.$day.'</option>';
  }
  $html.='</select>';
  
  return $html;
}

function mobile_search_filter_2(){ /*Budgets*/
  $html='<label for="prices">Budget</label><select id="prices" name="prices"><option value="-1">Select Budget</option>';
  
  $prices_array=array(
  '1-200'=>'< SGD 200', 
  '200-499'=>'SGD 200 - 499', 
  '500-999'=>'SGD 500 - 999', 
  '1000-1999'=>'SGD 1000 - 1999', 
  '2000-3999'=>'SGD 2000 - 3999', 
  '4000-5000'=>'> SGD 4000' 
  );
  
  foreach($prices_array as $price=>$label){
    $html.='<option value="'.$price.'" '.((isset($_SESSION['prices']) && $_SESSION['prices']!='' && $_SESSION['prices']!='-1')?($_SESSION['prices']==$price)?'checked="checked"':'':'').' > '.$label.'</option>';
  }
  $html.='</select>';
  
  return $html;
}

function mobile_search_filter_3(){ /*Types*/
  $html='<label for="types">Type</label><select id="types" name="types"><option value="-1">Select Type</option>';
  foreach($GLOBALS['tour_types'] as $k=>$v){
    $html.='<option value="'.$k.'" '.((isset($_SESSION['package_type']) && $_SESSION['package_type']!='')?(in_array($k, explode(',', $_SESSION['package_type'])))?'checked="checked"':'':'').' >'.$v.'</option>';
  }
  $html.='</select>';
  
  return $html;
}

function map_dictionary($city_name, $country_name){
  $city_name=ucwords(str_replace('-', ' ', $city_name));
  $country_name=ucwords(str_replace('-', ' ', $country_name));
  
  $map_dictionary=array(
    /*COUNTRY*/
    'United States' => array('city_name'=>'', 'country_name'=>'USA'),
    'United States Of America' => array('city_name'=>'', 'country_name'=>'USA'),
    'America' => array('city_name'=>'', 'country_name'=>'USA'),
    'Korea' => array('city_name'=>'', 'country_name'=>'South Korea'),
    'Melaka' => array('city_name'=>'', 'country_name'=>'Malacca'),
    'Us' => array('city_name'=>'', 'country_name'=>'USA'),
    'Arab' => array('city_name'=>'', 'country_name'=>'Saudi Arabia'),
    'Combodia' => array('city_name'=>'', 'country_name'=>'Cambodia'),
    'Burma' => array('city_name'=>'', 'country_name'=>'Myanmar'),
    'Holland' => array('city_name'=>'', 'country_name'=>'Netherlands'),
    'Netherland' => array('city_name'=>'', 'country_name'=>'Netherlands'),
    'England' => array('city_name'=>'', 'country_name'=>'United Kingdom'),
    'Washington' => array('city_name'=>'Washington D.C.', 'country_name'=>'USA'),
    'Washington D.c' => array('city_name'=>'Washington D.C.', 'country_name'=>'USA'),
    'Philippine' => array('city_name'=>'', 'country_name'=>'Philippines'),
    'Phillipine' => array('city_name'=>'', 'country_name'=>'Philippines'),
    'Philipine' => array('city_name'=>'', 'country_name'=>'Philippines'),
    'Aus' => array('city_name'=>'', 'country_name'=>'Australia'),
    'Uae' => array('city_name'=>'', 'country_name'=>'United Arab Emirates'),
    'Uk' => array('city_name'=>'', 'country_name'=>'United Kingdom'),
    'Vatican' => array('city_name'=>'', 'country_name'=>'Vatican City'),
    'Vetican' => array('city_name'=>'', 'country_name'=>'Vatican City'),
    'Vetican City' => array('city_name'=>'', 'country_name'=>'Vatican City'),
    'Antarctic' => array('city_name'=>'', 'country_name'=>'Antarctica'),
    'Arctic' => array('city_name'=>'', 'country_name'=>'Antarctica'),
    
    /*CITY*/
    'Pulau Langkawi' => array('city_name'=>'', 'country_name'=>'Langkawi'),
    'Pulau Redang' => array('city_name'=>'', 'country_name'=>'Redang'),
    'Hongkong' => array('city_name'=>'', 'country_name'=>'Hong Kong'),
    'Hk' => array('city_name'=>'', 'country_name'=>'Hong Kong'),
    'Kl' => array('city_name'=>'', 'country_name'=>'Kuala Lumpur'),
    'K.l.' => array('city_name'=>'', 'country_name'=>'Kuala Lumpur'),
    'Borabora' => array('city_name'=>'Bora Bora', 'country_name'=>'French Polynesia'),
    'Hang Zhou' => array('city_name'=>'', 'country_name'=>'Hangzhou'),
    'Bkk' => array('city_name'=>'', 'country_name'=>'Bangkok'),
    'La' => array('city_name'=>'', 'country_name'=>'Los Angeles'),
    'L.a.' => array('city_name'=>'', 'country_name'=>'Los Angeles'),
    'Mecca' => array('city_name'=>'', 'country_name'=>'Makkah'),
    'Mekkah' => array('city_name'=>'', 'country_name'=>'Makkah'),
    'Pulau Pangkor' => array('city_name'=>'', 'country_name'=>'Pangkor Island'),
    'Pangkor' => array('city_name'=>'', 'country_name'=>'Pangkor Island'),
    'Macao' => array('city_name'=>'', 'country_name'=>'Macau'),
    'Pulau Sibu' => array('city_name'=>'', 'country_name'=>'Sibu Island'),
    'Nz' => array('city_name'=>'', 'country_name'=>'New Zealand'),
    'Jeju' => array('city_name'=>'', 'country_name'=>'Jeju Island'),
    'Mount Bromo' => array('city_name'=>'', 'country_name'=>'Mt Bromo'),
    'Mount Daedunsan' => array('city_name'=>'', 'country_name'=>'Mt Daedunsan'),
    'Mount Sorak' => array('city_name'=>'', 'country_name'=>'Mt Sorak'),
    'Mount Emei' => array('city_name'=>'', 'country_name'=>'Mt. Emei'),
    'Angko Wat' => array('city_name'=>'', 'country_name'=>'Angkor Wat'),
    'Ang Ko Wat' => array('city_name'=>'', 'country_name'=>'Angkor Wat'),
    'Ang Kor Wat' => array('city_name'=>'', 'country_name'=>'Angkor Wat'),
    'Angkorwat' => array('city_name'=>'', 'country_name'=>'Angkor Wat'),
    'Kohsamui' => array('city_name'=>'', 'country_name'=>'Koh Samui'),
    'Ko Samui' => array('city_name'=>'', 'country_name'=>'Koh Samui'),
    'Hokkiado' => array('city_name'=>'', 'country_name'=>'Hokkaido'),
    'Malaka' => array('city_name'=>'', 'country_name'=>'Malacca'),
    'Pork Dickson' => array('city_name'=>'', 'country_name'=>'port dickson'),
    'Kalibo' => array('city_name'=>'', 'country_name'=>'Boracay'),
    'Yogya' => array('city_name'=>'', 'country_name'=>'Yogyakarta (Jogjakarta)'),
    'Yogyakarta' => array('city_name'=>'', 'country_name'=>'Yogyakarta (Jogjakarta)'),
    'Jogja' => array('city_name'=>'', 'country_name'=>'Yogyakarta (Jogjakarta)'),
    'Jogjakarta' => array('city_name'=>'', 'country_name'=>'Yogyakarta (Jogjakarta)')

  );
  
  $return_array=array();
  if(array_key_exists($country_name, $map_dictionary)){
    $return_array['city_name']=$map_dictionary[$country_name]['city_name'];
    $return_array['country_name']=$map_dictionary[$country_name]['country_name'];
  }else if(array_key_exists($city_name, $map_dictionary)){
    $return_array['city_name']=$map_dictionary[$city_name]['city_name'];
    $return_array['country_name']=$map_dictionary[$city_name]['country_name'];
  }else
    return false;
  
  return $return_array;
}

function subheading($params,$country_name){
  $params['lowest_price']="yes";
  $id=Package::search($params,'',1,FALSE);
  $package=new Package($id);
  $price=$package->get('price');
  
  $subheading="Showing <i>".$country_name."</i> packages".((!empty($price))?" from ".$price:"");
  return $subheading;
}