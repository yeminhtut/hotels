<?php
function _registersuccess()
{
	$user=User::getUser();
	if(!$user)
	{
		redirect('user/login');
	}
	
	$user_id = $user->get('UserID');
	$content['email'] = $user->get('Email');
	
  $body = View::do_fetch(VIEW_PATH.'user/registersuccess.php', $content);
  $view = new View(VIEW_PATH.'layouts/layout.php');
  $view->add('body',$body);
  $view->dump();
}
