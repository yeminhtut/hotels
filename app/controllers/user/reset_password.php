<?phpfunction _reset_password($hash){	$content = array();	$content['link_expired'] = FALSE;	  // If already login or just login/sign up via facebook, redirect away  $user = User::getUser();  if($user)    redirect('');    $result = Hash::retrieve_user($hash, TRUE);
  if(!$result)
  {  	$content['error_msg'] = 'The link has expired or is invalid.';
  	 
  	$content['link_expired'] = TRUE;  }    if(isset($_POST['password'])) {	  $n_password = $_POST['password'];	  $n_password2 = $_POST['password2'];	  	  if($n_password=='') {	  	$content['error_msg'] = 'Please provide a password';	  } elseif($n_password != $n_password2) {	  	$content['error_msg'] = 'Password mismatch';	  }	  	  if($n_password!='' && $n_password == $n_password2) {	  	$result = Hash::retrieve_user($hash);
	  	if($result)
	  	{	  		$user_id = $result['UserID'];	  		$user = new User($user_id);	  		
	  		$result = User::update_password($user_id,$n_password);	  		if($result) {	  			//$user->login(true);	  			  			$content['success_msg'] = 'Your password has been changed. You\'re now signed in. You can now update your <a href="/user/profile">profile</a> i.e. personal details, subscription, etc.';	  		}
	  	}	  }  }    $body = View::do_fetch(VIEW_PATH.'user/reset_password.php', $content);  $data['page_title'] = 'TourPackages.com.sg | Reset Password';
  $data['meta_description'] = 'TourPackages.com.sg | Reset Password';    $view = new View(VIEW_PATH.'layouts/layout.php', $data);  $view->add('body',$body);  $view->dump();       }