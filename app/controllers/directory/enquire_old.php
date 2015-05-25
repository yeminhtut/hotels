<?php
function _enquire($directory_id='',$postslug='') {
	//require(APP_PATH.'/inc/securimage/securimage.php');
	
	if(!$directory_id)
		redirect();
	
	$content['company'] = new Company($directory_id);
	
	if($content['company']->exists()==false)
		redirect();
	
	$user_obj = new User();
	
  $content['err_msgs'] = array();
  $content['name'] = '';
  $content['email'] = '';
  $content['contact'] = '';
  $content['adult'] = '';
  $content['child'] = '';
  $content['infant'] = '';
  $content['remarks'] = ''; 
  $content['captcha'] = '';
  
  $user=User::getUser();
  if($user) {
  	$content['name'] = $user->get('Name');
  	$content['email'] = $user->get('Email');
  }
  
  if($_SERVER['REQUEST_METHOD']=='POST') {
  	
  	$content['name'] = trim(filter_var($_POST['name'], FILTER_SANITIZE_STRING));
  	$content['email'] = trim(filter_var($_POST['email'], FILTER_SANITIZE_EMAIL));
  	$content['contact'] = trim(filter_var($_POST['contact'], FILTER_SANITIZE_NUMBER_FLOAT));
  	$content['adult'] =  trim(filter_var($_POST['adult'], FILTER_SANITIZE_NUMBER_FLOAT));
  	$content['child'] = trim(filter_var($_POST['child'], FILTER_SANITIZE_NUMBER_FLOAT));
  	$content['infant'] = trim(filter_var($_POST['infant'], FILTER_SANITIZE_NUMBER_FLOAT));
  	$content['remarks'] = trim(filter_var($_POST['remarks'], FILTER_SANITIZE_STRING));
  	
  	if(empty($content['name']) || $content['name']===FALSE) {
  		$content['err_msgs'][] = 'Please provide your name.';
  	}
  	
  	if(empty($content['email']) || !valid_email($content['email'])) {
  		$content['err_msgs'][] = 'Please provide a valid email address.';
  	}
  	
  	if(empty($content['contact']) || $content['contact']===FALSE) {
  		$content['err_msgs'][] = 'Please provide your contact number.';
  	}
  	
  	$security_code = TRUE;
  	/*if(!$user) {
  		$securimage = new Securimage();
  		$security_code = TRUE;
  		if ($securimage->check($_POST['captcha']) == false) {
  			$content['err_msgs'][] = 'The security code entered was incorrect.';
  			$security_code = FALSE;
  		}
  	}*/
  	
  	if (!empty($content['name']) && $content['name']!==FALSE 
  			&& !empty($content['email']) && $content['email']!==FALSE && valid_email($content['email']) 
  			&& !empty($content['contact']) && $content['contact']!==FALSE 
  			&& $security_code
  	) {
  		// send enquiry
  		Email::prepare_agency_enquiry_email(
  				$content['company']->Name,
  				$content['company']->Email,
  				$content['name'],
  				$content['email'],
  				$content['contact'],
  				$content['adult'],
  				$content['child'],
  				$content['infant'],
  				$content['remarks']
  		);
  		
  		Email::prepare_agency_enquiry_confirmation_email(
  				$content['company']->Name,
  				$content['company']->Email,
  				$content['name'],
  				$content['email'],
  				$content['contact'],
  				$content['adult'],
  				$content['child'],
  				$content['infant'],
  				$content['remarks']
  		);
  		
  		$user_id=0;
  		$user=User::getUser();
  		if($user)
	  		$user_id=$user->get('UserID');
	  				
  		// Add as new user
  		Subscribe::add_subscriber($content['email']);
  		
  		$user = $user_obj->retrieve_one('email = ?',$content['email']);
  		if(!$user)
  		{
  			// New User !!
  			$password = User::generate_password();
  			
  			$user = new User();
  			
  			$user->set('Email', $content['email']);
  			$user->set('Password', md5($password));
  			
  			// Defaults
  			$user->set('Source', 'tp_enquiry');
  			$user->set('RoleID', '4');
  			$user->set('Status', 'approved');
  			$user->set('Facebook_Registered', 'n');
  			$user->set('Facebook_Liked', 'n');
  			$user->set('Created_Date', date('Y-m-d h:i:s'));
  			
  			//Create
  			$user->create();
  			
  			// Update
  			$user->set('Created_By_ID',$user->get('UserID'));
  			$user->set('Last_Updated_Date',date('Y-m-d h:i:s'));
  			$user->set('Last_Updated_By_ID',$user->get('UserID'));
  			$user->update();
  			$user->login(true);
  			
  			$user_id=$user->get('UserID');
  			
  			Email::prepare_user_confirmation_email($user->get('UserID'),$content['email'],$password,$content['email'],$hash=false);
  		}
  		
  		Enquiry_Track::add_entry(
  				$content['company']->CompanyID,
  				0,
  				$content['name'],
  				$content['email'],
  				$content['contact'],
  				$content['adult'],
  				$content['child'],
  				$content['infant'],
  				$content['remarks'],
  				$user_id,
  				'n',
  				''
  		);
  		
  		//logger::insert_log('inquiries', $content['company']->CompanyID);
  		
  		redirect('directory/enquire_thankyou/'.$posting_id.'/'.$postslug);
  	}
  }
  
  $data['body'][]=View::do_fetch(VIEW_PATH.'directory/enquire.php', $content);
  
  $data['page_title'] = 'Enquiry to '.$content['company']->get('Name');
  $data['meta_description'] = 'Enquiry to '.$content['company']->get('Name');
  
  View::do_dump(VIEW_PATH.'layouts/layout.php',$data);
}