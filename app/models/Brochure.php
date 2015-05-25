<?php
Class Brochure extends Model
{
  function __construct($id='')
  {
		parent::__construct('BrochureID','T_Brochure','getdbh');
		$this->rs['BrochureID']=0;
		$this->rs['EventID']='';
		$this->rs['Type']='';
		$this->rs['ReferenceID']='';
		$this->rs['Title']='';
		$this->rs['Short_Description']='';
		$this->rs['Status']='';
		$this->rs['Remarks']='';
		$this->rs['Created_By_ID']='';
		$this->rs['Created_Date']='';
		$this->rs['Last_Updated_By_ID']='';
		$this->rs['Last_Updated_Date']='';

  if ($id)
    $this->retrieve($id);
  }

	function retrieve_brochures_by_company_and_eventid($company_id,$event_id,$limit=null)
	{
    $dbh = getdbh();
    $statement = "SELECT T_Brochure.*,T_Event.Name AS Event FROM T_Brochure LEFT JOIN T_Event ON T_Event.EventID=T_Brochure.EventID WHERE T_Brochure.status='active' AND FIND_IN_SET('agency',T_Brochure.Type)>0 AND T_Brochure.ReferenceID='".$company_id."' AND T_Brochure.EventID='".$event_id."' ORDER BY T_Brochure.created_date";
    if($limit)
      $statement .= ' LIMIT '.$limit;
    else
      $statement .= '';

    $sql = $dbh->prepare($statement);
    $sql->execute();
    $result = $sql->fetchAll();
    if($result)
      return $result;
    else
      return '';
	}
}
?>