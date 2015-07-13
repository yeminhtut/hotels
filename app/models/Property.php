<?php
class Property extends Model {
  /*
  Table of contents:
  - function getpermalink
  - static function makepermalink
  - function import_package
  - function package_info
  - static function search
  - static function retrieve_company_by_package
  */
  
  function __construct($id='') {
    parent::__construct('property_id','t_property');
    $this->rs['property_id']=0;
    $this->rs['zumata_property_id']='';
    $this->rs['address']='';
    $this->rs['property_name']='';
    $this->rs['city']='';
    $this->rs['description']='';
    $this->rs['image_details']='';
    $this->rs['lat']='';
    $this->rs['lng']='';
    $this->rs['created_dt']='0000-00-00 00:00:00'; 
    $this->rs['last_updated_date'] = '0000-00-00 00:00:00';

    if ($id)
      $this->retrieve($id);
  }
}
