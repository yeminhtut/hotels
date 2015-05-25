<?php
Class Country extends Model
{
  function __construct($id='')
  {
    parent::__construct('CountryID','T_Country','getdbh');
    $this->rs['CountryID']=0;
    $this->rs['Name']='';
    $this->rs['Formal_Name']='';
    $this->rs['Type']='';
    $this->rs['Sub_Type']='';
    $this->rs['Sovereignty']='';
    $this->rs['Capital']='';
    $this->rs['ISO_4217_Currency_Code']='';
    $this->rs['ISO_4217_Currency_Name']='';
    $this->rs['ITU_T_Telephone_Code']='';
    $this->rs['ISO_3166_1_2_Letter_Code']='';
    $this->rs['ISO_3166_1_3_Letter_Code']='';
    $this->rs['ISO_3166_1_Number']='';
    $this->rs['IANA_Country_Code_TLD']='';
    $this->rs['Continent']='';
    $this->rs['Created_By_ID']='';
    $this->rs['Created_Date']='';
    $this->rs['Last_Updated_By_ID']='';
    $this->rs['Last_Updated_Date']='';

    if ($id)
      $this->retrieve($id);
  }

  function retrieve_random_posting_country_and_city($posting_id)
  {
    $dbh = getdbh();
    $statement = "SELECT T_Country_City_Posting.*, T_Country.Name AS Country_Name, T_Country_City.Name AS City_Name FROM T_Country_City_Posting LEFT JOIN T_Country ON T_Country_City_Posting.countryid=T_Country.countryid LEFT JOIN T_Country_City ON T_Country_City.countryid=T_Country.countryid WHERE postingid=".$posting_id." GROUP BY T_Country_City_Posting.country_city_posting_id ORDER BY RAND() LIMIT 1";
    $sql = $dbh->prepare($statement);
    $sql->execute();
    $result = $sql->fetch(); 
    return $result;
  }
  
  function retrieve_deals_count_by_country()
  {
    $dbh = getdbh();
    $statement = '
    SELECT c.countryid, c.name, count(distinct(td.travel_deal_id)) as total FROM `T_Country_City_Travel_Deals` tcd, `T_Travel_Deals` td, `T_Country` c WHERE  
    td.Travel_Deal_ID = tcd.Travel_Deal_ID AND 
    tcd.countryid = c.countryid AND 
    (td.Expiry_Date>NOW() OR td.Expiry_Date IS NULL) AND 
    td.Status="active" AND 
    td.Draft="n" AND 
    c.name!=c.continent 
    group by name 
    order by name asc, total desc    
    ';
    $sql = $dbh->prepare($statement);
    $sql->execute();
    $result = $sql->fetchAll(); 
    return $result;
  }  
  
  function retrieve_deals_count_by_continent()
  {
    $dbh = getdbh();
    $statement = '
    SELECT c.countryid, c.continent, count(distinct(td.travel_deal_id)) as total FROM `T_Country_City_Travel_Deals` tcd, `T_Travel_Deals` td, `T_Country` c WHERE  
    td.Travel_Deal_ID = tcd.Travel_Deal_ID AND 
    tcd.countryid = c.countryid AND 
    (td.Expiry_Date>NOW() OR td.Expiry_Date IS NULL) AND 
    td.Status="active" AND 
    td.Draft="n" 
    group by continent
    order by continent asc, total desc
    ';
    $sql = $dbh->prepare($statement);
    $sql->execute();
    $result = $sql->fetchAll(); 
    return $result;
  }    
  
  function retrieve_country_by_name($name)
  {
    $country = new Country();
    $country = $country->retrieve_one('Name=? AND Name!=Continent',$name);
    return $country;
  }  
  
  function retrieve_country_by_name_c($name)
  {
  	$country = new Country();
  	$country = $country->retrieve_one('Name=?',$name);
  	return $country;
  }
  
  function retrieve_country_by_id($country_id)
  {
  	$country = new Country();
  	$country = $country->retrieve_one('CountryID=?',$country_id);
  	return $country;
  }
  
  function get_country_name() {
    return $this->get('Name');
  }  
  
  function retrieve_all_countries_with_postings($limit=null)
	{
		$dbh = getdbh();
		$statement = "SELECT T_Country.*, T_Posting.PostingID, COUNT(DISTINCT T_Posting.postingid) AS PostCount 
				FROM T_Country 
				LEFT JOIN T_Country_City_Posting ON T_Country_City_Posting.countryid=T_Country.countryid 
				LEFT JOIN T_Posting ON T_Posting.postingid=T_Country_City_Posting.postingid 
				LEFT JOIN T_Directory ON T_Posting.companyid=T_Directory.CompanyID 
				WHERE T_Posting.draft='n' AND T_Posting.Status='active'
				GROUP BY T_Country.Name ORDER BY T_Country.Name";
		if($limit)
			$statement .= " LIMIT $limit";
		$sql = $dbh->prepare($statement);
		$sql->execute();
		$result = $sql->fetchAll();
		return $result;
	}
	
	function retrieve_all_continents_with_postings($limit=null)
	{
		$dbh = getdbh();
		$statement = "SELECT T_Country.*, T_Posting.PostingID, COUNT(DISTINCT T_Posting.postingid) AS PostCount
				FROM T_Country
				LEFT JOIN T_Country_City_Posting ON T_Country_City_Posting.countryid=T_Country.countryid
				LEFT JOIN T_Posting ON T_Posting.postingid=T_Country_City_Posting.postingid
				LEFT JOIN T_Directory ON T_Posting.companyid=T_Directory.CompanyID
				WHERE T_Posting.draft='n' AND T_Posting.Status='active'
				GROUP BY T_Country.Continent ORDER BY T_Country.Continent";
		if($limit)
			$statement .= " LIMIT $limit";
		$sql = $dbh->prepare($statement);
		$sql->execute();
		$result = $sql->fetchAll();
		return $result;
	}
	
	function retrieve_all_countries_with_postings_by_continent($continent, $limit=null)
	{
		$dbh = getdbh();
		$statement = "SELECT T_Country.*, T_Posting.PostingID, COUNT(DISTINCT T_Posting.postingid) AS PostCount
				FROM T_Country
				LEFT JOIN T_Country_City_Posting ON T_Country_City_Posting.countryid=T_Country.countryid
				LEFT JOIN T_Posting ON T_Posting.postingid=T_Country_City_Posting.postingid
				LEFT JOIN T_Directory ON T_Posting.companyid=T_Directory.CompanyID
				WHERE T_Posting.draft='n' 
					AND T_Posting.Status='active'
					AND T_Country.Continent = ?
				GROUP BY T_Country.Name ORDER BY T_Country.Name";
		if($limit)
			$statement .= " LIMIT $limit";
		$sql = $dbh->prepare($statement);
		$sql->execute(array($continent));
		$result = $sql->fetchAll();
		return $result;
	}
	
	function retrieve_all_cities_with_postings_by_country_id($country_id, $limit=null)
	{
		$dbh = getdbh();
		$statement = "SELECT T_Country_City.*, T_Posting.PostingID, COUNT(DISTINCT T_Posting.postingid) AS PostCount
			FROM T_Country_City
			LEFT JOIN T_Country_City_Posting ON T_Country_City_Posting.Country_City_ID=T_Country_City.Country_City_ID
			LEFT JOIN T_Posting ON T_Posting.postingid=T_Country_City_Posting.postingid
			LEFT JOIN T_Directory ON T_Posting.companyid=T_Directory.CompanyID
			WHERE T_Posting.draft='n'
			  AND T_Posting.Status='active'
			  AND T_Country_City_Posting.CountryID = ?
			GROUP BY T_Country_City.Name ORDER BY T_Country_City.Name";
		if($limit)
			$statement .= " LIMIT $limit";
		$sql = $dbh->prepare($statement);
		$sql->execute(array($country_id));
		$result = $sql->fetchAll();
		return $result;
	}
	
	function retrieve_posting_countries_and_cities($posting_id)
	{
		$dbh = getdbh();
		$statement = "SELECT * FROM T_Country_City_Posting WHERE postingid=? ORDER BY country_city_posting_id";
		$sql = $dbh->prepare($statement);
		$sql->execute(array($posting_id));
		$result = $sql->fetchAll();
		return $result;
	}
	
	function retrieve_country_expert($country_id)
	{
		$dbh = getdbh();
		$statement = 'SELECT T_Country_Expert.`CountryID`, T_Country_Expert.`CompanyID`, T_Directory.Name
				FROM `T_Country_Expert`
				LEFT JOIN T_Directory ON T_Directory.CompanyID = T_Country_Expert.CompanyID
				WHERE T_Country_Expert.CountryID=?
    		AND T_Country_Expert.Status="active"
    		AND T_Country_Expert.Month="'.date('Y-m-d',mktime(0,0,0, date('m'), 01, date('Y'))).'"
    		ORDER BY RAND() ';
		$sql = $dbh->prepare($statement);
		$sql->execute(array($country_id));
		$result = $sql->fetchAll();
		return $result;
	}
  
}
