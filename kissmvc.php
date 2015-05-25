<?php
require('kissmvc_core.php');

//===============================================================
// Controller
//===============================================================

class Controller extends KISS_Controller {  
//This function parses the HTTP request to get the controller name, action name and parameter array.
  function parse_http_request() {
    $this->params = array();
    $p = $this->request_uri_parts;
    //var_dump($p);
    if (isset($p[0]) && $p[0] && $p[0][0]!='?')
      $this->controller=$p[0];
    if (isset($p[1]) && $p[1] && $p[1][0]!='?')
      $this->action=$p[1];
    if (isset($p[2]))
      $this->params=array_slice($p,2);

    if(isset($p[1]) && $p[1]=='property')
    {
      $this->controller = 'property';
      $this->action = 'index';
      if(isset($p[2]) && $p[2]=='search'){
        $this->action = 'search';
      }
      
    }
    
    
    // if(isset($p[1]) && $p[1]=='destination') ) {
    // 	$this->controller = 'destination';
    // 	// if(isset($p[2]) && $p[2] !=='') {    		
    // 	// 	$this->action = 'city';
    // 	// 	$this->params=$p;
    // 	// } 
    // }
  	// Routing destination page
  	if(isset($p[1]) && $p[1]=='destination')
  	{  		
  		$this->controller = 'destination';
  		$this->action = 'index';
      if(isset($p[2]) && $p[2] !==''){
        $this->action = 'city';
      }
  	}
    // Routing scraper
    if(isset($p[1]) && $p[1]=='scraper')
    {     
      $this->controller = 'scraper';
      $this->action = 'index';
      
    }

    if(isset($p[1]) && $p[1]=='about')
    {     
      $this->controller = 'about';
      $this->action = 'index';
      
    }
  	
  	if(isset($p[0]) && $p[0]=='directory')
  	{
  		$letters = range('A', 'Z');
  		$letters[] = '_HEX_';
  		$this->controller = 'directory';
  		if(isset($p[1]) && in_array($p[1],$letters)) {
  			$this->action = 'letter';
  			$this->params=array_slice($p,1);
  		} elseif(isset($p[1]) && $p[1]=='review') {
  			$this->action = 'review';
  			$this->params=array_slice($p,2);
  		} elseif(isset($p[1]) && $p[1]=='enquire') {
  			$this->action = 'enquire';
  			$this->params=array_slice($p,2);
  		} elseif(isset($p[1]) && $p[1]=='enquire_thankyou') {
  			$this->action = 'enquire_thankyou';
  			$this->params=array_slice($p,2);  			
  		} else {
  			$this->action = 'index';
  			$this->params=array_slice($p,1);
  		}
  	}
  	
  	if(isset($p[0]) && $p[0]=='corporate-travel')
  	{
  		$this->controller = 'corporatetravel';
  		$this->action = 'index';
  	}
    
    $gCntry=new Deal();
    $gCntry = Deal::get_all_countries();
    foreach ($gCntry as $all_countries) {
      $countries[] = strtolower(str_replace(' ', '-', $all_countries['name']));
    }  
    //$getCountries=$gCntry->retrieve_all_cities();
    // $CtryFilter1=array();
    // foreach($getCountries as $x=>$y){
    //   $CtryFilter1[]=strtolower(str_replace(' ', '-', $getCountries[$x]['CountryName']));
    // }
    // $CtryFilter2=array_unique($CtryFilter1);
    // $countries=array_values($CtryFilter2);
    
    if(isset($p[0]) && $p[0]=='package-deals')
  	{
  		$this->controller = 'package-deals';
      if(isset($p[1]) && $p[1]=='editors-picks') {
        $this->action = 'editor';
      }else if(isset($p[1]) && $p[1]=='travel-agencies-only') {
        $this->action = 'travel';
      }else if(isset($p[1]) && $p[1]=='daily-deals-only') {
        $this->action = 'daily';
      }else if(isset($p[1]) && $p[1]=='all-except-daily-deals') {
        $this->action = 'except';
      }else if(isset($p[1]) && in_array($p[1], $continents) ) {
        $this->action = 'continents';
      }else if(isset($p[1]) && in_array( $p[1] , $countries) ) {
        if(isset($p[2])){
          $this->action = 'cities';
        }else{
          $this->action = 'countries';
        }
      }else{
        $this->action = 'index';
      }
  	}
  	return $this;
  }
  
}
//===============================================================
// View
//===============================================================

class View extends KISS_View {
	
}

//===============================================================
// Model/ORM
//===============================================================

class Model extends KISS_Model  {
	
}
