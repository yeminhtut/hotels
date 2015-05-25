<?php
Class Deal_Destination extends Model
{
  function __construct($id='')
  {
		parent::__construct('id','deal_destination','getdbh');
    $this->rs['id']=0;
		$this->rs['deal_id']=0;
		$this->rs['destination_id']=0;
		
    if($id)
      $this->retrieve($id);
  }

}