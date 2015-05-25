<?php// Process registration via Form Submissionfunction _process_register(){  if($_SERVER['REQUEST_METHOD']=='POST')  {  	    // Validate  	$_SESSION['register']['email'] = trim(filter_var($_POST['email'], FILTER_SANITIZE_EMAIL));  			if(!valid_email($_SESSION['register']['email'])) {      $_SESSION['register']['err_msg'] = 'The Email field must contain a valid email address.';      redirect('user/register');		}				// Check if email already exists		$user = new User();
		$user = $user->retrieve_one('email = ?',$_SESSION['register']['email']);
		
		if($user && $user->get('UserID')>0)
		{
			// email already exists
			$_SESSION['login']['email'] = $_SESSION['register']['email'];
			$_SESSION['login']['err_msg'] = 'Your email address has been registered with <a href="http://tourpackages.com">TourPackages.com.sg</a> or <a href="http://tripzilla.sg">TripZilla.com</a>';			
			redirect('user/login');
		}        if(isset($_POST['from']) && $_POST['from']=='form') {    	    	if(!isset($_POST['agree']) && $_POST['agree']!='agree' ) {    		$_SESSION['register']['err_msg'] = 'Please read the terms and condition.';
    		redirect('user/register');    	}    }    // New User !!
    $user = new User();
    $created_date_time = date('Y-m-d h:i:s');    $pwd = substr(myHash($email,$created_date_time),0,8);
    
    $user->set('Email', $_SESSION['register']['email']);
    $user->set('Password', md5($pwd));
    
    // Defaults    $user->set('Source', 'tp');
    $user->set('RoleID', '4');
    $user->set('Status', 'approved');
    $user->set('Facebook_Registered', 'n');
    $user->set('Facebook_Liked', 'n');
    $user->set('Created_Date', $created_date_time);
    
    //Create
    $user->create();
    // Update
    $user->set('Created_By_ID',$user->get('UserID'));
    $user->set('Last_Updated_Date',date('Y-m-d h:i:s'));
    $user->set('Last_Updated_By_ID',$user->get('UserID'));
    $user->update();
    $user->login(true);
    
    // subscribe
    Subscribe::add_subscriber($_SESSION['register']['email']);
    
    Email::prepare_user_confirmation_email($user->get('UserID'),$_SESSION['register']['email'],$pwd,$_SESSION['register']['email'],$hash=false);
    
    redirect('user/registersuccess');  }  else    redirect('user/register');}