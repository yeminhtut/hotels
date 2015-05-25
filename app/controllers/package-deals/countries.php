<?php
function _countries() {  
	$deal_obj = new Deal();
  $tw=explode('/', $_SERVER['REQUEST_URI']);  
  $tw[2]= ucwords(str_replace('-', ' ', $tw[2]));
  $check_destination=new Destination();
  $dest = $check_destination->retrieve_one("name=?", $tw[2]);  
  $params=array();
  $params['destination']=$check_destination->get('id');	
	$deal_ids= Deal::search($params,$offset,FALSE);
	//$deal_ids= Deal::search($params,$offset,20,FALSE);		
	$company_ids=Deal::retrieve_company_by_deal();	
	$deal_dest_obj = new Deal_Destination();
	$content['make_html']=make_html($deal_ids);
	$content['continents_arr'] = Deal::get_all_deal_destinations('global');  
  $content['countries_arr'] = Deal::get_all_deal_destinations('country');  
  $content['cities_arr'] = Deal::get_all_deal_destinations('city'); 
	$content['cbDealFilter'] = ucwords(str_replace('-', ' ', $tw[2]));
	$CountryName=ucwords(str_replace('-', ' ', $tw[2]));
	$content['TextHeaderOne'] = $CountryName." Tour Package Deals from Singapore";
	$data['body'][]=View::do_fetch(VIEW_PATH.'deal/index.php',$content);
  
	$data['page_title'] = $CountryName.' Tour Package Deals from Singapore | TourPackages.com.sg';
	$data['meta_description'] = 'View '.$CountryName.' Group Tour or Free & Easy Tour Package Deals from Travel Agencies and Daily Deals Sites from Singapore';
	View::do_dump(VIEW_PATH.'layouts/layoutdeals.php',$data);
}


function make_html($deal_ids=''){
  if($deal_ids=='')
    $html='<div class="alert alert-warning text-center">We are not able to find any deal that meets your search criteria. You may want to refine your search and try again.</div>';
  else
   	$listing=deal_listing($deal_ids);
	$html=deal_html_desktop($listing);    
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
  FROM deal WHERE deal.id IN ($deal_ids) GROUP BY deal.id ORDER BY FIELD(deal.id, $deal_ids) 
  ";
  
  $sql=$dbh->prepare($statement);
  $sql->execute();
  $result=$sql->fetchAll(PDO::FETCH_ASSOC);
  
  return $result;
}

function deal_html_desktop($listing=array()){
  if(!empty($listing) && count($listing)>0){
    $html='';
    $num=1;
    foreach($listing as $row){      
      $deal_dests = retrieve_deal_destination($row['id']);
		foreach ($deal_dests as $dest) {
			$dest_ids[] = $dest['destination_id'];
		}
	  $deal_country = retrieve_deal_country($dest_ids);
	  $deal_city = retrieve_deal_city($dest_ids);	
      $type = $period = $expire_on = $price = '';
      $company=new Company($row['cid']);
      $row['type'] = str_replace(',daily_deal', '',$row['type']);
      $row['type'] = str_replace(',', ', ',$row['type']);
      $row['type'] = str_replace('_',' ',$row['type']);
      
      if($row['travel_from'] != '0000-00-00' ) {
        $period = '<li>Period: <span>'.date('M Y', strtotime($row['travel_from']));
        if($row['travel_to'] != '0000-00-00' && $row['travel_from'] != $row['travel_to'])
          $period .= ' to '.date('d M Y',strtotime($row['travel_to']));
        $period .= '</span></li>';
      }
      
      if($row['expire_on'] != '0000-00-00 00:00:00')
        $expire_on = '<li>Promo Ends: <span>'.date('d M Y', strtotime($row['expire_on'])).'</span></li>';
        
      preg_match("/(?!P|RM|MYR|Rp)^(\w+)/",$row['price'],$matches);
      if(!isset($matches[1]))
        $price = '<span>From</span> '.$row['price'];
      else
        $price = $row['price'];

      $thumb_url=thumb_url($row['id']);
      $deal_url='http://tripzilla.sg/travel/deal/'.$row['id'].'/'.makeslug($row['title']).'?utm_source=tp&utm_medium=tp&utm_campaign=tp';
      
      $html .='<div class="DealBox">				
				<div class="ImgBox">
				<a href="'.$deal_url.'" style="text-decoration: none" target="_blank"><img src="'.$thumb_url.'" width="194" /></a></div>
				<div class="TitleBox">
					<a href=".$deal_url." style="text-decoration: none" target="_blank">
					<strong>'.$row['title'].'</strong></a>
				</div>
				<div class="TABox">'.$company->get('display_name').'</div>
				<div class="PriceBox"><span style="font-size:11px; color:#999;">FROM: </span>'.$price.'</div>
				<div class="ExpiryBox">Promotion Ends On: '.$expire_on.'</div>
				<div class="ButBox">
					<a href="'.$deal_url.'" style="text-decoration: none" target="_blank">
						<div style="width:100%; height:35px;"></div>
					</a></div>
			</div>
      ';
      
      $num++;
    }
  }else
    return false;
  
  return $html;
}
function thumb_url($id){
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
function retrieve_deal_city($dest_ids){
	$dbh = getdbh();
    $statement="SELECT name FROM `destination` WHERE type='city' AND id IN (".implode(',', $dest_ids).")";
    $sql = $dbh->prepare($statement);
    $sql->execute();
    $result=$sql->fetchAll(PDO::FETCH_ASSOC);
    $result = $result[0]['name'];
    return $result;
}
function retrieve_deal_country($dest_ids){
	$dbh = getdbh();
    $statement="SELECT name FROM `destination` WHERE type='country' AND id IN (".implode(',', $dest_ids).")";
    $sql = $dbh->prepare($statement);
    $sql->execute();
    $result=$sql->fetchAll(PDO::FETCH_ASSOC);
    $result = $result[0]['name'];
    return $result;
}
function retrieve_deal_destination($deal_id){
    $dbh = getdbh();
    $statement="SELECT destination_id FROM `deal_destination` WHERE `deal_id` = ?";
    $sql = $dbh->prepare($statement);
    $sql->execute(array($deal_id));
    $result=$sql->fetchAll();
    return $result;
  }
function retrieve_all_destinations($type){
  $dbh = getdbh();
    $statement="SELECT name FROM `destination` WHERE `type` = ?";
    $sql = $dbh->prepare($statement);
    $sql->execute(array($type));
    $result=$sql->fetchAll();    
    return $result;
}
function retrieve_all_continents(){
  $dbh = getdbh();
    $statement="SELECT name FROM `destination` WHERE `parentid` = 0";
    $sql = $dbh->prepare($statement);
    $sql->execute();
    $result=$sql->fetchAll();    
    return $result;
}

