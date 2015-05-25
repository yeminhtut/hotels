<?php
function _login()
{
  //$user=User::getUser();
  // var_dump($user);exit;
  // if($user)
  // {
  //   redirect('');
  //   exit();
  // }
  
  $body = View::do_fetch(VIEW_PATH.'user/login.php');
  
  $data['page_title'] = 'TourPackages.com.sg | Login';
  $data['meta_description'] = 'TourPackages.com.sg | Login';
  
  $view = new View(VIEW_PATH.'layouts/layout.php', $data);
  $view->add('body',$body);
  $view->dump();  
}
?>