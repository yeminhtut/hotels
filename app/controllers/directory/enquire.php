<?php
function _enquire($directory_id='',$postslug='') {	
	if(!$directory_id)
		redirect();
	
	$company = new Company($directory_id);
	
	if(!$company->exists()){
		redirect();
	}else{
		$microsite=new Microsite();
		$microsite->retrieve_one("cid=?", array($company->get('id')));
		
		$enquiry_url="http://tripzilla.sg/directory/review/".$company->get('id')."/".url_title($company->get('display_name'));
		if($microsite->exists())
			$enquiry_url="http://tripzilla.sg/".$microsite->get('folder_name')."/enquire";
		header('Location: '.$enquiry_url,TRUE,302);
	}
}