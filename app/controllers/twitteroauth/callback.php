<?php
function _callback() { 

  /* If the oauth_token is old redirect to the connect page. */
  if (isset($_REQUEST['oauth_token']) && $_SESSION['oauth_token'] !== $_REQUEST['oauth_token']) {
    $_SESSION['oauth_status'] = 'oldtoken';
    redirect('twitteroauth/clearsessions');
  }

  /* Create TwitteroAuth object with app key/secret and token key/secret from default phase */
  $connection = new TwitterOAuth(CONSUMER_KEY, CONSUMER_SECRET, $_SESSION['oauth_token'], $_SESSION['oauth_token_secret']);

  /* Request access tokens from twitter */
  $access_token = $connection->getAccessToken($_REQUEST['oauth_verifier']);

  /* Save the access tokens. Normally these would be saved in a database for future use. */
  $_SESSION['access_token'] = $access_token;

  /* Remove no longer needed request tokens */
  unset($_SESSION['oauth_token']);
  unset($_SESSION['oauth_token_secret']);

  /* If HTTP response is 200 continue otherwise send to connect page to retry */
  if (200 == $connection->http_code) {
    /* The user has been verified and the access tokens can be saved for future use */
    $_SESSION['status'] = 'verified';
	
		$user_id = $access_token['user_id'];
		
		// create user and login
		$user=new User();
		$user->retrieve_one('uid=?',$user_id);
	    if (!$user->exists()) {
			// get user details
			$method = 'users/show/'.$user_id;
			$result = $connection->get($method);
			
			if(sizeof($result)>0) {
				$user->set('uid',$user_id);
				$user->set('name',$result->name);
				$user->set('location',$result->location);			
				
				/*Subscribe::add_subscriber($content['email']);
				
				$user = $user_obj->retrieve_one('email = ?',$content['email']);
				if(!$user)
				{
					// New User !!
						
					$password = User::generate_password();
						
					$user = new User();
						
					$user->set('Email', $content['email']);
					$user->set('Password', md5($password));
						
					// Defaults
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
				}*/
			}
		} else {
			//Login Succeeded
			$_SESSION['authuid']=$user_id;
			redirect('main/index');
		}
  } else {
    /* Save HTTP status for error dialog on connnect page.*/
    redirect('twitteroauth/clearsessions');
  }

}