<?php
Class Enquiry extends Model
{
	function __construct($id='')
	{
		parent::__construct('id','enquiry');
		$this->rs['id']=0;
		$this->rs['name']='';
		$this->rs['email']='';
		$this->rs['contact_no']='';
		$this->rs['when_callback']='';
		$this->rs['adults']='';
		$this->rs['children']='';
		$this->rs['infants']='';
		$this->rs['remarks']='';
		$this->rs['cid']='';
		$this->rs['packageid']='';
    $this->rs['type']='';
    $this->rs['dealid']='';
		$this->rs['status']='';
		$this->rs['flags']='';
		$this->rs['source']='';
		$this->rs['created_dt']='';

		if ($id)
			$this->retrieve($id);
	}
  
  function import_enquiry($obj){
    $this->set( 'id' , $obj['Enquiry_Track_ID']);
    $this->set( 'name' , $obj['Name']);
    $this->set( 'email' , $obj['Email']);
    $this->set( 'contact_no' , $obj['Contact_No']);
    $this->set( 'when_callback' , $obj['Preferred_Callback_Time']);
    $this->set( 'adults' , $obj['Adults']);
    $this->set( 'children' , $obj['Children']);
    $this->set( 'infants' , $obj['Infants']);
    $this->set( 'remarks' , $obj['Remarks']);
    $this->set( 'cid' , $obj['CompanyID']);
    $this->set( 'packageid' , $obj['PostingID']);
    $this->set( 'type' , $obj['Type']);
    $this->set( 'dealid' , $obj['Created_By_ID']);
    $this->set( 'status' , $obj['Status']);
    
    if($obj['Mobile']=='y')
      $this->set( 'flags' , 'mobile');
    else if($obj['Bulk_sent']=='y')
      $this->set( 'flags' , 'bulk');
    
    $this->set( 'source' , $obj['Source']);
    $this->set( 'created_dt' , $obj['Created_Date']);

    return $this;
  }
  
  function add_entry($name, $email, $contact_no, $when_callback, $adults, $children, $infants, $remarks, $cid, $packageid, $type, $dealid, $status, $flags, $source)
	{
		$dbh = getdbh();
    
		$statement = 'INSERT INTO enquiry 
				(name, email, contact_no, when_callback, adults, children, infants, remarks, cid, packageid, type, dealid, status, flags, source, created_dt) 
				VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,NOW())';
		$sql = $dbh->prepare($statement);
		$sql->execute(array($name, $email, $contact_no, $when_callback, $adults, $children, $infants, $remarks, $cid, $packageid, $type, $dealid, $status, $flags, $source));
	}
}