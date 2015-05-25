<?php
Class Country_City extends Model
{
  function __construct($id='')
  {
		parent::__construct('Country_City_ID','T_Country_City','getdbh');
		$this->rs['Country_City_ID']=0;
		$this->rs['CountryID']='';
		$this->rs['Name']='';
		$this->rs['Created_By_ID']='';
		$this->rs['Created_Date']='';
		$this->rs['Last_Updated_By_ID']='';
		$this->rs['Last_Updated_Date']='';

  if ($id)
    $this->retrieve($id);
  }

  function retrieve_by_name($name)
  {
    $cc = new Country_City();
    $cc = $cc->retrieve_one('Name=?',$name);
    return $cc;
  }

  function retrieve_deals_count_by_city()
  {
    $dbh = getdbh();
    $statement = '
    SELECT cc.country_City_ID, cc.name, count(distinct(td.travel_deal_id)) as total FROM `T_Country_City_Travel_Deals` tcd, `T_Travel_Deals` td, `T_Country` c, `T_Country_City` cc WHERE
    td.Travel_Deal_ID = tcd.Travel_Deal_ID AND
    tcd.countryid = c.countryid AND
    tcd.Country_City_ID = cc.Country_City_ID AND
    (td.Expiry_Date>NOW() OR td.Expiry_Date IS NULL) AND
    td.Status="active" AND
    td.Draft="n"
    group by cc.name
    order by total desc
    ';
    $sql = $dbh->prepare($statement);
    $sql->execute();
    $result = $sql->fetchAll();
    return $result;
  }

}
?>