<?php
class Microsite extends Model {

  function __construct($id='') {
    parent::__construct('id','microsite');
    $this->rs['id'] = 0;
    $this->rs['cid'] = 0;
    $this->rs['folder_name'] = '';
    $this->rs['description'] = '';
    $this->rs['menu'] = '';
    $this->rs['status'] = '';

    if ($id)
      $this->retrieve($id);
  }
  
  function import_microsite($obj){
    $this->set( 'id' , $obj['MicrositeID']);
    $this->set( 'cid' , $obj['CompanyID']);
    $this->set( 'folder_name' , $obj['Subdomain']);
    $this->set( 'description' , $obj['Description']);
    $this->set( 'menu' , $obj['Menu']);
    $this->set( 'status' , $obj['Status']);
    $this->set( 'created_by' , $obj['Created_By_ID']);
    $this->set( 'created_date' , $obj['Created_Date']);
    $this->set( 'last_updated_by' , $obj['Last_Updated_By_ID']);
    $this->set( 'last_updated_date' , $obj['Last_Updated_Date']);

    return $this;
  }
}