<?php
function _index() {  
	require(APP_PATH.'/inc/securimage/securimage.php');
	
	$content['name'] = '';
	$content['email'] = '';
	$content['type'] = '';
	$content['subject'] = '';
	$content['message'] = '';
	$content['captcha'] = '';
	
	$content['success_msg'] = false;
	$content['err_msg'] = false;
	
	if($_SERVER['REQUEST_METHOD']=='POST') {
		$content['name'] = $_POST['name'];
		$content['email'] = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
		$content['type'] = $_POST['type'];
		$content['subject'] = $_POST['subject'];
		$content['message'] = $_POST['message'];
		
		$security_code = TRUE;
		if(!$user) {
			$securimage = new Securimage();
			if ($securimage->check($_POST['captcha']) == false) {
				$content['err_msgs'][] = 'The security code entered was incorrect.';
				$security_code = FALSE;
			}
		}
		
		if($content['name']!='' && 
				$content['email']!='' &&
				filter_var($content['email'], FILTER_VALIDATE_EMAIL) &&
				$content['type']!='' && 
				$content['subject']!='' && 
				$content['message']!='' &&
				$security_code ) {
			
			Email::prepare_feedback_email($content['name'],$content['email'],$content['type'],$content['subject'],$content['message']);
			
			$content['success_msg'] = 'We have received your feedback and will get back to you soon!';
		} else {
			$content['err_msg'] = 'Please ensure that all fields have been correctly filled in!';
		}
	}
	
  $data['body'][]=View::do_fetch(VIEW_PATH.'contact/index.php', $content);
  
  View::do_dump(VIEW_PATH.'layouts/layout.php',$data);
}