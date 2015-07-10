<?php
function _index() {  
	
	
  $data['body'][]=View::do_fetch(VIEW_PATH.'contact/index.php', $content);
  
  View::do_dump(VIEW_PATH.'layouts/layout.php',$data);
}