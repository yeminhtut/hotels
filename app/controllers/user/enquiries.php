<?php
function _enquiries()
{
	$user=User::getUser();
	if(!$user)
	{
		redirect('user/login');
	}
	
	$user_id = $user->get('UserID');
	$email = $user->get('Email');
	
	$content['enquiries'] = Enquiry_Track::retrieve_enquiries_by_email($email);
	
  $body = View::do_fetch(VIEW_PATH.'user/enquiries.php', $content);
  $view = new View(VIEW_PATH.'layouts/layout.php');
  $view->add('body',$body);
  $view->dump();
}
