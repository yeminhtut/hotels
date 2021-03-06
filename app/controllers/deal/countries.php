<?php
function _countries() {
	$deal_obj = new Deal();
	$content['continents_arr'] = $deal_obj->retrieve_all_continents();
    $content['countries_arr'] = $deal_obj->retrieve_all_countries();
    $content['cities_arr'] = $deal_obj->retrieve_all_cities();
  
	$tw=explode('/', $_SERVER['REQUEST_URI']);
    $content['cbDealFilter'] = ucwords(str_replace('-', ' ', $tw[2]));
    $content['continents'] = $deal_obj->retrieve_deals_countries(ucwords(str_replace('-', ' ', $tw[2])));
	
	$data['body'][]=View::do_fetch(VIEW_PATH.'deal/index.php',$content);
  
    $CountryName=ucwords(str_replace('-', ' ', $tw[2]));
    $content['TextHeaderOne']=$CountryName.' Tour Package Deals from Singapore';
	$data['page_title'] = $CountryName.' Tour Package Deals from Singapore | TourPackages.com.sg';
	$data['meta_description'] = 'View '.$CountryName.' Group Tour or Free & Easy Tour Package Deals from Travel Agencies and Daily Deals Sites from Singapore';
	View::do_dump(VIEW_PATH.'layouts/layoutdeals.php',$data);
}