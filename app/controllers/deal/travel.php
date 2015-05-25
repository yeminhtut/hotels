<?php
function _travel() {
	$deal_obj = new Deal();
	$content['continents'] = $deal_obj->retrieve_travel();
  
  $content['TextHeaderOne']='Tour Package Deals from Singapore - Travel Agencies Only';
  $content['cbDealFilter'] = "travel-agencies-only";
  $content['continents_arr'] = $deal_obj->retrieve_all_continents();
  $content['countries_arr'] = $deal_obj->retrieve_all_countries();
  $content['cities_arr'] = $deal_obj->retrieve_all_cities();
	$data['body'][]=View::do_fetch(VIEW_PATH.'deal/index.php',$content);
  
	$data['page_title'] = 'Tour Package Deals from Singapore - Travel Agencies Only | TourPackages.com.sg';
	$data['meta_description'] = 'View Group Tour or Free & Easy Tour Package Deals from Travel Agencies from Singapore';
	View::do_dump(VIEW_PATH.'layouts/layoutdeals.php',$data);
}