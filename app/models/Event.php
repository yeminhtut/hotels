<?php
Class Event extends Model
{
  function __construct($id='')
  {
		parent::__construct('EventID','T_Event','getdbh');
		$this->rs['EventID']=0;
		$this->rs['Name']='';
		$this->rs['From_Date']='';
		$this->rs['To_Date']='';
		$this->rs['Floor_Plan']='';
		$this->rs['FB_Photo_Album_ID']='';
		$this->rs['Menu']='';
		$this->rs['Status']='';
		$this->rs['Display']='';
		$this->rs['Created_By_ID']='';
		$this->rs['Created_Date']='';
		$this->rs['Last_Updated_By_ID']='';
		$this->rs['Last_Updated_Date']='';

  if ($id)
    $this->retrieve($id);
  }
  
	function retrieve_all_events()
	{
    $event = new Event();
    $events = $event->retrieve_many("Status='active' ORDER BY From_Date DESC",'');	
    
		return $events;
	}  
}
?>