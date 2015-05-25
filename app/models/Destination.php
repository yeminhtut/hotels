<?php
class Destination extends Model {

  function __construct($id='') {
    parent::__construct('id','t_destination');
    $this->rs['id']=0;
    $this->rs['dest_id']=0;
    $this->rs['country']='';
    $this->rs['city_name']='';
    $this->rs['created_dt']='';

    if ($id)
      $this->retrieve($id);
  }
  
  
  
}