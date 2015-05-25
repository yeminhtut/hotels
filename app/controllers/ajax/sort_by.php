<?php
function _sort_by(){
  if($_SERVER['REQUEST_METHOD']=='POST'){
    if(!isset($_POST['sort_on']) || !isset($_POST['sort_by']))
      return false;
    
    $sort_array=array('sort_by_popularity', 'sort_by_expiry', 'sort_by_recently', 'sort_by_price', 'sort_by_days');
    
    if(in_array($_POST['sort_on'], $sort_array)==true){
      $_SESSION['sort_on'] = $_POST['sort_on'];
      $_SESSION['sort_by'] = $_POST['sort_by'];
    }else
      return false;
  }else
    redirect('');
}