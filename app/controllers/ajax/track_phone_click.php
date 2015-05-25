<?php 
function _track_phone_click($sort) {
	$dbh = getdbh();
	
	$referer=explode('/',$_SERVER['HTTP_REFERER']);
	$authid = isset($_SESSION['authuid'])?$_SESSION['authuid']:0;
	
	if($referer[3]=='tour' && $referer[4]=='package') { // tour/package
		if(isset($referer[5]) && trim($referer[5])!='')
		{
			$posting_id = $referer[5];
			$company = Company::retrieve_company_by_posting_id($posting_id);
			$company_id = $company['CompanyID'];
			$statement = "INSERT INTO T_Phone_Number_Track (CompanyID,Type,ReferenceID,IP_Address,User_Agent,Http_Referer,Created_By_ID,Created_Date) VALUES (?,?,?,?,?,?,?,NOW())";
			$sql = $dbh->prepare($statement);
			$sql->execute(array($company_id,'tour',$referer[5],$_SERVER['REMOTE_ADDR'],$_SERVER['HTTP_USER_AGENT'],$_SERVER['HTTP_REFERER'],$authid));
		}
	} else { // directory/review
		if(isset($referer[5]) && trim($referer[5])!='')
		{
			$company_id = $referer[5];
			$statement = 'INSERT INTO T_Phone_Number_Track (CompanyID,Type,IP_Address,User_Agent,Http_Referer,Created_By_ID,Created_Date) VALUES (?,?,?,?,?,?,NOW())';
			$sql = $dbh->prepare($statement);
			$sql->execute(array($company_id,'directory',$_SERVER['REMOTE_ADDR'],$_SERVER['HTTP_USER_AGENT'],$_SERVER['HTTP_REFERER'],$authid));
		}
	}
}