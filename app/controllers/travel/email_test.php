<?php 
	function _email_test(){		

  $featured_package_ids=get_featured_packages();
	
  $content['make_html_featured_package'] = make_html_featured_package($featured_package_ids);
  var_dump($content['make_html_featured_package']);echo "<hr/>";
  
	}

function make_html_featured_package($featured_package_ids=''){
  if($featured_package_ids==''){
    return false;
  }else{
    $listing=package_listing($featured_package_ids); 
    $html=featured_package_html_desktop($listing);   
  } 
  return $html;
}

function package_listing($featured_package_ids=''){
  $dbh=getdbh();
  
  $statement="
  SELECT 
  package.id, 
  package.cid, 
  package.title, 
  package.display_title,
  package.maxday, 
  package.type, 
  package.travel_period_from, 
  package.travel_period_to, 
  package.price,
  package.price_usd 
  FROM package WHERE package.id IN ($featured_package_ids) GROUP BY package.id ORDER BY FIELD(package.id, $featured_package_ids) 
  ";
  
  $sql=$dbh->prepare($statement);
  $sql->execute();
  $result=$sql->fetchAll(PDO::FETCH_ASSOC);
  
  return $result;
}
function featured_package_html_desktop($listing=array()){
  if(!empty($listing) && count($listing)>0){
    $html='';
    foreach($listing as $row){
      $company=new Company($row['cid']);
      $thumb_url=thumb_url($row['id']);
      
      if (empty($row['price']))
        $row['price'] = 'Enquire for price';
      else
        $row['price'] = $row['price'];
      
      $title=$row['title'];
      if(strlen($title)>45){
        $stringCut=substr($title, 0, 45);
        $title=substr($stringCut, 0, strrpos($stringCut, ' ')).'...';
      }
      
      // $html.='<li>
      //   <p class="company"><a href="/directory/review/'.$company->get('id').'/'.makeslug($company->get('display_name')).'">'.$company->get('display_name').'</a></p>
      //   <a target="_blank" href="'.Package::makepermalink($row['id'], $row['title']).'">
      //   <div class="thumb">
      //     <img src="'.$thumb_url.'" alt="'.$row['title'].'" width="156" height="125">
      //   </div>
      //   <div class="body" style="height: 86px;">
      //     <h3 style="font-weight:normal;color:#000;overflow: hidden; height: 52px;">'.$row['title'].'</h3>
      //   </div>
      //   <div class="footer"  style="height: 32px;">
      //     <p style="background-image:none;background-color:#ff8711;margin:0;height:32px;"><span>'.$row['price'].'</span>
      //     <img src="/img/white_arrow.png" style="float:right;">
      //     </p>
      //   </div>
      //   </a>
      // </li>
      // ';
      $html.='
              <tr>
          <td><div class="photo_holder" style="width:100px;height:100px;border:3px solid #ccc;">
            <a href="'.Package::makepermalink($row['id'], $row['title']).'">
              <img src="'.$thumb_url.'" width="100px" height="100px"/>
            </a>
            </div></td>
          <td width="5px"></td>
          <td>
            <a href="'.Package::makepermalink($row['id'], $row['title']).'"><b>'.$row['title'].'</b></a><br/>
            <div class="travel_agency_decription">by <a href="/directory/review/'.$company->get('id').'/'.makeslug($company->get('display_name')).'">'.$company->get('display_name').'</a></div>            
            <div class="travel_price">'.$row['price'].'</div>          
          </td>
        </tr>

      ';
    }
  }else
    return false;
  
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
function get_featured_packages(){
  $featured_packages=featured_packages();
  
  $mv_pkg_ids=most_viewed_packages();
  shuffle($mv_pkg_ids);
  $most_viewed_packages=array_slice($mv_pkg_ids, 0, (5 - count($featured_packages)));
  $most_viewed_packages=array_map(function($a) {  return array_pop($a); }, $most_viewed_packages);
  
  $package_ids=array_merge($featured_packages, $most_viewed_packages);
  shuffle($package_ids);
  $package_ids=implode(",", $package_ids);
  
  return $package_ids;
}
function featured_packages(){
  $package_ids=Package::search(array("featured"=>"y","enquireprice"=>"n"),false,5,false,true);
  $result=explode(",", $package_ids);
  return $result;
}
function most_viewed_packages(){
  $dbh = getdbh();
  
  $statement="
  SELECT 
  DISTINCT package.id 
  FROM 
  trip_sg.package package 
  LEFT JOIN trip_sg.company company ON company.id = package.cid 
  LEFT JOIN trip_sg_log.daily daily ON daily.extid = package.id 
  WHERE 
  package.status='active' 
  AND NOT(FIND_IN_SET('draft', package.flags)) 
  AND company.status='approved' 
  AND FIND_IN_SET('featured', company.flags) 
  AND ( daily.date BETWEEN DATE_SUB(CURDATE(), INTERVAL 2 week) AND CURDATE() ) 
  AND daily.type='package' 
  GROUP BY package.cid 
  ORDER BY SUM(daily.goal2) DESC 
  LIMIT 15
  ";
  
  $sql=$dbh->prepare($statement);
  $sql->execute();
  $result=$sql->fetchAll(PDO::FETCH_ASSOC);
  
  return $result;
}

 ?>
