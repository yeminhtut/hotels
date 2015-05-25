<?php
class Email extends Model
{
  function __construct($id='')
  {
		parent::__construct('EmailID','T_Email','getdbh');
		$this->rs['EmailID']=0;
		$this->rs['EmailFrom']='';
		$this->rs['EmailTo']='';
		$this->rs['EmailBcc']='';
		$this->rs['Content']='';
		$this->rs['Subject']='';
		$this->rs['Status']='';
		$this->rs['Created_By_ID']='';
		$this->rs['Created_Date']='';
		$this->rs['Last_Updated_By_ID']='';
		$this->rs['Last_Updated_Date']='';

  	if ($id)
    	$this->retrieve($id);
  }
  
  static function prepare_enquiry_email($company_name, $company_email, $posting_email, $name, $sender_email, $contact, $adult, $child, $infant, $remarks, $title, $posting_id, $code, $preferred_callback_time='')
  {
  	$dbh = getdbh();
  	
  	if($posting_email=='')
  		$email=$company_email;
  	else
  		$email=$posting_email;
  
  	if($adult=="")
  		$adult="N/A";
  	if($child=="")
  		$child="N/A";
  	if($infant=="")
  		$infant="N/A";
  	if($remarks=="")
  		$remarks="N/A";
  
  	$bcc = 'benjamin@tripzilla.com,chanel@travelogy.com,joanne@travelogy.com,kelly@travelogy.com,rusdi@travelogy.com,terence@travelogy.com,paulo@travelogy.com';
  	
/*  	$body = "<table style='font-family:Arial;'>
			<tr><td colspan='2'>Dear Valued Partner,</td></tr>
      <tr><td height='5'>&nbsp;</td><tr>
			<tr><td colspan='2'><b>A user of TripZilla's TourPackages.com.sg network portal has just expressed interest in your package: <a href='".WEB_DOMAIN."/tour/package/".$posting_id."/".url_title($title)."'>".$title."</a> (click to see package)</b></td></tr>
			<tr><td height='5'></td><tr>
			<tr><td colspan='2'>Please see details of the enquiry below:</td></tr>
			<tr><td height='5'></td><tr>
			<tr><td><b>Customer Name:</b></td><td>".ucwords($name)."</td></tr>
			<tr><td><b>Email:</b></td><td>".$sender_email."</td></tr>
			<tr><td><b>Contact Number:</b></td><td>".$contact."</td></tr>
			<tr><td><b>No. of Adults:</b></td><td>".$adult."</td></tr>
			<tr><td><b>No. of Children:</b></td><td>".$child."</td></tr>
			<tr><td><b>No. of Infants:</b></td><td>".$infant."</td></tr>
      <tr><td><b>Preferred Callback Time:</b></td><td>".(($preferred_callback_time!='')?$preferred_callback_time:'N/A')."</td></tr>
			<tr><td valign='top'><b>Enquiry:</b></td><td>".str_replace("\n","<br/>",$remarks)."</td></tr>
			<tr><td>&nbsp;</td><tr>
			<tr><td colspan='2'><b>Great job! Your product is generating interest on TripZilla!</b></td><tr>
			<tr><td colspan='2'>Contact <a href='mailto:benjamin@tripzilla.com'>benjamin@tripzilla.com</a> to learn how we can help you increase your sales even further.</td><tr>
			<tr><td>&nbsp;</td><tr>
			<tr><td colspan='2'>Yours Sincerely,<br />
					TripZilla ".SITE_COUNTRY."<br />
					The simplest way to find and book your travels
			</td></tr>";
  	
  	$body .= "<tr><td height='10'></td><tr>
      <tr><td colspan='2' style='font-family:Arial;font-size:11px;'><a href='".WEB_DOMAIN."/about'><b>Learn more about TripZilla ".SITE_COUNTRY."</b></a></td></tr>
      ";
  	
  	$body .= "</table>";
*/

    $content_email = 'benjamin@tripzilla.com';
  	$body = "<table style='font-family:Arial;'>
			<tr><td colspan='2'>Dear Valued Partner,</td></tr>
      <tr><td height='5'>&nbsp;</td><tr>
			<tr><td colspan='2'><b>A user of TripZilla Singapore has just expressed interest in your package: <a href='http://tripzilla.sg/tour/package/".$posting_id."/".url_title($title)."'>".$title."</a> (click to see package)</b></td></tr>
			<tr><td height='5'></td><tr>
			<tr><td colspan='2'>Please see details of the enquiry below:</td></tr>
			<tr><td height='5'></td><tr>
			<tr><td><b>Customer Name:</b></td><td>".ucwords($name)."</td></tr>
			<tr><td><b>Email:</b></td><td>".$sender_email."</td></tr>
			<tr><td><b>Contact Number:</b></td><td>".$contact."</td></tr>
			<tr><td><b>No. of Adults:</b></td><td>".$adult."</td></tr>
			<tr><td><b>No. of Children:</b></td><td>".$child."</td></tr>
			<tr><td><b>No. of Infants:</b></td><td>".$infant."</td></tr>
      <tr><td><b>Preferred Callback Time:</b></td><td>".(($preferred_callback_time!='')?$preferred_callback_time:'N/A')."</td></tr>
			<tr><td valign='top'><b>Enquiry:</b></td><td>".str_replace("\n","<br/>",$remarks)."</td></tr>
			<tr><td>&nbsp;</td><tr>
			<tr><td colspan='2'><b>Great job! Your product is generating interest on TripZilla!</b></td><tr>
			<tr><td colspan='2'>Contact <a href='mailto:".$content_email."'>".$content_email."</a> to learn how we can help you increase your sales even further.</td><tr>
			<tr><td>&nbsp;</td><tr>	
			<tr><td colspan='2'>Yours Sincerely,<br />
					TripZilla Singapore<br />
					Singapore's No. 1 Travel Portal
			</td></tr>";

  	$body .= "<tr><td height='10'></td><tr>
      <tr><td colspan='2' style='font-family:Arial;font-size:11px;'><a href='http://tripzilla.sg/about'><b>Learn more about TripZilla Singapore</b></a></td></tr>
      ";
    
  	$body .= "<img src='http://tripzilla.sg/edm/pixelclick.php?id=enquiry-email&q=2' border=0></table>";

  	
    if(!is_numeric($contact) || strlen($contact) < 8)
      $statement = "INSERT INTO T_Email (emailfrom,emailto,emailbcc,status,subject,content,created_date,last_updated_date,status) VALUES (?,?,?,?,?,?,NOW(),NOW(),'sent')";
    else
      $statement = "INSERT INTO T_Email (emailfrom,emailto,emailbcc,status,subject,content,created_date,last_updated_date) VALUES (?,?,?,?,?,?,NOW(),NOW())";
    
  	$sql = $dbh->prepare($statement);
  	$sql->execute(array($sender_email,$email,$bcc,'approved',ucwords($name)." has an enquiry for ".$title, $body));
  	
    if(is_numeric($contact) && strlen($contact) >= 8)
      self::send_email();
  }
  
  static function prepare_enquiry_confirmation_email($company_name, $company_email, $posting_email, $name, $sender_email, $contact, $adult, $child, $infant, $remarks, $title, $posting_id, $code, $preferred_callback_time='')
  {
  	$dbh = getdbh();
  	
  	if($posting_email=='')
  		$email=$company_email;
  	else
  		$email=$posting_email;
  
  	if($adult=="")
  		$adult="N/A";
  	if($child=="")
  		$child="N/A";
  	if($infant=="")
  		$infant="N/A";
  	if($remarks=="")
  		$remarks="N/A";
  	
/*  	$body = "<table>
			<tr><td colspan='2'>Dear ".$name.",</td><tr>
			<tr><td height='5'></td><tr>
			<tr><td colspan='2'>Thank you for using TourPackages, a product of TripZilla.</td></tr>
      <tr><td height='5'></td><tr>
			<tr><td colspan='2'>We have just sent your enquiry to the agencies with the information below:</td></tr>
			<tr><td height='5'></td><tr>
			<tr><td colspan='2'><b><a href='http://tourpackages.com.sg/tour/package/".$posting_id."/".url_title($title)."'>".$title."</a></b></td></tr>
			<tr><td height='5'></td><tr>
			<tr><td colspan='2'>
				<table border='1'>
					<tr><td><b>Customer Name:</b></td><td>".ucwords($name)."</td></tr>
					<tr><td><b>Email:</b></td><td>".$sender_email."</td></tr>
					<tr><td><b>Contact Number:</b></td><td>".$contact."</td></tr>
					<tr><td><b>No. of Adults:</b></td><td>".$adult."</td></tr>
					<tr><td><b>No. of Children:</b></td><td>".$child."</td></tr>
					<tr><td><b>No. of Infants:</b></td><td>".$infant."</td></tr>
		      <tr><td><b>Preferred Callback Time:</b></td><td>".(($preferred_callback_time!='')?$preferred_callback_time:'N/A')."</td></tr>
					<tr><td valign='top'><b>Enquiry:</b></td><td>".str_replace("\n","<br/>",$remarks)."</td></tr>
				</table>
			</td></tr>
			<tr><td height='5'></td><tr>
			<tr><td colspan='2'>You will be receiving a response from the agency shortly. If you need further assistance, please contact the vendor directly at <a href='mailto:".$email."'>".$email."</a>.</td></tr>
			<tr><td height='5'></td><tr>
			<tr><td colspan='2'>Have fun planning your trip!</td></tr>
			<tr><td height='5'></td><tr>
			<tr><td colspan='2'>Your travel companion,<br />
					TourPackages.com.sg / TripZilla.com<br />
					The simplest way to find and book your travels</td><tr>
			</table>";
*/      
  	$body = "<table style='font-family:Arial;'>
			<tr><td colspan='2'>Dear ".$name.",</td><tr>
			<tr><td height='5'></td><tr>
			<tr><td colspan='2'>Thank you for using TourPackages, a product of TripZilla.</td></tr>
      <tr><td height='5'></td><tr>
			<tr><td colspan='2'><b><a href='http://tourpackages.com.sg/tour/package/".$posting_id."/".url_title($title)."'>".$title."</a></b></td></tr>
      <tr><td height='5'></td><tr>
			<tr><td colspan='2'>We have just sent your enquiry to the agencies with the information below:</td></tr>
			<tr><td height='5'></td><tr>
      <tr><td height='5'></td><tr>
			<tr><td colspan='2'>
				<table border='1'>
					<tr><td><b>Your Name:</b></td><td>".ucwords($name)."</td></tr>
					<tr><td><b>Email:</b></td><td>".$sender_email."</td></tr>
					<tr><td><b>Contact Number:</b></td><td>".$contact."</td></tr>
					<tr><td><b>No. of Adults:</b></td><td>".$adult."</td></tr>
					<tr><td><b>No. of Children:</b></td><td>".$child."</td></tr>
					<tr><td><b>No. of Infants:</b></td><td>".$infant."</td></tr>
		      <tr><td><b>Preferred Callback Time:</b></td><td>".(($preferred_callback_time!='')?$preferred_callback_time:'N/A')."</td></tr>
					<tr><td valign='top'><b>Enquiry:</b></td><td>".str_replace("\n","<br/>",$remarks)."</td></tr>
				</table>
			</td></tr>
			<tr><td height='5'></td><tr>
			<tr><td colspan='2'>Have fun planning your trip!</td></tr>
			<tr><td height='5'></td><tr>
			<tr><td colspan='2'>Your travel companion,<br />
					TourPackages.com.sg / TripZilla.com<br />
					The simplest way to find and book your travels</td><tr>
			<img src='http://tripzilla.sg/edm/pixelclick.php?id=enquiry-confirmation-email&q=2' border=0></table>";
		
		$statement = "INSERT INTO T_Email (emailfrom,emailto,status,subject,content,created_date,last_updated_date) VALUES (?,?,?,?,?,NOW(),NOW())";
  	$sql = $dbh->prepare($statement);
  	$sql->execute(array('enquiries@tripzilla.com',$sender_email,'approved','Enquiry for '.$title, $body));
  	
  	self::send_email();
  }
  
	static function prepare_feedback_email($name,$email,$type,$subject,$content)
	{
		$dbh = getdbh();
		
		$message = "<table style='font-size:14px'>
			<tr><td>Name: </td><td>".$name."</td></tr>
			<tr><td></td><tr>
			<tr><td>Email: </td><td>".$email."</td></tr>
			<tr><td></td><tr>
			<tr><td>Type: </td><td>".$type."</td></tr>
			<tr><td></td><tr>
			<tr><td>Content: </td><td>".str_replace("\n","<br/>",$content)."</td></tr>
			</table>";
		
		$statement = "INSERT INTO T_Email (emailfrom,emailto,status,subject,content,created_date,last_updated_date) VALUES (?,?,?,?,?,NOW(),NOW())";
		$sql = $dbh->prepare($statement);
		$sql->execute(array("feedback-mailer@tripzilla.com","enquiries@travelogy.com","approved",$subject,$message));
		
		self::send_email();
	}
  
	static function prepare_user_confirmation_email($user_id,$name,$password,$email,$hash=false)
	{
		$dbh = getdbh();
		
		if($password=="")
			$password="{Unable to show due to security reasons}";
		$body="<table>
			<tr><td colspan='2'>Dear ".$name.",</td></tr>
			<tr><td></td><tr>
			<tr><td colspan='2'>Thank you for registering with TourPackages, a product of TripZilla. You may also use this account to login to TripZilla.com.</td></tr>
			<tr><td></td><tr>
			<tr><td colspan='2'>Please add <a href='mailto:edm@tripzilla.com'>edm@tripzilla.com</a> into your address book to receive exclusive and latest travel promotions from us.</td></tr>
			<tr><td>&nbsp;</td><tr>	
			<tr><td colspan='2'><b>Your login details as follow:</b></td></tr>
			<tr><td>
				<table>
				<tr><td><b>User ID:</b></td><td>".$email."</td></tr>
				<tr><td><b>Password:</b></td><td>".$password." (<a href='http://tourpackages.com.sg/user/profile'>change</a>)</td></tr>
				</table>
			</td></tr>
			<tr><td>&nbsp;</td><tr>
			<tr><td colspan='2'>Here's what you can do with a TripZilla account:</td></tr>
			<tr><td>&nbsp;&nbsp;&nbsp;&nbsp;1. Shortlist packages you are interested in</td></tr>
			<tr><td>&nbsp;&nbsp;&nbsp;&nbsp;2. Track your enquiries</td></tr>
			<tr><td>&nbsp;&nbsp;&nbsp;&nbsp;2. Receive exclusive deals from us</td></tr>
			<tr><td>&nbsp;</td><tr>
			<tr><td>
				<a href='http://tripzilla.sg/travel?utm_source=emailregistration-tourpackage&utm_medium=emailregistration&utm_campaign=emailregistration'><button>View Tour Packages</button></a>&nbsp;&nbsp;
				<a href='http://tripzilla.sg/travel-deals?utm_source=emailregistration-traveldeals&utm_medium=emailregistration&utm_campaign=emailregistration'><button>View Travel Deals</button></a>
			<tr><td>&nbsp;</td><tr>	
      <tr><td>Keep travelling!</td><tr>
      <tr><td></td><tr>
      <tr><td>Your travel companion,<br />
			TourPackages.com.sg / TripZilla.com<br />
			<br />
			The simplest way to find and book your travels</td><tr>
      <tr><td></td><tr>
			</table>";
		
		$bcc = 'joanne@travelogy.com';
		
		$statement = "INSERT INTO T_Email (emailfrom,emailto,emailbcc,status,subject,content,created_date,last_updated_date) VALUES (?,?,?,?,?,?,NOW(),NOW())";
		$sql = $dbh->prepare($statement);
		$sql->execute(array("enquiries@tripzilla.com",$email,$bcc,"approved","Your TourPackages/TripZilla account details",$body));
		
		self::send_email();
	}
	
	// company enquiry
	static function prepare_agency_enquiry_email($company_name,$company_email,$name,$sender_email,$contact,$adult,$child,$infant,$remarks)
	{
		$dbh = getdbh();
		
		if($adult=="")
			$adult="N/A";
		if($child=="")
			$child="N/A";	
		if($infant=="")
			$infant="N/A";	
		if($remarks=="")
			$remarks="N/A";
		
/*		$body = "<table style='font-size:14px'>
			<tr><td colspan='2'>Dear valued partner,</td></tr>
			<tr><td></td><tr>
			<tr><td colspan='2'><b>A user of <a href='http://TourPackages.com.sg'>TourPackages.com.sg</a> has sent you and enquiry<b></td></tr>
			<tr><td></td><tr>
			<tr><td></td><tr>
			<tr><td colspan='2'>Please see details of the enquiry below:</td></tr>
			<tr><td></td><tr>
			<tr><td>Customer Name:</td><td>".ucwords($name)."</td></tr>
			<tr><td>Email:</td><td>".$sender_email."</td></tr>
			<tr><td>Contact Number:</td><td>".$contact."</td></tr>
			<tr><td>No. of Adults:</td><td>".$adult."</td></tr>
			<tr><td>No. of Children:</td><td>".$child."</td></tr>
			<tr><td>No. of Infants:</td><td>".$infant."</td></tr>
		  <tr><td>Preferred Callback Time:</td><td>".(($preferred_callback_time!='')?$preferred_callback_time:'N/A')."</td></tr>
			<tr><td valign='top'>Remarks:</td><td>".str_replace("\n","<br/>",$remarks)."</td></tr>	
			<tr><td></td><tr>
			<tr><td colspan='2'>Yours Sincerely,<br />TourPackages.com.sg</td></tr>
			<tr><td colspan='2'>Singapore's Most Comprehensive Travel Portal with Over 2,500 Travel Deals & Tour Packages</td></tr>
			<tr><td height='10'></td><tr>
	    <tr><td colspan='2'><b>About TourPackages.com.sg</b></td></tr>
			<tr><td colspan='2'>TourPackages.com.sg lists thousands of tour packages and travel deals from hundreds of travel agents and companies in Singapore. Our users are able to search for travel packages from the comfort of their home and many of our paying advertisers have benefited greatly from the enquiries received.</td></tr>
	    <tr><td height='5'></td><tr>
      <tr><td colspan='2'><b>Register an account at no cost</b></td></tr>
      <tr><td colspan='2'>If you do not have an account with us, please register for a free account <a href='http://tripzilla.sg/agencies/register/'>here</a>. This will allow you to post/edit/manage your company's profile and tour packages.</td></tr>
      <tr><td height='5'></td><tr>
      <tr><td colspan='2'><b>Questions?</b></td></tr>
      <tr><td colspan='2'>If you have any questions, please email to <a href='mailto:enquiries@travelogy.com'>enquiries@travelogy.com</a> or call (+65) 65695033.</td></tr>
			</table>";
*/
    $content_email = 'benjamin@tripzilla.com';
    $body = "<table style='font-size:14px'>
			<tr><td colspan='2'>Dear valued partner,</td></tr>
			<tr><td></td><tr>
			<tr><td colspan='2'><b>A user of TripZilla Singapore has sent you an enquiry<b></td></tr>
			<tr><td></td><tr>
			<tr><td></td><tr>
			<tr><td colspan='2'>Please see details of the enquiry below:</td></tr>
			<tr><td></td><tr>
			<tr><td>Customer Name:</td><td>".ucwords($name)."</td></tr>
			<tr><td>Email:</td><td>".$sender_email."</td></tr>
			<tr><td>Contact Number:</td><td>".$contact."</td></tr>
			<tr><td>No. of Adults:</td><td>".$adult."</td></tr>
			<tr><td>No. of Children:</td><td>".$child."</td></tr>
			<tr><td>No. of Infants:</td><td>".$infant."</td></tr>
			<tr><td valign='top'>Enquiry:</td><td>".str_replace("\n","<br/>",$remarks)."</td></tr>	
			<tr><td>&nbsp;</td><tr>
			<tr><td colspan='2'><b>Get more customers now</b></td><tr>
			<tr><td colspan='2'>Increase your reach and sell more via TripZilla. Email <a href='mailto:".$content_email."'>".$content_email."</a> today for more information.</td><tr>
			<tr><td>&nbsp;</td><tr>	
			<tr><td colspan='2'>Yours Sincerely,<br />
					TripZilla Singapore<br />
					Singapore's No. 1 Travel Portal
			<img src='http://tripzilla.sg/edm/pixelclick.php?id=agency-enquiry-email&q=2' border=0></td></tr>";

    if($sender_email == 'shakeelaholidays@yahoo.com'){
      $company_email = "terence@tripzilla.com";
    }
        
    if(!is_numeric($contact) || strlen($contact) < 8)
      $statement = "INSERT INTO T_Email (emailfrom,emailto,emailbcc,status,subject,content,created_date,last_updated_date,status) VALUES (?,?,?,?,?,?,NOW(),NOW(),'sent')";
    else
      $statement = "INSERT INTO T_Email (emailfrom,emailto,emailbcc,status,subject,content,created_date,last_updated_date) VALUES (?,?,?,?,?,?,NOW(),NOW())";
    
		$sql = $dbh->prepare($statement);
		$sql->execute(array($sender_email,$company_email,"terence@tripzilla.com","approved",'Enquiry from '.ucwords($name), $body));
		
    if(is_numeric($contact) && strlen($contact) >= 8)
      self::send_email();
	}
	
	// company enquiry - user confirmation
	static function prepare_agency_enquiry_confirmation_email($company_name,$company_email,$name,$sender_email,$contact,$adult,$child,$infant,$remarks)
	{
		$dbh = getdbh();
		
		if($adult=="")
			$adult="N/A";
		if($child=="")
			$child="N/A";
		if($infant=="")
			$infant="N/A";
		if($remarks=="")
			$remarks="N/A";
		
/*		$body = "<table style='font-size:14px'>
			<tr><td colspan='2'>Dear ".$name.",</td><tr>
			<tr><td></td></tr>
			<tr><td colspan='2'>Thank you for using <a href='http://TourPackages.com.sg'>TourPackages.com.sg</a></td></tr>
			<tr><td colspan='2'>The following is the content that is being sent.</td></tr>
			<tr><td></td><tr>
			<tr><td>Customer Name:</td><td>".ucwords($name)."</td></tr>
			<tr><td>Email:</td><td>".$sender_email."</td></tr>
			<tr><td>Contact Number:</td><td>".$contact."</td></tr>
			<tr><td>No. of Adults:</td><td>".$adult."</td></tr>
			<tr><td>No. of Children:</td><td>".$child."</td></tr>
			<tr><td>No. of Infants:</td><td>".$infant."</td></tr>
			<tr><td>Preferred Callback Time:</td><td>".(($preferred_callback_time!='')?$preferred_callback_time:'N/A')."</td></tr>
			<tr><td valign='top'>Remarks:</td><td>".str_replace("\n","<br/>",$remarks)."</td></tr>
			<tr><td></td><tr>
			<tr><td colspan='2'>If you need further assistance, please contact the vendor directly at ".$company_email.".</td></tr>
			<tr><td></td></tr>
			<tr><td colspan='2'>Have fun planning your trip!</td></tr>
			<tr><td></td></tr>
			<tr><td colspan='2'>Your travel companion,<br />
					TourPackages.com.sg<br />
					The simplest way to find and book your travels</td><tr>
			<tr><td></td></tr>
			<tr><td colspan='2' style='font-size:11px'>TourPackages.com.sg is a free service provided by Travelogy.com Pte Ltd that helps you find the best travel deals and tour packages from Singapore with the greatest ease. We are not a travel agent and do not offer any direct bookings of tour packages.</td></tr>
			</table>";
*/      
		$body = "<table style='font-size:14px'>
			<tr><td colspan='2'>Dear ".$name.",</td><tr>
			<tr><td></td></tr>
			<tr><td colspan='2'>Thank you for using <a href='http://TourPackages.com.sg'>TourPackages.com.sg</td></tr>
      <tr><td height='5'></td><tr>
			<tr><td colspan='2'>We have just sent your enquiry to the agency with the information below:</td></tr>
			<tr><td></td><tr>
			<tr><td colspan='2'>
				<table border='1'>
					<tr><td>Customer Name:</td><td>".ucwords($name)."</td></tr>
					<tr><td>Email:</td><td>".$sender_email."</td></tr>
					<tr><td>Contact Number:</td><td>".$contact."</td></tr>
					<tr><td>No. of Adults:</td><td>".$adult."</td></tr>
					<tr><td>No. of Children:</td><td>".$child."</td></tr>
					<tr><td>No. of Infants:</td><td>".$infant."</td></tr>
					<tr><td valign='top'>Enquiry:</td><td>".str_replace("\n","<br/>",$remarks)."</td></tr>
				</table>
			</td></tr>
			<tr><td></td><tr>
			<tr><td colspan='2'>You will be receiving a response from the agency shortly. If you need further assistance, please contact the vendor directly at <a href='mailto:".$company_email."'>".$company_email."</a> or call them at ".$company_contact.".</td></tr>
			<tr><td height='5'></td><tr>
			<tr><td colspan='2'>Have fun planning your trip!</td></tr>
			<tr><td height='5'></td><tr>
			<tr><td colspan='2'>Your travel companion,<br />
					TourPackages.com.sg / TripZilla Singapore<br />
					Singapore's No. 1 Travel Portal<br/><br/>
          TripZilla is a free service provided by Travelogy.com Pte Ltd that helps you find the best travel deals and tour packages from Singapore with the greatest ease. We are not a travel agent and do not offer any direct bookings of tour packages.<br/></td><tr>
			<img src='http://tripzilla.sg/edm/pixelclick.php?id=agency-enquiry-confirmation-email&q=2' border=0></table>";
		
		$statement = "INSERT INTO T_Email (emailfrom,emailto,status,subject,content,created_date,last_updated_date) VALUES (?,?,?,?,?,NOW(),NOW())";
		$sql = $dbh->prepare($statement);
		$sql->execute(array("enquiries@tripzilla.com",$sender_email,"approved",'Enquiry to '.$company_name, $body));
		
		self::send_email();
	}	
	
	static function prepare_reset_password_email($email,$hash,$user_id)
	{
		$dbh = getdbh();
		
		$body = "<table style='font-size:14px'>
			<tr><td colspan='2'>Dear ".$email.",</td></tr>
			<tr><td></td><tr>
			<tr><td colspan='2'>You are receiving this email because we were notified that you have forgotten your password. If you did not request for a new password, please ignore this email.</td></tr>
			<tr><td></td><tr>
			<tr><td colspan='2'>Please click on the link below to change your password. The link expires in 24 hours.</td></tr>
			<tr><td colspan='2'><a href='http://TourPackages.com.sg/user/reset_password/".$hash."/'>http://TourPackages.com.sg/user/reset_password/".$hash."</a></td></tr>
			<tr><td></td><tr>
			<tr><td colspan='2'>If you are experiencing technical difficulties with TourPackages.com.sg, please contact us at <a href='mailto:enquiries@tripzilla.com'>enquiries@tripzilla.com</a></td></tr>
			<tr><td></td><tr>
			<tr><td colspan='2'>Your travel companion,<br />
					TourPackages.com.sg<br />
					The simplest way to find and book your travels</td><tr>
			<tr><td></td><tr>
			<tr><td colspan='2' style='font-size:11px'>TourPackages.com.sg is a free service provided by Travelogy.com Pte Ltd that helps you find the best travel deals and tour packages from Singapore with the greatest ease. We are not a travel agent and do not offer any direct bookings of tour packages.</td></tr>
			</table>";
		
		$statement = "INSERT INTO T_Email (emailfrom,emailto,status,subject,content,created_date,last_updated_date) VALUES (?,?,?,?,?,NOW(),NOW())";
		$sql = $dbh->prepare($statement);
		$sql->execute(array("enquiries@tripzilla.com",$email,"approved","Password Recovery", $body));
		
		self::send_email();
	}
	
	static function prepare_corporate_travel_email($contact_person, $contact_number, $email, $company_name, $preferred_destination, $pax, $additional_comments)
	{
		$dbh = getdbh();
		
		$body = "<table style='font-size:14px'>
			<tr><td>Contact Person: </td><td>".$contact_person."</td></tr>
			<tr><td></td><tr>
			<tr><td>Contact Number: </td><td>".$contact_number."</td></tr>
			<tr><td></td><tr>
			<tr><td>Email Address: </td><td>".$email."</td></tr>
			<tr><td></td><tr>
			<tr><td>Company Name: </td><td>".$company_name."</td></tr>
			<tr><td></td><tr>
			<tr><td>Preferred Destination: </td><td>".implode(', ',$preferred_destination)."</td></tr>
			<tr><td></td><tr>
			<tr><td>Pax: </td><td>".$pax."</td></tr>
			<tr><td></td><tr>
			<tr><td>Additional Comments: </td><td>".str_replace("\n","<br/>",$additional_comments)."</td></tr>
			<tr><td></td><tr>
			<tr><td></td><tr>
			<tr><td>Portal: </td><td>http://".$_SERVER['HTTP_HOST']."</td></tr>
			</table>";
		
		$statement = "INSERT INTO T_Email (emailfrom,emailto,status,subject,content,created_date,last_updated_date) VALUES (?,?,?,?,?,NOW(),NOW())";
		$sql = $dbh->prepare($statement);
		$sql->execute(array("enquiries@tripzilla.com","joanne@travelogy.com,kelly@travelogy.com,eric@travelogy.com","approved","Corporate Travel Enquiry",$body));
		
		self::send_email();
	}
	
  static function send_email($no_to_send=1)
  {
  	$dbh = getdbh();
  	$sql = "SELECT * FROM T_Email WHERE status='approved' ORDER BY emailid LIMIT ".$no_to_send;
  	foreach ($dbh->query($sql) as $row) {
  		$mailer = getmailer();
  		
  		$row['EmailTo'] = explode(',',trim($row['EmailTo']));
  		
  		$message = Swift_Message::newInstance()
  		->setSubject($row['Subject'])
  		->setFrom(array('enquiries@tripzilla.com' => 'TourPackages.com.sg'))
  		->setTo($row['EmailTo'])
  		->setBody($row['Content'],"text/html")
  		->setReplyTo($row['EmailFrom'])
  		;
  		
  		if($row['EmailBcc']!='') {
  			$message->setBcc(explode(',',trim($row['EmailBcc'])));
  		}
  		
  		if(!$mailer->send($message)) {
  			
  			$message = "<table style='font-size:14px'>
			    <tr><td colspan='2'>An error occurred while trying to send an email to ".$row['EmailTo']."</td></tr>
			    <tr><td></td></tr>
			    <tr><td>Email ID: </td><td>".$row['EmailID']."</td></tr>
			    <tr><td>Email Address: </td><td>".$row['EmailTo']."</td></tr>
			    </table>";
	  		
	  		$statement = "INSERT INTO T_Email (emailfrom,emailto,status,subject,content,created_date,last_updated_date) VALUES (?,?,?,?,?,NOW(),NOW())";
	  		$sql = $dbh->prepare($statement);
	  		$sql->execute(array("error-mailer@tripzilla.com","enquiries@tripzilla.com","approved","Email Error Notification",$message));
	  		
			  $statement = "UPDATE T_Email SET status='error', last_updated_date=NOW() WHERE EmailID=?";
			  $sql = $dbh->prepare($statement);
			  $sql->execute(array($row['EmailID']));
			  
			} else {
				
			  $statement = "UPDATE T_Email SET status='sent', last_updated_date=NOW() WHERE EmailID=?";
			  $sql = $dbh->prepare($statement);
			  $sql->execute(array($row['EmailID']));
			  
			}
  	}
  }
}