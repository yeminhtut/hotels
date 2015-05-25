<?php 
function _index() {
	$content['contact_person'] = '';
	$content['contact_number'] = '';
	$content['email'] = '';
	$content['company_name'] = '';
	$content['preferred_destination'] = '';
	$content['pax'] = '';
	$content['additional_comments'] = '';
	
	$content['err_msgs'] = array();
	
	$content['destinations'] = array(
			'Malaysia',
			'Indonesia',
			'Thailand',
			'Taiwan',
			'Hong Kong',
			'China',
			'Europe',
			'Asia',
			'Asean',
			'USA',
			'Others'
	);
	
	if($_SERVER['REQUEST_METHOD']=='POST') {
		 
		$content['contact_person'] = trim(filter_var($_POST['contact_person'], FILTER_SANITIZE_STRING));
		$content['contact_number'] = trim(filter_var($_POST['contact_number'], FILTER_SANITIZE_NUMBER_FLOAT));
		$content['email'] = trim(filter_var($_POST['email'], FILTER_SANITIZE_EMAIL));
		$content['company_name'] = trim(filter_var($_POST['company_name'], FILTER_SANITIZE_STRING));
		$content['preferred_destination'] = '';
		if(isset($_POST['preferred_destination']))
			$content['preferred_destination'] = $_POST['preferred_destination'];
		$content['pax'] =  trim(filter_var($_POST['pax'], FILTER_SANITIZE_NUMBER_FLOAT));
		$content['additional_comments'] = trim(filter_var($_POST['additional_comments'], FILTER_SANITIZE_STRING));
		 
		if(empty($content['contact_person']) || $content['contact_person']===FALSE) {
			$content['err_msgs'][] = 'Please provide a contact person.';
		}
		 
		if(empty($content['contact_number']) || $content['contact_number']===FALSE) {
			$content['err_msgs'][] = 'Please provide a contact number.';
		}
		
		if(empty($content['email']) || !valid_email($content['email'])) {
			$content['err_msgs'][] = 'Please provide a valid email address.';
		}
		 
		if(empty($content['company_name']) || $content['company_name']===FALSE) {
			$content['err_msgs'][] = 'Please provide a company name.';
		}
		
		if(empty($content['preferred_destination']) || $content['preferred_destination']===FALSE) {
			$content['err_msgs'][] = 'Please provide at least one preferred destination.';
		}
		
		if(empty($content['pax']) || $content['pax']===FALSE) {
			$content['err_msgs'][] = 'Please provide pax.';
		}
		
		if ( !empty($content['contact_person']) && $content['contact_person']!==FALSE 
				&& !empty($content['contact_number']) && $content['contact_number']!==FALSE 
				&& !empty($content['email']) && $content['email']!==FALSE && valid_email($content['email']) 
				&& !empty($content['company_name']) && $content['company_name']!==FALSE 
				&& !empty($content['preferred_destination']) && $content['preferred_destination']!==FALSE 
				&& !empty($content['pax']) && $content['pax']!==FALSE
			)
		{
			// add to db
			Corporate_Travel::add(
				$content['contact_person'], 
				$content['contact_number'], 
				$content['email'],
				$content['company_name'], 
				$content['preferred_destination'], 
				$content['pax'], 
				$content['additional_comments']
		 );
			
			// send enquiry
			Email::prepare_corporate_travel_email(
				$content['contact_person'],
				$content['contact_number'],
				$content['email'],
				$content['company_name'],
				$content['preferred_destination'],
				$content['pax'],
				$content['additional_comments']
			);
			
			$content['success_msg'] = 'Your enquiry has been sent to our customer service officer. <br> Our customer service officer will contact you in the next few working days. <br> Thank you for using TourPackages.com.sg for your corporate travel needs.';
		}
	}
	
	$data['body'][]=View::do_fetch(VIEW_PATH.'corporatetravel/index.php', $content);
	
	$data['page_title'] = 'Corporate Travel Enquiry';
	$data['meta_description'] = 'Corporate Travel Enquiry';
	
	View::do_dump(VIEW_PATH.'layouts/layout.php',$data);
}
