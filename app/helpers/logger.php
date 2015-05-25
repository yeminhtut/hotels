<?php
class logger {
  static function insert_log($type, $item_id=0, $keyword='', $search_type='posting')
	{
		if(! is_robot()) {
	    $dbh=getdbh();
	    
			$today=date('Y-m-d');

			$statement = 'INSERT INTO T_Log (date,type,itemid) VALUES (?,?,?)
					ON DUPLICATE KEY UPDATE `num`=`num`+1 ';
			$sql = $dbh->prepare($statement);
			$sql->execute(array($today, $type, $item_id));
			
			// Needs update
	    if($type=='search')
	    {
	    	if(!empty($dest) || !empty($budget) || !empty($when) || !empty($duration) || !empty($keyword))
	    	{
	    		$statement = 'SELECT * FROM T_Log_Search WHERE date=? AND search_keyword=? AND `type`=?';
	    		$sql = $dbh->prepare($statement);
	    		$sql->execute(array($today,$keyword,$search_type));
	    		$result = $sql->fetchAll();
	    		if($result) {
	    			$statement = 'UPDATE T_Log_Search SET num=num+1 WHERE date=? AND search_keyword=? AND `type`=?';
	    			$sql = $dbh->prepare($statement);
	    			$sql->execute(array($today,$keyword,$search_type));
	    		} else {
	    			$statement = 'INSERT INTO T_Log_Search (date,search_keyword,type) VALUES (?,?,?)';
	    			$sql = $dbh->prepare($statement);
	    			$sql->execute(array($today,$keyword,$search_type));
	    		}
	    	}
	    }
		}
	}
}