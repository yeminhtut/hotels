<?php
function _package($id=0,$postslug='') {
//=======================================
// Config & Prework
//=======================================

  $pagetitle = '';
  $description = '';
  $tfdestination=$tfcontinent=$tfcountry=$tfcity=$tfkeyword='';
  /*$dom_int=(isset($_SESSION['dom_int']))?$_SESSION['dom_int']:"international"; REMOVE!!*/

//=======================================
// Inputs & Sanitisation
//=======================================
  $id = (int)$id;

//=======================================
// Derived Data & Handling
//=======================================  
  $package = new Package();
  $company = new Company();
  $package->retrieve($id);
  
  if ($package->exists() && $package->get('status')!='deleted') {    
    $canonical_url = $package->getpermalink();
    $company->retrieve($package->get('cid'));
    //var_dump($result);exit;
  }else{
    $controller=new Controller(APP_PATH.'controllers/',WEB_FOLDER,'main','index');
    $controller->request_not_found();
  }

  $tourcode = $package->get('tourcode');

  google_redirect($id);
  
//=======================================
// Business Logic
//=======================================  
  $country_id=package_countryid($id);  
  $content['package_country_name'] = package_country_name($country_id);  
  $content['package']=$package;  
  $content['html_description'] = html_description($package->get('description'));
  $content['company']=$company; 
  $content['thumb_url']=thumb_url($id);
  $content['external_source']=external_source($package);
  $content['min_max_day']=retrieve_day($package);  
  $content['company_logo']=company_url($company->get('id'));
  $content['tourcode'] = !empty($tourcode) ? $package->get('tourcode') : $package->get('title');
  $content['show_expire']=show_expire($package);
  $content['country_expert_flag']=country_expert_flag($id,$company->get('id'));
  $content['contact']=$company->get('contact');  
  $pagetitle = $package->get('display_title').' from '.$company->get('display_name');
  if($package->get('price')){
    $description = 'View '.$package->get('display_title').' by '.$company->get('display_name').' | '.$package->get('price');
  }else{
    $description = 'View '.$package->get('display_title').' by '.$company->get('display_name');
  }  

  $content['html_pdf']=html_pdf($id);  

  /*$content['search_tab']=search_tab($dom_int);REMOVE!!*/
  $content['search_filter_month_of_travel']=search_filter_month_of_travel();

  $content['search_filter_no_of_days']=search_filter_no_of_days();
  $content['search_dropdown']=search_dropdown();
  $contact_number = $company->get('contact');
  $content['enquire_button'] = make_enquirey($package,$contact_number);
  //var_dump($content['thumb_url']);exit;
//=======================================
// View
//=======================================
  if (!$package->exists()){
    $controller=new Controller(APP_PATH.'controllers/',WEB_FOLDER,'main','index');
    $controller->request_not_found();
  }
  
  $data['page_title'] = $package->get('title').' from '.$content['company']->get('display_name').'. '.$content['type'].' Package to '.($content['posting_city_name']!=''?$content['posting_city_name'].', ':'').''.$content['package_country_name'];
  $data['meta_description'] = 'Tour Itinerary and package for '.$package->get('title').'. Experience the beauty of '.($content['posting_city_name']!=''?$content['posting_city_name']:$content['posting_country_name']).' with this special package from '.$content['company']->get('Name');
  
  $data['body'][]=View::do_fetch(VIEW_PATH.'tour/package.php', $content);
  View::do_dump(VIEW_PATH.'layouts/layout.php',$data);
}

//=======================================
// Private Functions
//=======================================
function make_enquirey($package,$contact_number){
  $html='';
    $html='
    <div style="margin-left:100px;">
      <td class="tour_enquire_button"><a href="http://tripzilla.sg/tour/enquire/'.$package->get('id').'/'.makeslug($package->get('display_title')).'" class="tour_enquire_button" target="_blank">Send Enquiry</a>&nbsp;&nbsp;</td>
    <td></td>
            <td class="tour_call_button" phone style="display:none;"><span class="tour_call_button">'.$contact_number[0].'</span></td>
            <td></td>            
          </tr>
        </table>
      </div>
    </div>
    ';

  
  return $html;
}

function external_source($package){
  $html='';
  
  if($package->get('cid')==1897){
    $html='
    <div class="text-center">
      <strong>Source: </strong><i><a href="'.$package->get('url_external').'" rel="nofollow" target="_blank">'.$package->get('url_external').'</a></i>
    </div>
    ';
  }
  
  return $html;
}

function get_breadcrumb($package){
  $package_destination=new Package_Destination();
  $package_destinations=$package_destination->retrieve_many("package_id=? ORDER BY destination_id ASC", array($package->get('id')));
  

  $continent_arr=$country_arr=$city_arr=array();
  $m_continent_arr=$m_country_arr=$m_city_arr=array();
  foreach($package_destinations as $package_destination){
    $check_destination=new Destination($package_destination->get('destination_id'));
    
    if($check_destination->get('type')=="global"){
      if(empty($m_continent_arr))
        $m_continent_arr[$check_destination->get('id')]="/travel/packages/".makeslug($check_destination->get('name'));
      else if(!isset($continent_arr[$check_destination->get('id')]))
        if($check_destination->get('parentid')!=0)
          $continent_arr[$check_destination->get('id')]="/travel/packages/".makeslug($check_destination->get('name'));
        else
          $continent_arr[$check_destination->get('id')]="/travel/packages/".makeslug($check_destination->get('name'));
    }
    
    if($check_destination->get('type')=="country"){
      $get_destination=new Destination($check_destination->get('id'));
      $get_destinations=$get_destination->get_parents();
      
      if(empty($m_country_arr) && $get_destinations[0]->get('id')==key($m_continent_arr)){
        $check_city=new Destination();
        $check_city->retrieve_one("parentid=?", array($check_destination->get('id')));
        if($check_city->exists())
          $m_country_arr[$check_destination->get('id')]="/travel/packages/".makeslug($check_destination->get('name'));
        else
          $country_arr[$check_destination->get('id')]="/travel/packages/".makeslug($check_destination->get('name'));
      }else if(!isset($country_arr[$check_destination->get('id')]))
        $country_arr[$check_destination->get('id')]="/travel/packages/".makeslug($check_destination->get('name'));
    }
    
    if($check_destination->get('type')=="city"){
      if(empty($m_city_arr) && $check_destination->get('parentid')==key($m_country_arr))
        $m_city_arr[$check_destination->get('id')]="/travel/packages/".makeslug($check_destination->get('name'));
      else if(!isset($city_arr[$check_destination->get('id')]))
        $city_arr[$check_destination->get('id')]="/travel/packages/".makeslug($check_destination->get('name'));
    }
  }
  
  $continent_arr=array_replace_recursive($m_continent_arr, $continent_arr);
  $country_arr=array_replace_recursive($m_country_arr, $country_arr);
  $city_arr=array_replace_recursive($m_city_arr, $city_arr);
  
  /*$bdata[$dom_int]=(($dom_int=="international")?"/":"/domestic");
  $bdata['Tour Packages']="/travel/packages/".(($dom_int=="international")?"international":"domestic");REMOVE!!*/
  $bdata['Tour Packages']="/travel/packages/";
  $bdata['continent']=$continent_arr;
  $bdata['country']=$country_arr;
  $bdata['city']=$city_arr;
  $breadcrumb=breadcrumb($bdata);
  
  return $breadcrumb;
}

function google_redirect($id=false){
  if($id && $id!=0){
    $redirect_ids=array(17772);
    $host  = $_SERVER['HTTP_HOST'];
    if(isset($_SERVER['HTTP_REFERER']))
      if(strpos($_SERVER['HTTP_REFERER'], 'google.com')!==FALSE)
        if(in_array($id, $redirect_ids)){
          header('Location: '.WEB_DOMAIN.'/travel/deals/matta',TRUE,301);
          exit;
        }
  }
}

function html_description($content=''){
  $content=mb_convert_encoding($content, 'html-entities', 'utf-8');
  
  $doc=new DOMDocument();
  @$doc->loadHTML($content);
  $xpath=new DOMXpath($doc);
  $searchTags=$xpath->query('//img');
  
  $length=$searchTags->length;
  
  for ($i=0; $i<$length; $i++) {
    $element=$searchTags->item($i);
    $src=$element->getAttribute('src');
    if(strpos($src,"http")===FALSE)
      $src=WEB_DOMAIN.$src;
    list($width, $height, $type, $attr)=getimagesize($src);
    
    if($width<101)
      $element->setAttribute('class', 'inherit_width');
  }
  
  $html=preg_replace('~<(?:!DOCTYPE|/?(?:html|head|body))[^>]*>\s*~i', '', $doc->saveHTML());
  
  return $html;
}

function html_pdf($id){
  $html='';
  $download_html='';
  $package=new Package($id);
  $file=new File($package->get('fileid'));
  if($file->exists()){
    if(strtolower($file->get('extension'))=='pdf') {
      $html.='
      <object style="height: 920px; width: 100%;" type="application/pdf" data="'.FILES_PATH.$file->get('id').'.'.$file->get('extension').'">
      <iframe src="https://docs.google.com/gview?url='.urlencode(FILES_PATH.$file->get('id')).'.'.$file->get('extension').'&embedded=true" style="height: 920px; width: 670px;" frameborder="0"></iframe><br /><br />
      </object><br /><br />
      ';
    } elseif(strtolower($file->get('extension'))=='tiff' || strtolower($file->get('extension'))=='ppt') {
      $html.='<iframe src="https://docs.google.com/gview?url='.urlencode(FILES_PATH.$file->get('id')).'.'.$file->get('extension').'&embedded=true" style="height: 798px; width: 577px;" frameborder="0"></iframe><br /><br />';
    }
    
    $download_html.='<a target="_blank" href="/files/'.$file->get('id').'.'.$file->get('extension').'"><span class="glyphicon glyphicon-link"></span> Itinerary</a>';
  }
  
  $result=$package->get('attach_array');
  $count = 1;
  if($result!=''){
    foreach($result as $itinerary){
      if ($itinerary){
        $html.='
        <object style="height: 920px; width: 100%;" data="'.$itinerary.'">
        <iframe src="https://docs.google.com/gview?url='.urlencode($itinerary).'&embedded=true" style="height: 920px; width: 670px;" frameborder="0"></iframe><br /><br />
        </object><br /><br />
        ';
        
        $download_html.='<a target="_blank" href="'.$itinerary.'"><span class="glyphicon glyphicon-link"></span> Itinerary '.$count++.'</a>';
      }
    }
  }
  
  if(isset($_SESSION['mobile'])){
    if(!empty($download_html))
      $html='<br/><span style="font-size:15px; font-weight:bold;">Download itineraries in PDF : '.$download_html.'</span>';
  }else{
    if(!empty($download_html)){
      $download_html='<div class="itineraries"><label>Download:</label>'.$download_html.'</div>';
      $html='<div class="title">Itinerary</div> '.$download_html.' <div class="content"> '.$html.' </div>';
    }
  }
  
  return $html;
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
    $file->retrieve_one('destination_id=? and reference_type=?', array($country_id, 'country'));

    $thumb_url=FILES_PATH."no_image.jpg";
    if($file->exists())
      $thumb_url=FILES_PATH.$file->get('id').".".$file->get('extension');
  }
  
  return $thumb_url;
}

function company_url($id){
  //for company logo
  $file = new File();
  $file = $file->retrieve_one('reference_id=? and reference_type=? and type=? ', array($id, 'company', 'image'));
  if($file!='' && $file->exists())
    $comp_url=FILES_PATH.$file->get('id').".".$file->get('extension');
  else
    $comp_url=FILES_PATH."no_image.jpg";
    
  return $comp_url;
}

function retrieve_day($package){
  $min_max_day = false;
    if($package->get('maxday')!=0 && $package->get('minday')!=0) {
      $min_max_day = isset($_SESSION['mobile']) ? '<li><span class="glyphicon glyphicon-time"></span> Days: ' : '<li><label>Days</label><span>';
        if($package->get('minday')==$package->get('maxday')) {
            $min_max_day.=$package->get('minday')." day".(($package->get('minday')>1)?'s':'');
        } else {
            $min_max_day.=$package->get('minday')." - ".$package->get('maxday')." day".(($package->get('maxday')>1)?'s':'');
        }
      $min_max_day .= isset($_SESSION['mobile']) ? '</li>' : '</span></li>'; 
    } elseif($package->get('minday')!=0) {
      $min_max_day = isset($_SESSION['mobile']) ? '<li><span class="glyphicon glyphicon-time"></span> Days: ' : '<li><label>Days</label><span>';
        $min_max_day .= $package->get('minday')." or more day".(($package->get('minday')>1)?'s':'');
      $min_max_day .= isset($_SESSION['mobile']) ? '</li>' : '</span></li>';
    } elseif($package->get('maxday')!=0) {
      $min_max_day = isset($_SESSION['mobile']) ? '<li><span class="glyphicon glyphicon-time"></span> Days: ' : '<li><label>Days</label><span>';
        $min_max_day .= $package->get('maxday')." or less day".(($package->get('maxday')>1)?'s':'');
      $min_max_day .= isset($_SESSION['mobile']) ? '</li>' : '</span></li>';
    }
  return $min_max_day;
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

function package_country_name($id){
  $dbh=getdbh();
  
  $statement="SELECT name FROM `destination` WHERE `id` = ?";
  
  $sql=$dbh->prepare($statement);
  $sql->execute(array($id));
  $result=$sql->fetch(PDO::FETCH_COLUMN);  
  
  return $result;
}

function show_expire($package){
  $html = '';
  if(isset($_SESSION['mobile'])) {
    if(($package->get('valid_to')!='0000-00-00' && strtotime(date('Y-m-d'))>strtotime($package->get('valid_to'))) || $package->get('flags')=='draft' || $package->get('status')!='active')
      $html = '<div class="expire button-template button-color-orange">Tour package expired :(
                <a href="/travel/packages"><div class="similar button-template button-color-blue">Find similar packages</div></a>
               </div>';

  }else{
    if(($package->get('valid_to')!='0000-00-00' && strtotime(date('Y-m-d'))>strtotime($package->get('valid_to'))) || $package->get('flags')=='draft' || $package->get('status')!='active')
      $html = '<div class="expire button-template button-color-orange">This tour package has expired :(
                <a href="/travel/packages"><div class="similar button-template button-color-blue">Find similar tour packages</div></a>
               </div>';
  }
  return $html;
}

function retrieve_articles($id){
  $package_destination=new Package_Destination();
  $package_destinations=$package_destination->retrieve_many("package_id=?", array($id));
  $count = 0;
  $dest ='';
  if(count($package_destinations)>0){
    foreach($package_destinations as $package_destination){
      $destination = new Destination($package_destination->get('destination_id'));
      if($destination->get('type')=="country" || $destination->get('type')=="city"){
        if (!$count++)
          $dest .= '?'.strtolower($destination->get('name'));
        else
          $dest .= '&'.strtolower($destination->get('name'));
      }
    }
  }
  $iframe = '
  <h2 class="article">Travel Articles</h2>
  <iframe src="http://magazine.tripzilla.com/widget-page'.$dest.'" class="article_iframe"></iframe>';
  return $iframe;
}

function country_expert_flag($id='',$cid=''){
  $html = '';
  $package_destination=new Package_Destination();
  $package_destinations=$package_destination->retrieve_many("package_id=?",$id);
  $countries_expert=array();

  foreach($package_destinations as $package_destination)
    $countries_expert[]=$package_destination->get('destination_id');

  $company_expert=new Destination_Expert();
  if(!empty($countries_expert)){
    $company_expert->retrieve_one('country_id IN ('.implode(',', $countries_expert).') AND status="active" AND cid=? AND (? BETWEEN start_date and end_date)', array($cid, date('Y-m-d')));
    
    if($company_expert->exists()){
      $tag_destination=new Destination($company_expert->get('country_id'));
      $html .= '
      <div class="country_expert_flag">'.$tag_destination->get('name').' Expert</div>
      ';
    }
  }
  
  return $html;
}

function html_similar_travel_promotions($id){
  if(!isset($id) || !is_numeric($id) || empty($id))
    $html='';
  
  $package_destination=new Package_Destination();
  $package_destination->retrieve_one('package_id=? ORDER BY destination_id DESC', array($id));
  
  $check_package_destination=new Destination($package_destination->get('destination_id'));
  
  $params['destination']=$check_package_destination->get('id');
  $deal_ids=Deal::search($params,'',5,FALSE,TRUE);
  
  if($check_package_destination->get('type')=="city"){
    $get_country=new Destination($check_package_destination->get('parentid'));
    $location=$check_package_destination->get('name').", ".$get_country->get('name');
  }else{
    $location=$check_package_destination->get('name');
  }
  
  if($deal_ids=='')
    $html='';
  else{
    if(isset($_SESSION['mobile'])){
      $listing=deal_listing($deal_ids);
      $html=deal_html_mobile($listing,$location);
    }else{
      $listing=deal_listing($deal_ids);
      $html=deal_html_desktop($listing,$location);
    }
  }
  
  return $html;
}

function deal_listing($deal_ids=''){
  $dbh=getdbh();
  
  $statement="
  SELECT
  deal.id,
  deal.cid,
  deal.title,
  deal.price,
  deal.type,
  deal.travel_from,
  deal.travel_to,
  deal.expire_on
  FROM deal WHERE deal.id IN ($deal_ids) GROUP BY deal.id
  ";
  
  $sql=$dbh->prepare($statement);
  $sql->execute();
  $result=$sql->fetchAll(PDO::FETCH_ASSOC);
  
  return $result;
}

function deal_html_desktop($listing=array(),$location=''){
  if(!empty($listing) && count($listing)>0){
    $html='
    <div class="footer-package">
    <div class="bar">
    <span class="glyphicon glyphicon-tags"></span>
    <h2>Other similar travel promotions to '.$location.'</h2>
    </div>
    <ul class="list">
    ';
    foreach($listing as $row){
      $company=new Company($row['cid']);
      
      $thumb_url=thumb_url_similar_promotion($row['id']);
      
      $price='<span>'.$row['price'].'</span>';
      preg_match("/(?!P|RM|MYR|Rp)^(\w+)/",$row['price'],$matches);
      if(!isset($matches[1]))
        $price='<span>From '.$row['price'].'</span>';
      
      $title=$row['title'];
      if (strlen($row['title']) > 40) {
        $shorten = substr($row['title'], 0, 40);
        $title = substr($shorten, 0, strrpos($shorten, ' ')).'...';
      }
      
      $html.='
      <li>
        <div class="thumb">
          <a target="_blank" href="'.Deal::makepermalink($row['id'], $row['title']).'" target="_blank">
            <img src="'.$thumb_url.'" alt="'.$row['title'].'" width="156" height="125"/>
          </a>
        </div>
        <div class="body">
          <a target="_blank" href="'.Deal::makepermalink($row['id'], $row['title']).'" target="_blank"><h3>'.$title.'</h3></a>
          <p>From: <a href="/directory/review/'.$company->get('id').'/'.makeslug($company->get('display_name')).'"><b>'.$company->get('display_name').'</b></a> </p>
        </div>
        <div class="footer">
          <div class="price">'.$price.'</div>
        </div>
      </li>
      ';
    }
    $html.='</ul></div>';
  }else
    return false;
  
  return $html;
}

function deal_html_mobile($listing=array(),$location=''){
  if(!empty($listing) && count($listing)>0){
    $html='
    <div class="bar">
    <span class="icon-promotion"></span>
    <h2>Other similar promotions to '.$location.'</h2>
    </div>
    <div class="list">
    ';
    foreach($listing as $row){
      $link = "document.location.href = '".Deal::makepermalink($row['id'], $row['title'])."'";  
      $company=new Company($row['cid']);
      $thumb_url=thumb_url_similar_promotion($row['id']);
      
      $row['type'] = str_replace(',daily_deal', '',$row['type']);
      $row['type'] = str_replace(',', ', ',$row['type']);
      $row['type'] = str_replace('_',' ',$row['type']);
      
      $html.='
      <div class="item item-avatar" onclick="'.$link.'">
        <div class="right">
          <img src="'.$thumb_url.'" alt="'.$row['title'].'" width="115" height="90"/>
        </div>
        <div class="left">
          <h2 class="clamp multiline_clamp">'.$row['title'].'<span class="ellipsis">&#133;</span><span class="fill"></span></h2>
          <p class="company"><a href="/directory/review/'.$company->get('id').'/'.makeslug($company->get('display_name')).'">'.$company->get('display_name').'</a></p>
          <p>'.ucwords($row['type']).'</p>
          <p class="price">'.$row['price'].'</p>
        </div>
        <div style="clear: both;"></div>
      </div>
      ';
    }
    $html.='</div>';
  }else
    return false;
  
  return $html;
}

function thumb_url_similar_promotion($id){
  if(!isset($id) || !is_numeric($id) || empty($id))
    $thumb_url=FILES_PATH."no_image.jpg";
  
  $file=new File();
  $file->retrieve_one('reference_id=? and reference_type=?', array($id, 'deal'));
  
  $thumb_url=FILES_PATH."no_image.jpg";
  if($file->exists())
    $thumb_url=FILES_PATH.$file->get('id').".".$file->get('extension');
  else{
    $deal=new Deal($id);
    $thumb_url=$deal->get('image_url');
  }
  
  return $thumb_url;
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
  '0-200'=>'< SGD 200', 
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
  
  $html.='<option value="-1">Any</option>';
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

/*function search_tab($dom_int='international'){
  if($dom_int=='domestic')
    $html='<a href="/travel/packages/international"><input type="radio" name="dom_int" value="international"><strong>International</strong></a>&nbsp;<a href="/travel/packages/domestic"><input type="radio" name="dom_int" value="domestic" checked="checked"><strong>Domestic</strong></a>';
  else
    $html='<a href="/travel/packages/international"><input type="radio" name="dom_int" value="international" checked="checked"><strong>International</strong></a>&nbsp;<a href="/travel/packages/domestic"><input type="radio" name="dom_int" value="domestic"><strong>Domestic</strong></a>';
    
  return $html;
}REMOVE!!*/

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