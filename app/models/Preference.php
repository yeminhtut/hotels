<?php
Class Preference extends Model
{
  function __construct($id='')
  {
		parent::__construct('PreferenceID','T_Preference','getdbh');
		$this->rs['PreferenceID']=0;
		$this->rs['Name']='';
		$this->rs['Type']='';
		$this->rs['Created_By_ID']='';
		$this->rs['Created_Date']='';
		$this->rs['Last_Updated_By_ID']='';
		$this->rs['Last_Updated_Date']='';

	  if ($id)
	    $this->retrieve($id);
  }
  
  function update_user_preferences($user_id,$preferences,$visit_location_others=false)
  {
  	$dbh = getdbh();  	
  	
  	$visit_location_others_id=self::retrieve_visit_location_others_id();
  	
  	$sql = $dbh->prepare('DELETE FROM T_User_Preference WHERE userid=?');
  	$sql->execute(array($user_id));
  	
  	$sql = $dbh->prepare('DELETE FROM T_User_Preference_Other WHERE userid=?');
  	$sql->execute(array($user_id));
  	
  	$sql_add="";
  	$values_add=array();
  	foreach($preferences as $key=>$val)
  	{
  		$sql_add.=",(?,?,?,NOW(),?,NOW())";
  		$values_add[]=$user_id;
  		$values_add[]=$val;
  		$values_add[]=$user_id;
  		$values_add[]=$user_id;
  		if($visit_location_others_id==$val)
  		{
  			$sql = $dbh->prepare('INSERT INTO T_User_Preference_Other (userid,preferenceid,name,created_by_id,created_date,last_updated_by_id,last_updated_date) 
  					VALUES (?,?,?,?,NOW(),?,NOW())');
  			$sql->execute(array($user_id,$visit_location_others_id,$visit_location_others,$user_id,$user_id));
  		}
  	}
  	
  	$statement="INSERT INTO T_User_Preference (userid,preferenceid,created_by_id,created_date,last_updated_by_id,last_updated_date) 
  			VALUES ".substr($sql_add,1,strlen($sql_add)-1);
  	
  	if(count($values_add)>0)
  	{
  		$sql = $dbh->prepare($statement);
  		$sql->execute($values_add);
  	}
  }
  
  function retrieve_visit_location_others_id()
  {
  	$dbh = getdbh();
  	
  	$sql = $dbh->prepare('SELECT preferenceid FROM T_Preference WHERE name="Others"');
  	$sql->execute();
  	$preferenceid = $sql->fetchColumn();
  	
  	return $preferenceid;
  }
  
  function retrieve_visit_location_others($type, $user_id)
  {
  	$dbh = getdbh();
  	
  	$visit_location_others_id=self::retrieve_visit_location_others_id();
  	
  	$sql = $dbh->prepare('SELECT name FROM T_User_Preference_Other WHERE userid=? AND preferenceid=?');
  	$sql->execute(array($user_id, $visit_location_others_id));
  	$name = $sql->fetchColumn();
  	
  }
  
  function retreive_user_preferences($type, $user_id) {
  	$dbh = getdbh();
  	
  	$sql = $dbh->prepare('SELECT * FROM T_Preference WHERE type=? ORDER BY preferenceid');
  	$sql->execute(array($type));
  	$result = $sql->fetchAll();
  	
  	$sql = $dbh->prepare('SELECT * FROM T_User_Preference WHERE userid=?');
  	$sql->execute(array($user_id));
  	$temp = $sql->fetchAll();
  	
  	$visit_location_others=self::retrieve_visit_location_others($type, $user_id);
  	
  	$preferences=array();
  	$out=array();
  	if($result)
  	{
  		if($temp)
  		{
  			foreach($temp as $row)
  			{
  				$preferences[]=$row['PreferenceID'];
  			}
  		}
  		
  		foreach($result as $key=>$val)
  		{
  			if(in_array($val['PreferenceID'],$preferences))
  			{
  				$out[$key]=$val;
  				$out[$key]['checked']='checked';
  				$out[$key]['selected']='selected';
  			}
  			else
  			{
  				$out[$key]=$val;
  				$out[$key]['checked']='';
  				$out[$key]['selected']='selected';
  			}
  		}
  	}
  	
  	return $out;
  }
}