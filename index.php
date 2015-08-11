<?php
//===============================================
// Debug
//===============================================
ini_set('display_errors','ON');
error_reporting(0);

//===============================================
// mod_rewrite
//===============================================
//Please configure via .htaccess or httpd.conf

//===============================================
// Madatory KISSMVC Settings (please configure)
//===============================================
define('APP_PATH','app/'); //with trailing slash pls
define('WEB_FOLDER','/hotels'); //with trailing slash pls
define('VIEW_PATH',APP_PATH.'views/'); //with trailing slash pls
define('WEB_DOMAIN','http://'.$_SERVER['HTTP_HOST']); //with http:// and NO trailing slash pls
define('FILES_PATH',''); //with trailing slash pls
define('IMG_PATH','/img/'); //with trailing slash pls

define('SITE_TEMPLATE','sg');
define('SITE_COUNTRY','Singapore');
define('CURRENCY_CODE','SGD');
define('CUR_SYMBOL','$');


//===============================================
// Other Settings
//===============================================
$GLOBALS['sitename']='Tripzilla';

$GLOBALS['pagination']['per_page'] = 10;

//===============================================
// Includes
//===============================================
require('kissmvc.php');
require(APP_PATH.'/inc/functions.php');
require 'vendor/autoload.php';
// Twitter OAuth
require(APP_PATH.'inc/twitteroauth/twitteroauth.php');

// OpenGraph Parser
require(APP_PATH.'inc/OpenGraph.php');

//===============================================
// Session
//===============================================

session_start();

//===============================================
// Uncaught Exception Handling
//===============================================s
set_exception_handler('uncaught_exception_handler');

function uncaught_exception_handler($e) {
  ob_end_clean(); //dump out remaining buffered text
  $vars['message']=$e;
  die(View::do_fetch(VIEW_PATH.'errors/exception_uncaught.php',$vars));
}

function custom_error($msg='') {
  $vars['msg']=$msg;
  die(View::do_fetch(VIEW_PATH.'errors/custom_error.php',$vars));
}

//===============================================
// Database
//===============================================

function getdbh() {
  if (!isset($GLOBALS['dbh']))
    try {
      $GLOBALS['dbh'] = new PDO('mysql:host=localhost;dbname=hotels', 'root', '');

    } catch (PDOException $e) {
      die('Connection failed: '.$e->getMessage());
    }
  return $GLOBALS['dbh'];
}

//===============================================
// Mailer (Swiftmail)
//===============================================

function getmailer() {
  if (!isset($GLOBALS['mailer'])) {
    include_once('/var/www/sites/inc/Swift-4.3.0/lib/swift_required.php');
    //$transport = Swift_SmtpTransport::newInstance('localhost', 25, 'username', 'password');
    //$transport = Swift_SendmailTransport::newInstance('/usr/sbin/sendmail -bs');
    $transport = Swift_SmtpTransport::newInstance('localhost', 25);
    /*$transport = Swift_SmtpTransport::newInstance('smtp.mailgun.org', 25)
    ->setUsername('edm@tripzilla.com')
    ->setPassword('edm1234!')
    ;*/
    $GLOBALS['mailer'] = Swift_Mailer::newInstance($transport);
  }
  return $GLOBALS['mailer'];
}

//===============================================
// Autoloading for Business Classes
//===============================================
// Assumes Model Classes start with capital letters and Helpers start with lower case letters

function kiss_autoloader($classname) {
	// exclude Swift autoloaders
	if (strpos($classname, 'Swift_')!==FALSE) {
		return;
	}
	
  $a=$classname[0];
  if ($a >= 'A' && $a <='Z')
    require_once(APP_PATH.'models/'.$classname.'.php');
  else
    require_once(APP_PATH.'helpers/'.$classname.'.php');  
}

spl_autoload_register('kiss_autoloader');

//===============================================
// Start the controller
//===============================================
$controller = new Controller(APP_PATH.'controllers/',WEB_FOLDER,'main','index');
