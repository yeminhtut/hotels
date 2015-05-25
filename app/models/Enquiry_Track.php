<?php
Class Enquiry_Track extends Model
{
	function __construct($id='')
	{
		parent::__construct('Enquiry_Track_ID','T_Enquiry_Track','getdbh');
		$this->rs['Enquiry_Track_ID']=0;
		$this->rs['Name']='';
		$this->rs['Email']='';
		$this->rs['Contact_No']='';
		$this->rs['Preferred_Callback_Time']='';
		$this->rs['Adults']='';
		$this->rs['Children']='';
		$this->rs['Infants']='';
		$this->rs['Remarks']='';
		$this->rs['CompanyID']='';
		$this->rs['PostingID']='';
    $this->rs['Ad_Type']='';
		$this->rs['Status']='';
		$this->rs['Bulk_sent']='';
		$this->rs['Source']='tp';
		$this->rs['Created_By_ID']='';
		$this->rs['Created_Date']='';
		$this->rs['Last_Updated_By_ID']='';
		$this->rs['Last_Updated_Date']='';

		if ($id)
			$this->retrieve($id);
	}
	
	function add_entry($company_id,$posting_id,$name,$email,$contact,$adult,$child,$infant,$remarks,$user_id,$bulk,$preferred_callback_time, $type='posting')
	{
		$dbh = getdbh();
    
		$sql = $dbh->prepare("SELECT `Featured` FROM `T_Directory` WHERE `CompanyID` = ?");
		$sql->execute(array($company_id));
		$featured = $sql->fetchColumn();
    $ad_type = $featured=='y' ? 'premium':'basic';
    
		$statement = "INSERT INTO T_Enquiry_Track 
				(companyid,postingid,Type,ReferenceID,Ad_Type,name,email,contact_no,preferred_callback_time,adults,children,infants,remarks,bulk_sent,source,created_by_id,created_date,last_updated_by_id,last_updated_date) 
				VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?,'tp',?,NOW(),?,NOW())";
		$sql = $dbh->prepare($statement);
		$sql->execute(array($company_id,$posting_id,$type,$posting_id,$ad_type,$name,$email,$contact,$preferred_callback_time,$adult,$child,$infant,$remarks,$bulk,$user_id,$user_id));
	}
	
	function retrieve_enquiries_by_email($email)
	{
		$dbh = getdbh();
		
		$statement = 'SELECT T_Enquiry_Track.Remarks, T_Enquiry_Track.`Created_Date`, T_Posting.PostingID, T_Posting.Title, T_Directory.CompanyID, T_Directory.Name, T_Microsite.Subdomain, T_Microsite.Status AS MicrositeStatus
				FROM `T_Enquiry_Track`
				LEFT JOIN T_Posting ON T_Enquiry_Track.PostingID = T_Posting.PostingID
				LEFT JOIN T_Directory ON T_Directory.CompanyID = T_Enquiry_Track.CompanyID
				LEFT JOIN T_Microsite ON T_Microsite.CompanyID = T_Directory.CompanyID
				WHERE T_Enquiry_Track.`Email` = ?
				ORDER BY `T_Enquiry_Track`.`Created_Date` DESC';
		$sql = $dbh->prepare($statement);
		$sql->execute(array($email));
		
		return $sql->fetchAll();
	}
}
