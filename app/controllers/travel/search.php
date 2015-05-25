<?php
function _search() {
  if($_SERVER['REQUEST_METHOD']=='POST') {
    //=======================================
    // Config & Prework
    //=======================================    
    //echo $_POST['search_min_max_days_slider'];exit;    
    $where=trim($_POST['where']);
    $country_name=$city_name='';
    //$_SESSION['dom_int_redirect']=TRUE; //to display redirecting loading for once /*REMOVE!!*/
      
    //=======================================
    // Inputs & Sanitisation
    //=======================================
    $where=ucwords(str_replace('-', ' ', $where));
    $_SESSION['sk_nonclear']=$where; //track the keyword search
    
    //=======================================
    // Derived Data & Handling
    //=======================================
    $destination=new Destination();
    
    //search dom_int by default set as "international"
    /*if(!isset($_SESSION['dom_int']))
      $_SESSION['dom_int']='international'; REMOVE!!*/
    
    //filter search by travel period
    if(isset($_POST['departure']) && $_POST['departure']!=-1)
      $_SESSION['departure']=$_POST['departure'];
    else
      unset($_SESSION['departure']);
    
    //filter search by no of days
    if(isset($_POST['search_min_max_days_slider']) && $_POST['search_min_max_days_slider']!='1;1'){
      $search_min_max_days_slider = str_replace(';', '-', $_POST['search_min_max_days_slider']);
      $_SESSION['days']=$search_min_max_days_slider;
    }
    else{
      unset($_SESSION['days']);
    }

    //filter search by prices
    if(isset($_POST['search_min_max_price_slider']) && $_POST['search_min_max_price_slider']!='0;0'){
      $search_min_max_price_slider=str_replace(';', '-', $_POST['search_min_max_price_slider']);
      $_SESSION['prices']=$search_min_max_price_slider;
      }    
    else{
      unset($_SESSION['prices']);
    }
    //filter search by type
    if(isset($_POST['search_tour_type']) && $_POST['search_tour_type']!='-1')
      $_SESSION['package_type']=$_POST['search_tour_type'];
    else
      unset($_SESSION['package_type']);    
   
    $_SESSION['where'] = $where;
    
    if($where!=''){
      $destination->retrieve_one("name=?", $where);
      
      if($destination->exists())
        redirect('travel/packages/'.makeslug($destination->get('name')));
      else
        redirect('travel/packages/'.makeslug($where));
    }else
      redirect('travel/packages');
  }else
    redirect('travel/packages');
}