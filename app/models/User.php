<?php
class User extends Model {

  function __construct($id='') {
    parent::__construct('id','user');
    
    $this->rs['id'] = '';
    $this->rs['cid'] = '';
    $this->rs['fbid'] = '';
    $this->rs['email'] = '';
    $this->rs['fullname'] = '';
    $this->rs['dob'] = '0000-00-00 00:00:00';
    $this->rs['gender'] = '';
    $this->rs['contact'] = '';
    $this->rs['address'] = '';
    $this->rs['password'] = '';
    $this->rs['roleid'] = '';
    $this->rs['status'] = '';
    $this->rs['source'] = '';
    $this->rs['lastlogin'] = '0000-00-00 00:00:00';
    $this->rs['lastip'] = '';
    $this->rs['created_dt'] = '0000-00-00 00:00:00';
    
    if ($id)
      $this->retrieve($id);
  }
  
  function import_user($obj){
    $this->set( 'id' , $obj['UserID']);
    $this->set( 'cid' , $obj['CompanyID']);
    $this->set( 'fbid' , $obj['FacebookID']);
    $this->set( 'email' , $obj['Email']);
    $this->set( 'fullname' , $obj['Name']);
    $this->set( 'dob' , $obj['Date_Of_Birth']);
    $this->set( 'gender' , $obj['Gender']);
    $this->set( 'password' , $obj['Password']);
    $this->set( 'roleid' , $obj['RoleID']);
    $this->set( 'status' , $obj['Status']);
    $this->set( 'source' , $obj['Source']);
    $this->set( 'lastlogin' , $obj['Last_Login_Date']);
    $this->set( 'lastip' , '');
    $this->set( 'created_dt' , $obj['Created_Date']);

    return $this;
  }

  function hasPermission($action) {
    return TRUE;
  }

  function login($saveincookie=false) {
    $this->set('lastlogin',date('Y-m-d H:i:s'));
    $this->set('lastip',ip::getUserIP());
    $this->update();
    if ($saveincookie) {
      $cookiename=$GLOBALS['config']['cookiename'];
      $expire=time()+60*$GLOBALS['config']['cookieexpire'];
      setcookie($cookiename.'[useru]',$this->get('email'),$expire,'/');
      setcookie($cookiename.'[userp]',md5($GLOBALS['config']['secretstring'].$this->get('password')),$expire,'/');
    }
  }
  
  static function rolePermission(){
    $data_entry_access=array(
      'dashboard', 
      'package->add', 
      'package->edit', 
      'packages->active', 
      'packages->inactive', 
      'packages->expired', 
      'deal->add', 
      'deal->edit', 
      'deals->active', 
      'deals->inactive', 
      'deals->expired', 
      'files', 
      'file->add', 
      'file->edit', 
      'user-profile' 
    );
    
    $travel_agency_admin_access=array(
      'dashboard', 
      'package->add', 
      'package->edit', 
      'packages->active', 
      'packages->inactive', 
      'packages->expired', 
      /*'deal->add', 
      'deal->edit', 
      'deals->active', 
      'deals->inactive', 
      'deals->expired', */
      'package_enquiries', 
      'view_enquiry',
			'ecommerce',
      //'deal_enquiries', 
      'files', 
      'file->add', 
      'file->edit', 
      'company_profile' 
    );
    
    $user_access=array(
      'user->profile'
    );
    
    $request_uri=explode('/', ltrim(rtrim($_SERVER['REQUEST_URI'], '/'), '/'));
    $uri_access=$request_uri[0];
    $request_access=$request_uri[1];
    if(isset($request_uri[2]))
      $request_access=$request_uri[1]."->".$request_uri[2];
    
    $user=new User($_SESSION['authuid']);
    $roleid=false;
    if($user->exists())
      $roleid=$user->get('roleid');
    
    if($roleid==1){
      return true;
    }else if($roleid==2){
      if($uri_access=="back-office")
        foreach($data_entry_access as $access)
          if(strpos($request_access, $access)!==false)
            return true;
    }else if($roleid==3){
      if($uri_access=="backend")
        foreach($travel_agency_admin_access as $access)
          if(strpos($request_access, $access)!==false)
            return true;
    }else if($roleid==4){
      foreach($user_access as $access)
        if(strpos($request_access, $access)!==false)
          return true;
    }
    
    User::logout();
    return false;
  }
  
  static function userPermission($cid=false){
    if($cid){
      $user=new User($_SESSION['authuid']);
      $user_cid=0;
      if($user->exists())
        if($user->get('roleid')==1)
          return true;
        else
          $user_cid=$user->get('cid');
      
      if($user_cid==$cid)
        return true;
    }
    
    return false;
  }
  
  static function adminPermission($roleid=false){
    $cid=(int)$_SESSION['authcid'];
    if(empty($roleid) && !is_numeric($roleid) && $roleid!=1 && $cid==0)
      return false;
    
    return $cid;
  }

  static function follow($fuid,$luid) {
    $follower=new User($fuid);
    if (!$follower->exists())
      return false;
    $leader=new User($luid);
    if (!$leader->exists())
      return false;
    $follower->set('following',addToSet($luid,$follower->get('following')));
    $follower->update();
    $leader->set('followers',addToSet($fuid,$leader->get('followers')));
    $leader->update();
    return true;
  }

  static function unfollow($fuid,$luid) {
    $follower=new User($fuid);
    if ($follower->exists()) {
      $follower->set('following',removeFromSet($follower->get('following'),$luid));
      $follower->update();
    }
    $leader=new User($luid);
    if ($leader->exists()) {
      $leader->set('followers',removeFromSet($leader->get('followers'),$fuid));
      $leader->update();
    }
  }

	static function logout() {
    unset($_SESSION['authuid']);
    unset($_SESSION['authcid']);
    //clear cookies
    $cookiename=$GLOBALS['config']['cookiename'];
    $expire=time()-3600;
    setcookie($cookiename.'[useru]','',$expire,'/');
    setcookie($cookiename.'[userp]','',$expire,'/');
    unset($_COOKIE[$cookiename]['useru']);
    unset($_COOKIE[$cookiename]['userp']);
	}

  static function tryCookieLogin() {
    //try cookie login
    $cookiename=$GLOBALS['config']['cookiename'];
    if (isset($_COOKIE[$cookiename])) {
      $email=trim($_COOKIE[$cookiename]['useru']);
      $hashedpassword=$_COOKIE[$cookiename]['userp'];
      $user=new User();
      $user->retrieve_one('email=?',$email);
      if ($user->exists() && md5($GLOBALS['config']['secretstring'].$user->get('password'))==$hashedpassword) {
        $_SESSION['authuid']=$user->get('id');
        $expire=time()+60*$GLOBALS['config']['cookieexpire'];
        setcookie($cookiename.'[useru]',$user->get('email'),$expire,'/');
        setcookie($cookiename.'[userp]',md5($GLOBALS['config']['secretstring'].$user->get('password')),$expire,'/');
        $user->login();
        $GLOBALS['authuser']=$user;
        return $GLOBALS['authuser'];
      }
    }
    return false;
  }

  static function getID() {
    if (isset($_SESSION['authuid']) && $_SESSION['authuid'])
      return $_SESSION['authuid'];
    else
      return false;
  }

  static function getUser() {
    if (isset($GLOBALS['authuser']))
      return $GLOBALS['authuser'];
    if ($id=User::getID()) {
      $GLOBALS['authuser']=new User($id);
      return $GLOBALS['authuser'];
    }
    //try cookie login
    return User::tryCookieLogin();
  }
  
  function generate_avatar_url($id,$photoid='')
  {
  	$user=new User($id);
  	if($user->exists())
  	{
  		$size = 75;
  		$email = $user->get('email');
  		if($photoid)
  		$photoid=(int)$photoid;
  		else
  		$photoid=$user->get('photoid');
  		
  		$photo=new Photo($photoid);
  		$tn_url=$photo->get_tn_url('75x75');
		  $grav_url = "gravatar_id=".md5(strtolower($email))."&default=".urlencode($tn_url)."&size=".$size;
		  return $grav_url;
		} 
  }
}