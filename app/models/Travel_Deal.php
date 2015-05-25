<?php
Class Travel_Deal extends Model
{
  function __construct($id='')
  {
		parent::__construct('Travel_Deal_ID','T_Travel_Deals','getdbh');
		$this->rs['Travel_Deal_ID']=0;
		$this->rs['DealID']='';
		$this->rs['Title']='';
		$this->rs['Short_Description']='';
		$this->rs['Content']='';
		$this->rs['Company']='';
		$this->rs['Deal_Site']='';
		$this->rs['Url']='';
		$this->rs['EventID']='';
		$this->rs['Price']='';
		$this->rs['Original_Price']='';
		$this->rs['Sold']='';
		$this->rs['Image_Url']='';
		$this->rs['Type']='';
		$this->rs['Book_By_Date']='';
		$this->rs['Expiry_Date']='';
		$this->rs['Travel_Period']='';
		$this->rs['Contact_Number']='';
		$this->rs['Status']='';
		$this->rs['Draft']='';
		$this->rs['Featured']='';
		$this->rs['Featured_CS']='';
		$this->rs['Deal_Views']='';
		$this->rs['Clicks']='';
		$this->rs['Contact_Number_View']='';
		$this->rs['Created_By_ID']='';
		$this->rs['Created_Date']='';
		$this->rs['Last_Updated_By_ID']='';
		$this->rs['Last_Updated_Date']='';
		$this->rs['From']='';

  if ($id)
    $this->retrieve($id);
  }
  
	function retrieve_deals($filters='', $limit=null, $from=null)
	{
    $dbh = getdbh();
    $statement = 'SELECT T_Travel_Deals.* FROM T_Travel_Deals LEFT JOIN T_Country_City_Travel_Deals ON T_Country_City_Travel_Deals.Travel_Deal_ID=T_Travel_Deals.Travel_Deal_ID WHERE (T_Travel_Deals.Expiry_Date>NOW() OR T_Travel_Deals.Expiry_Date IS NULL) AND Status="active" AND T_Travel_Deals.Draft="n"  ';
    // Check filters here
    if($filters && is_array($filters))
    {
      if(isset($filters['continent']) && $filters['continent']!='') 
        $statement .= ' AND T_Country_City_Travel_Deals.CountryID IN (SELECT CountryID FROM T_Country WHERE Continent LIKE "%'.$filters['continent'].'%" )';
      if(isset($filters['country']) && $filters['country']!='') 
        $statement .= ' AND T_Country_City_Travel_Deals.CountryID=(SELECT CountryID FROM T_Country WHERE name LIKE "%'.$filters['country'].'%" )';
      if(isset($filters['city']) && $filters['city']!='') 
        $statement .= ' AND T_Country_City_Travel_Deals.Country_City_ID IN (SELECT Country_City_ID FROM T_Country_City WHERE Name LIKE "%'.$filters['city'].'%" )';
      if(isset($filters['type']) && $filters['type']!='') 
        $statement .= ' AND FIND_IN_SET("'.str_replace('-','_',str_replace(' ','-',$filters['type'])).'",T_Travel_Deals.Type)>0 ';
    }
    $statement .= ' GROUP BY T_Travel_Deals.Travel_Deal_ID ORDER BY T_Travel_Deals.featured ASC, T_Travel_Deals.`Created_Date` DESC ';    
    
    if($limit)
      $statement .= ' LIMIT '.($from?$from.', ':'').$limit;
    else
      $statement .= '';
    
    $sql = $dbh->prepare($statement);
    $sql->execute();
    $result = $sql->fetchAll(); 
    
		return $result;
	}

  static function makecanonicallink($id,$title) {
    $id=(int)$id;
    $url='http://tripzilla.sg/travel/deal/'.$id.'/'.makeslug($title);
    return $url;
  }
  
}