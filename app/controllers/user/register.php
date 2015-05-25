<?php

// Long Form not used for now

function _register()
{
  // If already login or just login/sign up via facebook, redirect away
  $user = User::getUser();
  if($user)
    redirect('');
  
  $data['page_title'] = 'TourPackages.com.sg | Registration';
  $data['meta_description'] = 'TourPackages.com.sg | Registration';
  
  $body = View::do_fetch(VIEW_PATH.'user/register.php');
  $view = new View(VIEW_PATH.'layouts/layout.php', $data);
  $view->add('body',$body);
  $view->dump();
}
