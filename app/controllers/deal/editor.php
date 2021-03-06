<?php
function _editor() {
	$deal_obj = new Deal();
	$content['continents'] = $deal_obj->retrieve_editor();
  
  $content['TextHeaderOne']='Tour Package Deals from Singapore - Editor\'s Picks';
  $content['cbDealFilter'] = "editors-picks";
  $content['continents_arr'] = $deal_obj->retrieve_all_continents();
  $content['countries_arr'] = $deal_obj->retrieve_all_countries();
  $content['cities_arr'] = $deal_obj->retrieve_all_cities();
	$data['body'][]=View::do_fetch(VIEW_PATH.'deal/index.php',$content);
  
	$data['page_title'] = 'Tour Package Deals from Singapore - Editor\'s Picks | TourPackages.com.sg';
	$data['meta_description'] = 'View our Editor\'s picks on the best Tour Package Deals available';
	View::do_dump(VIEW_PATH.'layouts/layoutdeals.php',$data);
}