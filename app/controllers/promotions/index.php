<?php
function _index()
{  
  $data['body'][]=View::do_fetch(VIEW_PATH.'promotions/index.php');
  
  View::do_dump(VIEW_PATH.'layouts/layout.php',$data);
}