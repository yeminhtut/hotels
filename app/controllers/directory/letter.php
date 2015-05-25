<?php
function _letter($letter, $offset='') {
  
  $company = new Company();
 $content['featured_companies']=$company->retrieve_featured_travel_agencies(1);
  
  $content['companies'] = $company->retrieve_directories_by_alphabet($letter);
  $total_companies = count($content['companies']);
  
  $content['pagination']=pagination::makePagination($offset,$total_companies,myUrl('directory/'.$letter),$GLOBALS['pagination']);
  
  $content['companies'] = $company->retrieve_directories_by_alphabet($letter, $GLOBALS['pagination']['per_page'], $offset);
  
  $data['body'][]=View::do_fetch(VIEW_PATH.'directory/index.php',$content);
  
  $per_page_title = ' - Starts with letter '.$letter;
  if(is_numeric($offset))
  	$per_page_title .= ' - Page '.(($offset / $GLOBALS['pagination']['per_page']) + 1);
  
  $data['page_title'] = 'Travel Agencies Directory, Singapore'.$per_page_title;
  $data['meta_description'] = 'An exhaustive list of travel agencies in Singapore are listed on this directory. Find their tour packages on TourPackages.com.sg'.$per_page_title;
  
  View::do_dump(VIEW_PATH.'layouts/layout.php',$data);
}