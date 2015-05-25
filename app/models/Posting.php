<?php
class Property extends Model {
  function __construct($id='') {
    parent::__construct('property_id','t_property','getdbh');
    $this->rs['property_id']=0;
    $this->rs['zumata_property_id']='';
    $this->rs['address']='';
    $this->rs['city']='';
    $this->rs['created_dt']=''; 
    
    if ($id)
      $this->retrieve($id);
  }
  
  // $result from retrieve_posting function
  function format_listview($result)
  {
    $html = '';

    foreach($result as $post_arr)
    {
      $posting = new Posting($post_arr['PostingID']);
      $company_result = Company::retrieve_company_by_posting_id($posting->get('PostingID'));
      $company = new Company($company_result['CompanyID']);
      $microsite=null;
      if($company_result['MicrositeStatus']=='active')
        $microsite = $company_result['Microsite'];
      $tour_type = Posting::get_tour_type($posting->get('Tour_Type'));

      $reference_type='posting';
      $reference_id=$posting->get('PostingID');

      if($posting->get('Image_Type')=='custom')
      {
        $file = new File();
        $file = File::retrieve_random_file($reference_id,$reference_type);

        if($file->exists())
          $image_url = TN_PATH.'square/90x90/'.$file->get('FileID').'.'.$file->get('Extension');
        else
          $image_url = '';
      }
      else
      {
          $country = Country::retrieve_random_posting_country_and_city($reference_id);
          $country_id = $country['CountryID'];
          $file = new File();
          $file = File::retrieve_random_file($country_id,'country');
          if($file->exists() && $file!='')
            $image_url = TN_PATH.'square/90x90/'.$file->get('FileID').'.'.$file->get('Extension');
          else
            $image_url = TN_PATH.'square/90x90/no_image.jpg';
      }
      $html .= '<li>
                  <a href="'.myUrl('m/tour/package/'.$posting->get('PostingID').'/'.url_title($posting->get('Title'))).'">
                    <img width="90" height="90" src="'.$image_url.'"/><span style="white-space: normal;">'.$posting->get('Title').'</span>
                    <div style="margin-top: 2px; font-weight: bold;"><span style="color: #F10;">'.$company->get('Name').'</span>
                    <br/><span>'.$tour_type.'</span></div>
                  </a>
                </li>';
    }    
    
    return $html;
  }
  
  function retrieve_featured($posting, $limit=FALSE, $destination=FALSE)
  {
  	$dbh = getdbh();  
    
    $statement = "SELECT T_Posting.*, T_Directory.Name as Company_Name
    		FROM T_Posting ";
    
    if($destination) {
    	$statement .= "LEFT JOIN T_Country_City_Posting ON T_Posting.postingid=T_Country_City_Posting.postingid ";
    }
    
    $statement .= "LEFT JOIN T_Directory ON T_Directory.CompanyID = T_Posting.CompanyID 
    		WHERE T_Posting.draft='n' 
    		AND T_Posting.status='active' 
    		AND T_Posting.Featured='y'  ";
    
    if($destination) {
    	$destination = str_replace('-',' ', $destination);
    	$temp=explode('|',$destination);
    	if(isset($temp[1])) {
    		if(is_numeric(trim($temp[1])))
    		{
    			$statement.=' AND Country_City_ID="'.trim($temp[1]).'"';
    		}
    		else
    		{
    			$statement.=' AND Country_City_ID IN (SELECT Country_City_ID FROM T_Country_City WHERE name = "'.trim($temp[1]).'" 
    					AND T_Country_City_Posting.countryid IN (SELECT countryid FROM T_Country WHERE name = "'.trim($temp[0]).'"))';
    		}
    	} else if(isset($temp[0])) {
    		if(is_numeric(trim($temp[0])))
    		{
    			$statement.=' AND T_Country_City_Posting.CountryID="'.trim($temp[0]).'"';
    		}
    		else
    		{
    			$statement.=' AND (Country_City_ID IN (SELECT Country_City_ID FROM T_Country_City WHERE name = "'.trim($temp[0]).'") 
    					OR T_Country_City_Posting.countryid IN (SELECT countryid FROM T_Country WHERE name = "'.trim($temp[0]).'"))';
    		}
    	}
    }
    
    $statement .= "ORDER BY RAND() ".($limit?' LIMIT '.$limit:'');
    
    $sql = $dbh->prepare($statement);
    $sql->execute();
    $result = $sql->fetchAll();
    if($result)
    	return $result;
    
    return FALSE;
  }

	function retrieve_departure_dates($posting_id)
	{
    $dbh = getdbh();     
    $statement = "SELECT * FROM T_Posting_Departure_Date WHERE postingid = ".$posting_id." ORDER BY Departure_Date ASC";
    $sql = $dbh->prepare($statement);
    $sql->execute();
    $result = $sql->fetchAll();    
    if($result)
      return $result;
    else
      return '';
	}
  
	function retrieve_max_days()
	{	
		$dbh = getdbh();
		$statement = "SELECT MAX(MaxDay) AS MaxDay FROM T_Posting WHERE T_Posting.Status='active' AND T_Posting.draft='n'";
		$sql = $dbh->prepare($statement);
		$sql->execute();
		$max_day = $sql->fetchColumn();
		if($max_day)
			return $max_day;
		else
			return 0;
	}
  
	function retrieve_min_days()
	{	
		$dbh = getdbh();
		$statement = "SELECT MIN(MinDay) AS MinDay FROM T_Posting WHERE T_Posting.Status='active' AND T_Posting.draft='n'";
		$sql = $dbh->prepare($statement);
		$sql->execute();
		$min_day = $sql->fetchColumn();
		if($min_day)
			return $min_day;
		else
			return 0;
	}
	
	function retrieve_max_price()
	{
		$dbh = getdbh();
		$statement = "SELECT MAX(Price) AS Price FROM T_Posting WHERE T_Posting.Status='active' AND T_Posting.draft='n'";
		$sql = $dbh->prepare($statement);
		$sql->execute();
		$max_price = $sql->fetchColumn();
		if($max_price)
			return $max_price;
		else
			return 0;
	}
	
	function retrieve_min_price()
	{
		$dbh = getdbh();
		$statement = "SELECT MIN(Price) AS Price FROM T_Posting WHERE T_Posting.Status='active' AND T_Posting.draft='n'";
		$sql = $dbh->prepare($statement);
		$sql->execute();
		$min_price = $sql->fetchColumn();
		if($min_price)
			return $min_price;
		else
			return 0;
	}
  
  function get_tour_type($type='')
  {
    if($type=='free_and_easy')
      return 'Free and Easy';
    elseif($type=='group_tour')
      return 'Group Tour';
    elseif($type=='land_tour')
      return 'Land Tour';
    elseif($type=='cruise')
      return 'Cruise';
    elseif($type=='luxurious')
      return 'Luxurious';
    else
      return null;
  }
  
function retrieve_posting($filters='', $limit='', $from='', $country_expert=FALSE, $country_expert_multiple=FALSE, $cruise_expert=FALSE, $count_query=FALSE, $company_query=FALSE)
  {
    $posting = new Posting();
    $sql_filter = '';
    $params=array();
    
    $destination_flag = FALSE;
    
    if($country_expert) {
    	$sql_filter .= ' AND T_Country_Expert.Status="active" ';
    }
    
    /*
     * TODO: need to display unique company per page
     * $country_expert_multiple
     */
    
    if($filters)
    {
      foreach($filters as $key=>$value)
      {
        if($value!='~')
        {				
          switch($key)
          {
            case 'keyword':
              if($value!='')
              {
              	switch(strtolower($value)) {
              		case 'united-states':
              		case 'united-states-of-america':	
              		case 'america':
              			// 184 - United States of America
              			$sql_filter.=' AND T_Country_City_Posting.CountryID = 184 ';
              			break;
              		case 'korea':
              			// 295	North Korea
              			// 90	South Korea
              			$sql_filter.=' AND T_Country_City_Posting.CountryID IN (90, 295) ';
              			break;
              		// continents
              		case 'africa':
              		case 'antarctica':
              		case 'asia':
              		case 'australia-and-pacific':
              		case 'europe':
              		case 'middle-east':
              		case 'north-america':
              		case 'oceania':
              		case 'south-america':
              			$sql_filter.=' AND T_Country_City_Posting.CountryID IN ( SELECT `CountryID` FROM `T_Country` WHERE `Continent`=? ) ';
              			$params[] = str_replace('-',' ',$value);
              			break;
              		default:
              			// TODO: add content, countries, cities, tags etc
              			$sql_filter.=' AND (T_Posting.Title LIKE ? OR T_Posting.Content LIKE ? )';
              			$params[] = "%".trim(str_replace('-',' ',$value))."%";
              			$params[] = "%".trim(str_replace('-',' ',$value))."%";
              	}
              	
              	$destination_flag = TRUE;
              }
            break;          
            case 'companies':
              if($value!='')
              {
              	if(count(explode(',',$value)) > 1) {
                	$sql_filter.=' AND T_Posting.CompanyID IN ('.$value.') ';
              	} else {
              		$sql_filter.=" AND T_Posting.CompanyID=? ";
              		$params[] = $value;
              	}
              }
            break;
            case 'sort_dir':
              if($value!='')
                $sort_dir=strtoupper($value);
            break;
            case 'sort':
              if($value!='')
              {
                $sort=$value;
              }
            break;
            case 'type':
              if($value!='all' && $value!='')
              {
              	if(count(explode(',',$value)) > 1) {
              		$sql_filter.=' AND T_Posting.Tour_Type IN ("'.implode('","',explode(',',$value)).'") ';
              	} else {
	                $sql_filter.=' AND T_Posting.Tour_Type=? ';	
	                $params[] = trim($value," '");
              	}
              }
            break;
            case 'muslim_tour':
              if($value=='y')
              {
                $sql_filter.=' AND T_Posting.Muslim_Tour="y"';	
              }
            break;
            case 'designated':
              $temp=explode(',',$value);
              foreach($temp as $row)
              {
                if(!is_numeric($row))
                  die();
              }					
              $sql_filter.=' AND T_Posting.PostingID IN ('.$value.')';
            break;					
            case 'max_days':
              if($value=='' && $value!=11)
                $value = $posting->retrieve_max_days();
              $sql_filter.=" AND (MaxDay<=? OR MaxDay IS NULL)"; 	
              $params[] = $value;
            break;
            case 'min_days':
              if($value=='' && $value!=1)
                $value = $posting->retrieve_min_days();
              $sql_filter.=" AND (MinDay>=? OR MinDay IS NULL)";     
              $params[] = $value;
            break;
            case 'destination':
              if(!empty($value)) {
                $temp=explode('|',$value);
                if(isset($temp[1])) {
                  if(is_numeric(trim($temp[1])))
                  {
                    $sql_filter.=' AND Country_City_ID=? ';
                    $params[] = trim($temp[1]);
                  }
                  else
                  {
                    $sql_filter.=' AND Country_City_ID IN (SELECT Country_City_ID FROM T_Country_City WHERE name = ? 
                    		AND T_Country_City_Posting.countryid IN (SELECT countryid FROM T_Country WHERE name = ? ))';
                    $params[] = trim(str_replace('-',' ',$temp[1]));
                    $params[] = trim(str_replace('-',' ',$temp[0]));
                  }
                } else if(isset($temp[0])) {
                  if(is_numeric(trim($temp[0])))
                  {
                    $sql_filter.=' AND T_Country_City_Posting.CountryID=? ';
                    $params[] = trim($temp[0]);
                  }
                  else
                  {
                  	switch(strtolower($temp[0])) {
                  		// continents
                  		case 'africa':
                  		case 'antarctica':
                  		case 'asia':
                  		case 'australia-and-pacific':
                  		case 'europe':
                  		case 'middle-east':
                  		case 'north-america':
                  		case 'oceania':
                  		case 'south-america':
                  			$sql_filter.=' AND T_Country_City_Posting.CountryID IN ( SELECT `CountryID` FROM `T_Country` WHERE `Continent`=? ) ';
                  			$params[] = str_replace('-',' ',$temp[0]);
                  			break;
                  		default:
                  			// TODO: add content, countries, cities, tags etc
                  			$sql_filter.=' AND (Country_City_ID IN (SELECT Country_City_ID FROM T_Country_City WHERE name = ? ) 
		                    	OR T_Country_City_Posting.countryid IN (SELECT countryid FROM T_Country WHERE name = ? ))';
		                    $params[] = trim(str_replace('-',' ',$temp[0]));
		                    $params[] = trim(str_replace('-',' ',$temp[0]));
                  	}
                  }
                }
                
                $destination_flag = TRUE;
              }
            break;
            case 'price':
              if($value!='')
              {
                $t_budget = explode('|',$value);
                if(isset($t_budget[0])) {
                  $sql_filter.=' AND price BETWEEN ? AND ? ';	
                  $params[] = $t_budget[0];
                  $params[] = $t_budget[1];
                } else {
                  $sql_filter.=' AND price<=? ';	
                  $params[] = $value;
                }
              }
            break;            
            case 'date':
              if($value!='')
              {
              	$values = explode(',',$value);
              	if(count($values) > 1) {
              		foreach($values as $value) {
              			$temp=explode('_',$value);
              			$values[0]=$temp[0].'-'.str_pad($temp[1],2,'0',STR_PAD_LEFT).'-'.str_pad($temp[2],2,'0',STR_PAD_LEFT);
              			$end1=end($values);
              			if(isset($temp[0]) && isset($temp[1]) && isset($temp[2]))
              				if(checkdate((int)$temp[1]+1,(int)$temp[2],(int)$temp[0]))
              				$values[1]=$temp[0].'-'.str_pad(((int)$temp[1]+1),2,'0',STR_PAD_LEFT).'-'.str_pad($temp[2],2,'0',STR_PAD_LEFT);
              			elseif(checkdate(1,(int)$temp[2],((int)$temp[0]+1)))
              			$values[1]=((int)$temp[0]+1).'-01-'.str_pad($temp[2],2,'0',STR_PAD_LEFT);
              			else
              				$values[1]=((int)$temp[0]+1).'-01-01';
              			$end2=end($values);
              			$sql_filter.=' AND (Travel_Period_To >= ? AND Travel_Period_From <= ? )';
              			$params[] = $values[0];
              			$params[] = $values[1];
              		}
              	} else {
	                $temp=explode('_',$value);
	                $values[0]=$temp[0].'-'.str_pad($temp[1],2,'0',STR_PAD_LEFT).'-'.str_pad($temp[2],2,'0',STR_PAD_LEFT);
	                $end1=end($values);
	                if(isset($temp[0]) && isset($temp[1]) && isset($temp[2]))
	                  if(checkdate((int)$temp[1]+1,(int)$temp[2],(int)$temp[0]))
	                    $values[1]=$temp[0].'-'.str_pad(((int)$temp[1]+1),2,'0',STR_PAD_LEFT).'-'.str_pad($temp[2],2,'0',STR_PAD_LEFT);
	                  elseif(checkdate(1,(int)$temp[2],((int)$temp[0]+1)))
	                    $values[1]=((int)$temp[0]+1).'-01-'.str_pad($temp[2],2,'0',STR_PAD_LEFT);
	                  else
	                    $values[1]=((int)$temp[0]+1).'-01-01';
	                $end2=end($values);
	                $sql_filter.=' AND (Travel_Period_To >= ? AND Travel_Period_From <= ? )';     
	                $params[] = $values[0];
	                $params[] = $values[1];
              	}
              }
            break;
            case 'tags':
              if($value!=null) {
                $with_tags_filter = true;
                $sql_filter.=' AND T_Posting_Tag.TagID = ? ';
                $params[] = $value;
              }
            break;
            case 'states':
              if($value!='') {
                $with_states_filter = true;
                $sql_filter.=' AND FIND_IN_SET(?,T_Directory.States)>0';
                $params[] = $value;
              }
            break;
            case 'companyid':
            	$sql_filter.=" AND T_Posting.companyid=? ";
            	$params[] = $value;
            break;
            case 'masiachinatours':
            	$sql_filter.=" AND T_Country_City_Posting.countryid IN (SELECT countryid FROM T_Country WHERE Name LIKE ? ) ";
            	$params[] = '%'.$value.'%';
            break;
            case 'masiacountries':
            	$sql_filter.=" AND T_Country_City_Posting.countryid IN (SELECT countryid FROM T_Country WHERE name LIKE ? ) ";
            	$params[] = '%'.$value.'%';
            break;
            case 'exclude':
            	if(is_array($value) && count($value)>0) {
            		$sql_filter.=" AND T_Posting.postingid NOT IN (".implode(',',$value).") ";
            	}
            break;
            default:
              $sql_filter.='';
          }
        }      
      }
    }
    
    $sql_filter.=' AND T_Posting.draft="n" ';  

    $dbh = getdbh();
    if($count_query) {
    	$statement = 'SELECT COUNT(DISTINCT T_Posting.PostingID) ';
    } elseif($company_query) {
    	$statement = 'SELECT T_Posting.CompanyID ';
    } else {
    	$statement = '
    		/* tp/models/Posting.php - retrieve_posting */	
    		SELECT T_Posting.PostingID,
  			T_Posting.Title,
  			T_Posting.Currency,
  			T_Posting.Price,
  			T_Posting.Tour_Type,
  			T_Posting.Image_Type,
				T_Posting.Content,
				T_Posting.MinDay,
				T_Posting.MaxDay,
				T_Posting.Travel_Period_From,
				T_Posting.Travel_Period_To,
				T_Posting.Valid_From,
				T_Posting.Valid_To,
    		T_Posting.CompanyID ';
    }
    
    if($country_expert) {
    	$statement .= ' ,T_Country_City_Posting.Country_City_ID,
    		T_Country_City_Posting.CountryID,
    		T_Country_Expert.Status AS Country_Expert_Status,
    		T_Country_Expert.CountryID AS Country_Expert_Country ';
    }
    
    if(isset($sort) && $sort=='days')
    {
      if($sort_dir=='ASC')
        $sort='ISNULL(MinDay),MinDay ASC, Maxday'; // place packages with NULL MinDay at the end
      else
        $sort='ISNULL(MaxDay),MaxDay DESC, MinDay'; // place packages with NULL MaxDay at the end
    }
    else if(isset($sort) && $sort=='price')
    {
      $sort='ISNULL(price), Converted_Price'; // place packages with NULL price at the end
      $statement .= ', (SELECT T_Posting.Price/T_Currency.Exchange_Rate from T_Currency WHERE T_Currency.From_Code="'.CURRENCY_CODE.'" AND T_Currency.To_Code=T_Posting.Currency) AS Converted_Price ';
    }
    else    
    {
    	//$sort='ISNULL(T_Posting.Consortium),ISNULL(T_Country_Expert.Country_Expert_ID), T_Directory.Featured ASC, T_Posting.Featured ASC, T_Posting.Sort_order';
      if($destination_flag) {
      	$sort='ISNULL(T_Posting.Consortium), T_Directory.Featured ASC, T_Posting.Sort_order';
      } else {
      	// $sort='T_Directory.Featured ASC, T_Posting.Featured ASC, T_Posting.Sort_order';
      	$sort='T_Directory.Featured ASC, T_Posting.Sort_order';
      }
      $sort_dir='';
    }    
    
    $statement .= 'FROM T_Posting 
      LEFT JOIN T_Directory ON T_Directory.CompanyID=T_Posting.CompanyID 
    	LEFT JOIN T_Country_City_Posting ON T_Posting.postingid=T_Country_City_Posting.postingid ';
    
    if($country_expert) {
	    $statement .= ' LEFT JOIN T_Country_Expert ';
	    if($country_expert_multiple && $cruise_expert) {
	    	$statement .= ' ON (T_Country_Expert.CompanyID=T_Posting.CompanyID OR T_Posting.CompanyID=153) ';
	    } elseif(!$country_expert_multiple && $cruise_expert) {
	    	$statement .= ' ON T_Posting.CompanyID=153 ';
	    } else {
	    	$statement .= ' ON T_Country_Expert.CompanyID=T_Posting.CompanyID ';
	    }
	    $statement .= ' AND T_Country_City_Posting.CountryID=T_Country_Expert.CountryID 
	    		AND T_Country_Expert.Status="active" 
	    		AND T_Country_Expert.Month="'.date('Y-m-d',mktime(0,0,0, date('m'), 01, date('Y'))).'" ';
    }
    
    $statement .= '  WHERE T_Posting.status="active" '.$sql_filter.' ';

    if(! $count_query) {
    	if($company_query) {
    		$statement .= ' GROUP BY T_Posting.CompanyID ';
    	} else {
		    $statement .= ' GROUP BY T_Posting.postingid ';
		
		    $statement .= ' ORDER BY '.$sort.' '.$sort_dir.', T_Posting.Valid_To DESC, T_Posting.Last_Updated_Date DESC'.($limit?' LIMIT '.($from?$from.', ':' ').$limit.'':$limit); 
    	}
    }
    
    $sql = $dbh->prepare($statement);
    $sql->execute($params);
    if($count_query) {
    	$result = $sql->fetchColumn();
    } else {
    	$result = $sql->fetchAll();  
    }

    return $result;
  } 
  
  function retrieve_itineraries($posting_id)
  {
  	$dbh = getdbh();
  	$sql = $dbh->prepare('SELECT Pdf_URL FROM T_Posting_Pdf WHERE postingid=?');
  	$sql->execute(array($posting_id));
  	$result = $sql->fetchAll();
  	if($result)
  		return $result;
  	else
  		return '';
  }
  
  function retrieve_country_package_count($country)
  {
  	$dbh = getdbh();
  	$sql = $dbh->prepare("SELECT COUNT(DISTINCT T_Posting.PostingID) AS count FROM T_Posting LEFT JOIN T_Country_City_Posting ON T_Posting.PostingID=T_Country_City_Posting.PostingID LEFT JOIN T_Country ON T_Country.CountryID=T_Country_City_Posting.CountryID WHERE T_Posting.status='active' AND T_Posting.draft='n' AND T_Country.name=? LIMIT 1");
  	$sql->execute(array($country));
  	$number_of_rows = $sql->fetchColumn();
  	
  	return $number_of_rows;
  }
  
  function retrieve_similir_packages($countries,$cities,$type,$exclude,$minday, $maxday, $company_id)
  {
  	$dbh = getdbh();
  	
  	$params = array(date('Y-m-d'),$type,$company_id,$exclude);
  	$cc_sql = '';
  	if(sizeof($cities)>0) {
  		$cc_sql .= 'AND T_Country_City_Posting.Country_City_ID IN ('.implode(',',$cities).')';
  	} elseif(sizeof($countries)>0) {
  		$cc_sql .= 'AND T_Country_City_Posting.CountryID IN ('.implode(',',$countries).')';
  	}
  
  	$day_sql = '';
  	if($minday > 0 && $maxday > 0)
  		$day_sql .= 'AND ( T_Posting.MinDay BETWEEN '.($minday-1).' AND '.($minday+1).' OR  T_Posting.MaxDay BETWEEN '.($maxday-1).' AND '.($maxday+1).' )';
  
  	$where_posting = '';
  	$user=User::getUser();
  	if($user) {
	  	$user_id=$user->get('UserID');
	  	$sql = $dbh->prepare('SELECT `Email` FROM T_User WHERE userid=? LIMIT 1');
	  	$sql->execute(array($user_id));
	  	$email = $sql->fetchColumn();
	  	if($email) {
	  		$where_posting = ' AND T_Posting.PostingID NOT IN (SELECT PostingID FROM `T_Enquiry_Track` WHERE `Email` = ?)';
	  		$params[] = $email;
	  	}
  	}
  	
  	// exclude fascinating holidays - id 120
  	// exclude AsiaTravel - id 1868
  	$sql = $dbh->prepare('SELECT T_Directory.Name,T_Posting.* FROM `T_Posting` 
  			LEFT JOIN T_Country_City_Posting ON T_Country_City_Posting.PostingID=T_Posting.PostingID 
  			LEFT JOIN T_Directory ON T_Directory.CompanyID=T_Posting.CompanyID 
  			LEFT JOIN T_Country_Expert ON T_Country_Expert.CompanyID=T_Posting.CompanyID 
  				AND T_Country_City_Posting.CountryID=T_Country_Expert.CountryID 
  				AND T_Country_Expert.Status="active" 
  				AND T_Country_Expert.Month="'.date('Y-m-d').'" 
  			WHERE T_Posting.`Status`="active" 
  				'.$cc_sql.' 
  				'.$day_sql.' 
  				AND T_Posting.Tour_type=? 
  				AND T_Posting.CompanyID!=? 
  				AND T_Posting.CompanyID NOT IN (1868, 120) 
  				AND T_Directory.Featured="y" 
  				AND T_Posting.PostingID!=? 
  				'.$where_posting.' 
  			GROUP BY T_Posting.CompanyID 
  			ORDER BY ISNULL(T_Country_Expert.Country_Expert_ID), 
  				T_Posting.Featured ASC, 
  				T_Directory.Featured ASC, 
  				T_Posting.Sort_order LIMIT 4');
  	$sql->execute($params);
  	$result = $sql->fetchAll();
  	
  	return $result;
  }

  static function makecanonicallink($id,$title) {
    $id=(int)$id;
    $url='http://tripzilla.sg/tour/package/'.$id.'/'.makeslug($title);
    return $url;
  }  
  
}