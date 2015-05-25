<?php
function _shortlist()
{
	$temp = array();
	$content = array();
	
	$user=User::getUser();
	if($user)
	{
		$user_id = $user->get('UserID');
	
		$result = Shortlist::retrieve_shortlists_by_user_id($user_id);
		
		if($result)
		{
			foreach($result as $row)
			{
				$temp[] = $row['PostingID'];
			}
			$temp = implode(",", $temp);
		}		
	} else {
    if(isset($_SESSION['shortlist']))
      $temp=$_SESSION['shortlist'];
	}
	
	if(count($temp)) {
		$result = Posting::retrieve_posting(array("designated"=>$temp));
		if($result) {
			foreach($result as $k=>$package) {
				$reference_type='posting';
				$reference_id=$package['PostingID'];
				
				$posting_image = TN_PATH.'square/100x100/no_image.jpg';
				if($package['Image_Type']=='custom')
				{
					$file = new File();
					$file = File::retrieve_random_file($reference_id,$reference_type);
				
					if($file!='' && $file->exists())
						$posting_image = TN_PATH.'square/100x100/'.$file->get('FileID').'.'.$file->get('Extension');
				}
				else
				{
					$country_obj = Country::retrieve_random_posting_country_and_city($reference_id);
					$country_id = $country_obj['CountryID'];
					$file = new File();
					$file = File::retrieve_random_file($country_id,'country');
					if($file!='' && $file->exists())
						$posting_image = TN_PATH.'square/100x100/'.$file->get('FileID').'.'.$file->get('Extension');
				}
				
				if(strlen($package['Description']) > 160) {
					$package['Description'] = substr($package['Description'], 0, 160).'...';
				}
					
				$company_result = Company::retrieve_company_by_posting_id($package['PostingID']);
				$company = new Company($company_result['CompanyID']);
				
				$content['packages'][$k] = array(
						'Title' => $package['Title'],
						'PostingID' => $package['PostingID'],
						'Tour_Type' => $package['Tour_Type'],
						'CompanyID' => $package['CompanyID'],
						'Description' => $package['Description'],
						'CompanyName' => $company->Name,
						'Currency' => $package['Currency'],
						'Price' => $package['Price'],
						'Image' => $posting_image
				);
			}
		}
	}
	
	$data['page_title'] = 'Shortlisted Packages';
	
	
  $body = View::do_fetch(VIEW_PATH.'user/shortlist.php', $content);
  $view = new View(VIEW_PATH.'layouts/layout.php', $data);
  $view->add('body',$body);
  $view->dump();
}
