<?php
Class Corporate_Travel extends Model
{
  function __construct($id='')
  {
		parent::__construct('Corporate_Travel_ID','T_Corporate_Travel','getdbh');
		$this->rs['Corporate_Travel_ID']=0;
		$this->rs['Contact_Person']='';
		$this->rs['Contact_Number']='';
		$this->rs['Email']='';
		$this->rs['Company_Name']='';
		$this->rs['Pax']='';
		$this->rs['Additional_Comments']='';
		$this->rs['Created_Date']='';

	  if ($id)
	    $this->retrieve($id);
  }
  
  static function add($contact_person, $contact_number, $email, $company_name, $preferred_destination, $pax, $additional_comments) {
  	$dbh = getdbh();
  	 
  	$statement = "INSERT INTO `T_Corporate_Travel`
					(`Contact_Person`, `Contact_Number`, `Email`, `Company_Name`, `Preferred_Destination`, `Pax`, `Additional_Comments`, `Host`, `Created_Date`)
					VALUES (?, ?, ?, ?, ?, ?, ?, ?, NOW())";
  	$sql = $dbh->prepare($statement);
  	$sql->execute(array($contact_person, $contact_number, $email, $company_name, implode(', ',$preferred_destination), $pax, $additional_comments, $_SERVER['HTTP_HOST']));
  }
  
}