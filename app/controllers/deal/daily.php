<?php
function _daily() {
	$deal_obj = new Deal();
	$content['continents'] = $deal_obj->retrieve_daily();
  
  $content['TextHeaderOne']='Tour Package Deals from Singapore - Daily Deals Only';
  $content['cbDealFilter'] = "daily-deals-only";
  $content['continents_arr'] = $deal_obj->retrieve_all_continents();
  $content['countries_arr'] = $deal_obj->retrieve_all_countries();
  $content['cities_arr'] = $deal_obj->retrieve_all_cities();
	$data['body'][]=View::do_fetch(VIEW_PATH.'deal/index.php',$content);
  
	$data['page_title'] = 'Tour Package Deals from Singapore - Daily Deals Only | TourPackages.com.sg';
	$data['meta_description'] = 'View Group Tour or Free & Easy Tour Package Deals from Daily Deals Sites from Singapore';
	View::do_dump(VIEW_PATH.'layouts/layoutdeals.php',$data);
}