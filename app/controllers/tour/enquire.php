<?php
function _enquire($posting_id='',$postslug='')
{
	require(APP_PATH.'/inc/securimage/securimage.php');
	
  $security_error=$error=$new_user=FALSE;
  $pagetitle = '';
  $description = '';
  $content['name'] = '';
  $content['email'] = '';
  $content['contact'] = '';
  $content['adult'] = '2';
  $content['child'] = '';
  $content['infant'] = '';
  $content['remarks'] = '';
  $content['captcha'] = '';
  // $content['show_captcha'] = show_captcha();
  // $content['setOption'] = setOption();
  $tfdestination=$tfcontinent=$tfcountry=$tfcity=$tfkeyword='';
  
  //=======================================
  // Inputs & Sanitisation
  //=======================================
  $id=(int)$posting_id;
	$postslug=trim($postslug);
  
  //=======================================
  // Derived Data & Handling
  //=======================================
  $package = new Package();
  $company = new Company();
  $package_destination = new Package_Destination();

  $package->retrieve($id);
  
  if(!$package->exists()){
    redirect();
  }else{
		//temporary redirect to tz sg
		header('Location: http://tripzilla.sg/tour/enquire/'.$posting_id.'/'.$postslug,TRUE,302);
	}

  $company->retrieve($package->get('cid'));
  
  $package_destinations=$package_destination->retrieve_many("package_id=?", array($id));
  
  $countries=$cities=array(); 
  foreach($package_destinations as $package_destination){
    $dest_id=(int)$package_destination->get('destination_id');
    $destination=new Destination($dest_id);
    
    if($destination->get('type')=="city")
      $cities[]=$dest_id;
    else if($destination->get('type')=="country")
      $countries[]=$dest_id;
    else if($dest_id==3) //exception for Europe (3)
      $countries[]=$dest_id;
  } 

  /*From Process */
    if($_SERVER['REQUEST_METHOD']=='POST'){
    $securimage = new Securimage();
    $content['name'] = trim(filter_var($_POST['name'], FILTER_SANITIZE_STRING));
    $content['email'] = trim(filter_var($_POST['email'], FILTER_SANITIZE_EMAIL));
    $content['contact'] = trim(filter_var($_POST['contact'], FILTER_SANITIZE_NUMBER_FLOAT));
    $content['adult'] =  trim(filter_var($_POST['adult'], FILTER_SANITIZE_NUMBER_FLOAT));
    $content['child'] = trim(filter_var($_POST['child'], FILTER_SANITIZE_NUMBER_FLOAT));
    $content['infant'] = trim(filter_var($_POST['infant'], FILTER_SANITIZE_NUMBER_FLOAT));
    $content['remarks'] = trim(filter_var($_POST['remarks'], FILTER_SANITIZE_STRING));
    
    if(empty($content['name'])){
      $error=TRUE;
      $content['err_msgs'][] = 'Please provide your name.';
    }
    
    if(empty($content['email']) || !filter_var($content['email'], FILTER_VALIDATE_EMAIL)){
      $error=TRUE;
      $content['err_msgs'][] = 'Please provide a valid email address.';
    }
    
    if(empty($content['contact']) || $content['contact']===FALSE){
      $error=TRUE;
      $content['err_msgs'][] = 'Please provide your contact number.';
    }
    
    if ($content['show_captcha']){
      if ($securimage->check($_POST['captcha']) == false){
        $content['err_msgs'][] = 'The security code entered was incorrect.';
        $security_error=TRUE;
      }
    }    
  }
/*form process checking errors */
  $get_uid=false; 
  if(isset($_SESSION['authuid']))
    $get_uid=$_SESSION['authuid'];
  
  $get_exclude=exclude_pkg_ids($get_uid);
  if(empty($get_exclude))
    $exclude=$id;
  else
    $exclude=$get_exclude; 
  $content['make_html_similar_packages']=make_html_similar_packages($exclude, $company->get('id'), $countries, $cities); 

  if($_SERVER['REQUEST_METHOD']=='POST'){
  if(!$error && !$security_error){
      $curr_company = $company->get('display_name');
      $user_obj = new User();
      $user_obj->retrieve_one('email = ?',$content['email']);
      if(!$user_obj->exists()){
        // New User !!        
        $password=substr(md5($content['email'].$GLOBALS['config']['secretstring']),0,8);
        $user = new User();
        $user->set('email', $content['email']);
        $user->set('password',md5($GLOBALS['config']['secretstring'].$password));

        // defaults
        $user->set('source', 'tz_enquiry');
        $user->set('roleid', '4');
        $user->set('status', 'approved');
        $user->set('created_dt', date('y-m-d h:i:s'));

        //create
        $user->create();

        // update
        // $user->login(true);        
        $new_user=TRUE;
        
      }//check user is existing one or not
      /*else{
        echo 'need to make login true';
      }*/     
   } //close just a moment  

      $recent_enquiry=recent_enquiry($content['email'], $package->get('id')); //to prevent the user to send the same package's enquiry at the time.
      
       if(!$recent_enquiry){
        $enquiry=new Enquiry();
        $enquiry->set('name', $content['name']);
        $enquiry->set('email', $content['email']);
        $enquiry->set('contact_no', $content['contact']);
        $enquiry->set('adults', $content['adult']);
        $enquiry->set('children', $content['child']);
        $enquiry->set('infants', $content['infant']);
        $enquiry->set('remarks', $remarks);
        $enquiry->set('cid', $company->get('id'));
        $enquiry->set('packageid', $package->get('id'));
        $enquiry->set('type', 'posting');
        $enquiry->set('dealid', 0);
        $enquiry->set('status', 'new');
        if(isset($_SESSION['mobile']))
          $enquiry->set('flags', 'mobile');
        else
          $enquiry->set('flags', '');
          $enquiry->set('source', 'tz');
          $enquiry->set('created_dt', date('y-m-d h:i:s'));
          $enquiry->create();
        
        //Log::inc($package->get('id'),'package',0,0,1);
        
        if($new_user){
          sendWelcomeEmail($user, $password);          
        } 
         
        sendClientEnquiry($content,$package,$company);
        sendUserEnquiry($content,$package,$company);
        redirect('tour/enquire_thankyou/'.$posting_id.'/'.$postslug);
      }
  }
  $data['page_title'] = 'Enquire '.$package->get('title');
  $data['meta_description'] = 'Enquire '.$package->get('title');
  $content['package']=$package;
  $content['company']=$company;
  $data['body'][]=View::do_fetch(VIEW_PATH.'tour/enquire.php', $content);

  
  
  View::do_dump(VIEW_PATH.'layouts/layout.php',$data);
}

//=======================================
// Private Functions
//=======================================
function sendClientEnquiry($content,$package,$company){
  $dbh=getdbh();
  
  $bcc='eric@travelogy.com, kelly@travelogy.com, benjamin@tripzilla.com, chanel@travelogy.com, joanne@travelogy.com, terence@travelogy.com, paulo@travelogy.com, nurmani@travelogy.com';
  
  $email=( ($package->get('email')=='')? $company->get('email') : $package->get('email') );
  $name=ucwords($content['name']);
  
  $subject=$name.' has an enquiry for '.$package->get('display_title');
  $body=html_enquiry_email_to_client($content, $package, $company);
  
  $mailer=getmailer();
  $message=Swift_Message::newInstance()
  ->setSubject($subject)
  ->setFrom(array('enquiries@tripzilla.com' => 'TripZilla Singapore'))
  ->setBody($body,"text/html")
  ;
  
  switch ($_SERVER['HTTP_HOST']) {
    case 'sg.dev.tripzilla.com':
    case 'new.tourpackages.com.sg/':
      if($package->get('id')==1 || $package->get('id')==2){
        $to_emails=array_map('trim', explode(',',$email));
        foreach($to_emails as $to_email)
          if(filter_var($to_email, FILTER_VALIDATE_EMAIL))
            $message->addTo($to_email);
        
        if($bcc!=NULL) {
          $bcc_emails=array_map('trim', explode(',',$bcc));
          foreach($bcc_emails as $bcc_email)
            if(filter_var($bcc_email, FILTER_VALIDATE_EMAIL))
              $message->addBcc($bcc_email);
        }
      }else
        $message->setTo('ye.minhtut@travelogy.com');//for testing
    break;
    default:
      $to_emails=array_map('trim', explode(',',$email));
      foreach($to_emails as $to_email)
        if(filter_var($to_email, FILTER_VALIDATE_EMAIL))
          $message->addTo($to_email);
      
      if($package->get('id')==1 || $package->get('id')==2)
        $bcc='paulo@travelogy.com';
      
      if($bcc!=NULL) {
        $bcc_emails=array_map('trim', explode(',',$bcc));
        foreach($bcc_emails as $bcc_email)
          if(filter_var($bcc_email, FILTER_VALIDATE_EMAIL))
            $message->addBcc($bcc_email);
      }
    break;
  }
  
  $mailer->send($message);
}

function sendUserEnquiry($content,$package,$company){
  $dbh=getdbh();
  
  $email=trim($content['email']);
  $name=ucwords($content['name']);
  
  $subject='Enquiry for '.$package->get('display_title');
  $body=html_enquiry_email_to_user($content, $package, $company);
  
  $mailer=getmailer();
  $message=Swift_Message::newInstance()
  ->setSubject($subject)
  ->setFrom(array('enquiries@tripzilla.sg' => 'TripZilla Singapore'))
  ->setBody($body,"text/html")
  ;
  
  switch ($_SERVER['HTTP_HOST']) {
    case 'sg.dev.tripzilla.com':
    case 'my.tripzilla.me':
      if($package->get('id')==1 || $package->get('id')==2){
        $to_emails=array_map('trim', explode(',',$email));
        foreach($to_emails as $to_email)
          if(filter_var($to_email, FILTER_VALIDATE_EMAIL))
            $message->addTo($to_email);
      }else
        $message->setTo('paulo@travelogy.com');//for testing
    break;
    default:
      $to_emails=array_map('trim', explode(',',$email));
      foreach($to_emails as $to_email)
        if(filter_var($to_email, FILTER_VALIDATE_EMAIL))
          $message->addTo($to_email);
    break;
  }
  
  $mailer->send($message);
}

function exclude_pkg_ids($uid=false){ 
  if(!$uid)
    return false;
  
  $uid=(int)$uid;
  $user=new User($uid);
  
  if(!$user->exists())
    return false;
  
  $email=$user->get('email');
  $date=date('Y-m-d');
  
  
  $dbh=getdbh();
  $stm="SELECT GROUP_CONCAT(packageid) ids FROM enquiry WHERE email=? AND DATE(created_dt)=? AND type='posting'";
  $sql=$dbh->prepare($stm);
  $sql->execute(array($email, $date));
  $result=$sql->fetchColumn();
  
  return $result;
} 

function make_html_similar_packages($exclude='', $company_id='', $countries, $cities){ 
  if( (empty($exclude)) && (!is_numeric($company_id) || empty($company_id)) && (empty($countries) && empty($cities)) )
    $html='';
  else{
    if(count($cities)>0){
      $city_dests=implode(',', $cities);
      $country_dests=implode(',', $countries);
      
      $cities_listing=package_listing_featured_companies($exclude, $company_id, $city_dests, true);
      if(COUNT($cities_listing)>0 && COUNT($cities_listing)<4){
        foreach($cities_listing as $ct_lst)
          $exclude_ct[]=$ct_lst['id'];
        $exclude_ct=implode(",", $exclude_ct);
        $exclude=implode(",", array($exclude, $exclude_ct));
      }
      $countries_listing=package_listing_featured_companies($exclude, $company_id, $country_dests, false, (4 - COUNT($cities_listing)));
      
      $listing=$cities_listing;
      if(COUNT($countries_listing)>0)
        $listing=array_merge($cities_listing, $countries_listing);
    }else{
      $country_dests=implode(',', $countries);
      $listing=package_listing_featured_companies($exclude, $company_id, $country_dests);
    }
    
    if(isset($_SESSION['mobile']))
      $html=package_html_mobile($listing);
    else
      $html=package_html_desktop($listing);
  }
  
  return $html;
}

function package_listing_featured_companies($exclude, $company_id, $destination_ids, $city=false, $limit=4){
  $dbh=getdbh();
  
  $type=(($city)?'city':'country');
  
  $statement="
  SELECT 
  DISTINCT(package.id), 
  package.display_title, 
  package.cid, 
  package.price, 
  '$type' type 

  FROM package 
  LEFT JOIN package_destination ON package_destination.package_id=package.id 
  LEFT JOIN company ON company.id=package.cid 

  WHERE 
  package.status='active' 
  AND NOT(FIND_IN_SET('draft', package.flags)) 
  AND package_destination.destination_id IN (?) 
  AND package.cid!=? 
  AND package.cid!=1897 /*Eco Adventure(temp removed)*/
  AND company.flags='featured' 
  AND package.id NOT IN ($exclude) 

  GROUP BY package.cid 

  ORDER BY IF( FIND_IN_SET(  'featured', package.flags ) , 1, 0 ) DESC, company.flags ASC, package.sortorder, RAND() LIMIT $limit
  ";
  $params[]=$destination_ids;
  $params[]=$company_id;
  
  $sql=$dbh->prepare($statement);
  $sql->execute($params);
  $result=$sql->fetchAll(PDO::FETCH_ASSOC);
  
  return $result;
} 

function package_listing_non_featured_companies($exclude, $company_id, $destination_ids){
  $dbh=getdbh();
  
  $statement="
  SELECT 
  DISTINCT(package.id), 
  package.display_title, 
  package.cid, 
  package.price 

  FROM package 
  LEFT JOIN package_destination ON package_destination.package_id=package.id 
  LEFT JOIN company ON company.id=package.cid 

  WHERE 
  package.status='active' 
  AND NOT(FIND_IN_SET('draft', package.flags)) 
  AND package_destination.destination_id IN (?) 
  AND package.cid!=? 
  AND package.id NOT IN ($exclude) 

  GROUP BY package.cid 

  ORDER BY IF( FIND_IN_SET(  'featured', package.flags ) , 1, 0 ) DESC, company.flags ASC, package.sortorder, RAND() LIMIT 4
  ";
  $params[]=$destination_ids;
  $params[]=$company_id;
  
  $sql=$dbh->prepare($statement);
  $sql->execute($params);
  $result=$sql->fetchAll(PDO::FETCH_ASSOC);
  
  return $result;
}

function package_html_desktop($listing=array()){
  if(!empty($listing) && count($listing)>0){
    $html='
    <div class="divider">&nbsp;</div>
    <div class="similar-packages">
      <div class="header">
        <div class="text">Similar Packages</div>
      </div>
    <ul class="listing">
    <p>Send enquiry to these similar packages (click on checkbox to add/remove recommendations)</p>
    <li style="margin-top:10px;">
    <input type="checkbox" id="handle">
    <label><strong>Select all</strong></label>
    </li>
    ';
    
    foreach($listing as $row){
      $company=new Company($row['cid']);
      $thumb_url=thumb_url($row['id']);
      
      if (empty($row['price']))
        $price = 'Enquire for price';
      else
        $price = $row['price'];
      
      $checked=''; 
      if($row['type']=="city")
        $checked='checked="checked"';
      
      $html.='
      <li>
        <input type="checkbox" class="sm_pkg_cb" name="similar_packages[]" value="'.$row['id'].'" '.$checked.' />
          <a target="_blank" href="'.Package::makepermalink($row['id'], $row['display_title']).'">
            <div class="thumb">
              <img src="'.$thumb_url.'" alt="'.$row['display_title'].'" width="85" height="60"/>
            </div>
          </a>
        <div class="content">
          <a target="_blank" href="'.Package::makepermalink($row['id'], $row['display_title']).'"><h3>'.$row['display_title'].'</h3></a>
          <p>
            From: <a href="/directory/review/'.$company->get('id').'/'.makeslug($company->get('display_name')).'"><b>'.$company->get('display_name').'</b></a><br/>
            Price: '.$price.'
          </p>
        </div>
      </li>
      ';
    } 
    
    $html.='</ul></div>';
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
  
  return $result;
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

function show_captcha() {
  $user=User::getUser();
  $country_code = '';
  if(isset($_SERVER['HTTP_CF_IPCOUNTRY'])) {
    $country_code = $_SERVER['HTTP_CF_IPCOUNTRY'];
  } elseif($_SERVER['GEOIP_COUNTRY_CODE']) {
    $country_code = @$_SERVER['GEOIP_COUNTRY_CODE'];
  } else {
    $country_code=@file_get_contents('http://api.hostip.info/country.php?ip='.$_SERVER['REMOTE_ADDR']);
  }
  
  if ($country_code!='SG' && !$user)
    return TRUE;
  else
    return FALSE;
}

function recent_enquiry($email, $packageid){
  $recently_sent=new Enquiry();
  $recently_sent->retrieve_one("email=? AND packageid=? AND type='posting' AND DATE(created_dt)=?", array($email, $packageid, date('Y-m-d')));
  if($recently_sent->exists())
    return true;
  
  return false;
}

function sendWelcomeEmail($email,$password) {
  $dbh=getdbh();
  
  $email=$email;
  $subject="Your TripZilla account details";
  $body=html_welcome_email($email, $password);
  //$bcc="kelly@travelogy.com, nurmani@travelogy.com";
  
  $mailer=getmailer();
  $message=Swift_Message::newInstance()
  ->setSubject($subject)
  ->setFrom(array('enquiries@tripzilla.sg' => 'TripZilla Singapore'))
  ->setBody($body,"text/html")
  ;
  
  $message->setTo($email);
  
  $mailer->send($message);  
}
function html_welcome_email($email, $password){
  if($password=="")
    $password="{Unable to show due to security reasons}";
  
  $html="
  <table>
  <tr><td colspan='2'>Hi there,</td></tr>
  <tr><td>&nbsp;</td><tr>
  <tr><td colspan='2'>Thank you for registering with TripZilla!</td></tr>
  <tr><td></td><tr>     
  <tr><td colspan='2'>Please add <a href='mailto:newsletter@tripzilla.sg'>newsletter@tripzilla.sg</a> into your address book to receive exclusive and latest travel promotions from us.</td></tr>
  <tr><td>&nbsp;</td><tr>   
  <tr><td colspan='2'><b>Your login details as follow:</b></td></tr>
  <tr><td>
  <table>
  <tr><td><b>User ID:</b></td><td>".$email."</td></tr>
  <tr><td><b>Password:</b></td><td>".$password." (<a href='".WEB_DOMAIN."/user/profile'>change</a>)</td></tr>
  </table>
  </td></tr>
  <tr><td>&nbsp;</td><tr>
  <tr><td colspan='2'>Here's what you can do with a TripZilla account:</td></tr>
  <tr><td>&nbsp;&nbsp;&nbsp;&nbsp;1. Shortlist packages you are interested in</td></tr>
  <tr><td>&nbsp;&nbsp;&nbsp;&nbsp;2. Track your enquiries</td></tr>
  <tr><td>&nbsp;&nbsp;&nbsp;&nbsp;2. Receive exclusive deals from us</td></tr>
  <tr><td>&nbsp;</td><tr>
  <tr><td>
  <a href='".WEB_DOMAIN."/travel?utm_source=emailregistration-tourpackage&utm_medium=emailregistration&utm_campaign=emailregistration'><button>View Tour Packages</button></a>&nbsp;&nbsp;
  <a href='".WEB_DOMAIN."/package-deals?utm_source=emailregistration-traveldeals&utm_medium=emailregistration&utm_campaign=emailregistration'><button>View Travel Deals</button></a>
  <tr><td>&nbsp;</td><tr> 
  <tr><td>Keep travelling!</td><tr>
  <tr><td></td><tr>
  <tr><td>Your travel companion,<br />
  TripZilla ".SITE_COUNTRY."<br />
  The simplest way to find and book your travels</td><tr>
  <tr><td></td><tr>
  </table>
  ";
  
  return $html;
}


function html_enquiry_email_to_client($content, $package, $company){
  $body_email = 'benjamin@tripzilla.com';
  
  $email=$content['email'];
  $name=ucwords($content['name']);
  $contact=$content['contact'];
  $adult=$content['adult'];
  $child=$content['child'];
  $infant=$content['infant'];
  $remarks=$content['remarks'];
  if(!isset($_SESSION['mobile'])){
    $travelfrom=(($content['from']!='')?$content['from']:'  N/A');
    $travelto=(($content['to']!='')?$content['to']:'N/A');
  }
  
  $package_url=Package::makepermalink($package->get('id'), $package->get('display_title'));
  
  if($adult=="")
    $adult="N/A";
  if($child=="")
    $child="N/A";
  if($infant=="")
    $infant="N/A";
  if($remarks=="")
    $remarks="N/A";
  
  $remarks=str_replace("\n","<br/>",$remarks);
  
  $html="
  <table style='font-family:Arial;'>
  <tr><td colspan='2'>Dear Valued Partner,</td></tr>
  <tr><td height='5'>&nbsp;</td><tr>
  <tr><td colspan='2'><b>A user of TripZilla Singapore has just expressed interest in your package: <a href='".$package_url."'>".$package->get('display_title')."</a> (click to see package)</b></td></tr>
  <tr><td height='5'></td><tr>
  <tr><td colspan='2'>Please see details of the enquiry below:</td></tr>
  <tr><td height='5'></td><tr>
  <tr><td><b>Customer Name:</b></td><td>".$name."</td></tr>
  <tr><td><b>Email:</b></td><td>".$email."</td></tr>
  <tr><td><b>Contact Number:</b></td><td>".$contact."</td></tr>
  <tr><td><b>No. of Adults:</b></td><td>".$adult."</td></tr>
  <tr><td><b>No. of Children:</b></td><td>".$child."</td></tr>
  <tr><td><b>No. of Infants:</b></td><td>".$infant."</td></tr>";

  if(!isset($_SESSION['mobile'])){
    $html .="<tr><td><b>Travel period from :</b></td><td>".$travelfrom."</td></tr>
             <tr><td><b>Travel period to :</b></td><td>".$travelto."</td></tr>";
  }
  
  $html .="
  <tr><td valign='top'><b>Enquiry:</b></td><td>".$remarks."</td></tr>
  <tr><td>&nbsp;</td><tr>
  <tr><td colspan='2'><b>Get more customers now</b></td><tr>
  <tr><td colspan='2'>Increase your reach and sell more via TripZilla. Email <a href='mailto:".$body_email."'>".$body_email."</a> today for more information.</td><tr>
  <tr><td>&nbsp;</td><tr> 
  <tr><td colspan='2'>Yours Sincerely,<br />
  TripZilla Singapore<br />
  Singapore's No.1 Travel Portal
  </td></tr>
  ";

  $html.="
  <tr><td height='10'></td><tr>
  <tr><td colspan='2' style='font-family:Arial;font-size:11px;'><a href='".WEB_DOMAIN."/about'><b>Learn more about TripZilla Singapore</b></a></td></tr>
  ";
  
  $html.="</table>";
  
  return $html;
}

function html_enquiry_email_to_user($content, $package, $company){
  $email=$content['email'];
  $name=ucwords($content['name']);
  $contact=$content['contact'];
  $adult=$content['adult'];
  $child=$content['child'];
  $infant=$content['infant'];
  $remarks=$content['remarks'];
  if(!isset($_SESSION['mobile'])){
    $travelfrom=(($content['from']!='')?$content['from']:'  N/A');
    $travelto=(($content['to']!='')?$content['to']:'N/A');
  }
  
  $package_url=Package::makepermalink($package->get('id'), $package->get('display_title'));
  $company_url=Company::makepermalink($company->get('id'), $company->get('display_name'));
  $comp_contact = $company->get('contact');
    
  $company_name=ucwords($company->get('display_name'));
  $company_email=(($company->get('email')!='')? "<tr><td height='5'></td><tr><tr><td colspan='2'>Email: ".$company->get('email')."</td></tr>" : "" );
  $company_contact=(($comp_contact!='') ? "<tr><td height='5'></td><tr><tr><td colspan='2'>Call: ".$comp_contact[0].", mention tour code ref: ".$package->get('tourcode')."</td></tr>" :'');
  $company_address=(($company->get('address')!='')?"<tr><td height='5'></td><tr><tr><td colspan='2'>Walk-in:".implode('<br/>', $company->get('address')).'</td></tr>':'');
  $company_opening_hours=(($company->get('opening_hours')!='')? "<tr><td height='5'></td><tr><tr><td colspan='2'>Operating hour: ".$company->get('opening_hours')."</td></tr>" : "" );

  if($adult=="")
    $adult="N/A";
  if($child=="")
    $child="N/A";
  if($infant=="")
    $infant="N/A";
  if($remarks=="")
    $remarks="N/A";
  
  $remarks=str_replace("\n","<br/>",$remarks);
  
  $html="
  <table style='font-family:Arial;'>
  <tr><td colspan='2'>Dear ".$name.",</td><tr>
  <tr><td height='5'></td><tr>
  <tr><td colspan='2'>Thank you for inquiring about the following package on <a href='".WEB_DOMAIN."'>TripZilla.sg</a></td></tr>
  <tr><td height='5'></td><tr>
  <tr><td colspan='2'><b><a href='".$package_url."'>".$package->get('display_title')."</a></b></td></tr>
  <tr><td height='5'></td><tr>
  <tr><td colspan='2'>We have sent your enquiry to <a href='".$company_url."'>".$company_name."</a>. You will be receiving response from them shortly. If you need further assistance, please contact them directly via the following methods:</td></tr>
  <tr><td height='5'></td><tr>
  <tr><td height='5'></td><tr>
  ".$company_email."
  ".$company_contact."
  ".$company_address."
  ".$company_opening_hours."
  <tr><td height='5'></td><tr>
  <tr><td height='5'></td><tr>
  <tr><td colspan='2'><b>Your Enquiry:</b></td></tr>
  <tr><td colspan='2'>
  <table border='1'>
  <tr><td><b>Your Name:</b></td><td>".$name."</td></tr>
  <tr><td><b>Email:</b></td><td>".$email."</td></tr>
  <tr><td><b>Contact Number:</b></td><td>".$contact."</td></tr>
  <tr><td><b>No. of Adults:</b></td><td>".$adult."</td></tr>
  <tr><td><b>No. of Children:</b></td><td>".$child."</td></tr>
  <tr><td><b>No. of Infants:</b></td><td>".$infant."</td></tr>";
  
  if(!isset($_SESSION['mobile'])){
    $html .="<tr><td><b>Travel period from :</b></td><td>".$travelfrom."</td></tr>
             <tr><td><b>Travel period to :</b></td><td>".$travelto."</td></tr>";
  }
  
  $html .="
  <tr><td valign='top'><b>Enquiry:</b></td><td>".$remarks."</td></tr>
  </table>
  </td></tr>
  <tr><td height='5'></td><tr>
  <tr><td colspan='2'>Have fun planning your trip!</td></tr>
  <tr><td height='5'></td><tr>
  <tr><td colspan='2'>Your travel companion,<br />
  TripZilla Singapore<br />
  The simplest way to find and book your travels<br/><br/>
  TripZilla is a free service provided by Travelogy.com Pte Ltd that helps you find the best travel deals and tour packages from Singapore with the greatest ease. We are not a travel agent and do not offer any direct bookings of tour packages.<br/></td><tr>
  </table>
  ";
  
  return $html;
}