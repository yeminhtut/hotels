<?php
function _profile($email=''){
//=======================================
// Config & Prework
//=======================================
  $pagetitle = 'Profile';
  $description = 'Profile';
  $content = '';
  
//=======================================
// Inputs & Sanitisation
//=======================================
  if(isset($_SESSION['authuid']))
    $id=(int)$_SESSION['authuid'];  
//=======================================
// Derived Data & Handling
//=======================================
  if($id)
    $user=new User($id);
  else
    redirect('user/login');  
//=======================================
// Business Logic
//=======================================
  $content['user']=$user;
  $content['html_gender']=html_gender($user); 
  
//=======================================
// View
//=======================================
  // $head[]='<link rel="stylesheet" href="https://code.jquery.com/ui/1.10.4/themes/smoothness/jquery-ui.min.css">';
  // $head[]='<link href="/css/animate.min.css" rel="stylesheet" type="text/css" media="all">';
  // $head[]='<link href="/css/page/user/profile.css?25" rel="stylesheet" type="text/css" media="all">';
  
  // $head[]='<script type="text/javascript" src="/js/jquery-ui-1.10.4.min.js"></script>';
  // $head[]='<script type="text/javascript" src="/js/page/user/profile.js?4"></script>';
  
  $body = View::do_fetch(VIEW_PATH.'user/profile.php', $content);
  $view = new View(VIEW_PATH.'layouts/layout.php');
  $view->add('body',$body);
  $view->dump();

}

//=======================================
// Private Functions
//=======================================
function html_gender($user=''){
  $html='';
  $gender_arr=array('male', 'female');
  
  foreach($gender_arr as $gender){
    if($user->get('gender')==$gender)
      $html.='
      <div class="radio-inline">
        <label><input type="radio" name="gender" value="'.$gender.'" checked="checked"> '.ucwords($gender).'</label>
      </div>
      ';
    else
      $html.='
      <div class="radio-inline">
        <label><input type="radio" name="gender" value="'.$gender.'"> '.ucwords($gender).'</label>
      </div>
      ';
  }
  
  return $html;
}

function package_thumb_url($id){
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

function deal_thumb_url($id){
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