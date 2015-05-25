<?php
function _enquire_thankyou($directory_id='',$postslug='') {
	
	if(!$directory_id)
		redirect();
	
	$content['company'] = new Company($directory_id);
	
	if($content['company']->exists()==false)
		redirect();
  
  $data['body'][]=View::do_fetch(VIEW_PATH.'directory/enquire_thankyou.php', $content);
  
  $data['page_title'] = 'Thank you for enquiring '.$content['company']->get('Name');
  $data['meta_description'] = 'Thank you for enquiring '.$content['company']->get('Name');
  
  View::do_dump(VIEW_PATH.'layouts/layout.php',$data);
}