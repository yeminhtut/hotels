<?php
function _enquire_thankyou($posting_id='',$postslug='')
{
  
	if(!$posting_id){
    redirect();
  }    
  else{
    $id=(int)$posting_id;
    $package = new Package();
    $company = new Company();
  }
  $package->retrieve($id);
  if(!$package->exists()){
    redirect();
  }
  else
  {
    $content['posting'] = $package;   
    // $content['departure_dates'] = Posting::retrieve_departure_dates($posting->get('PostingID'));
    // $company_arr = Company::retrieve_company_by_posting_id($posting_id);
    // $content['company'] = new Company($company_arr['CompanyID']);
  }
  
  $data['body'][]=View::do_fetch(VIEW_PATH.'tour/enquire_thankyou.php', $content);
  
  $data['page_title'] = 'Thank you for enquiring '.$content['posting']->get('title');
  $data['meta_description'] = 'Thank you for enquiring '.$content['posting']->get('title');
  
  View::do_dump(VIEW_PATH.'layouts/layout.php',$data);
}