<?php
class Amenity extends Model {
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
    parent::__construct('id','t_property_amenities');
    $this->rs['id']=0;
    $this->rs['zumata_id']='';
    $this->rs['amenities']='';
    $this->rs['created_dt']='0000-00-00 00:00:00'; 
    $this->rs['updated_dt'] = '0000-00-00 00:00:00';

    if ($id)
      $this->retrieve($id);
  }
}
