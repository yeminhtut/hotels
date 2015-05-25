<?php
Class Package_Destination extends Model
{
  function __construct($id='')
  {
		parent::__construct('id','package_destination','getdbh');
    $this->rs['id']=0;
		$this->rs['package_id']=0;
		$this->rs['destination_id']=0;
		
    if($id)
      $this->retrieve($id);
  }
}