<?php 
Class Hash extends Model
{
  function __construct($id='')
  {
		parent::__construct('HashID','T_Hash','getdbh');
		$this->rs['HashID']=0;
		$this->rs['UserID']='';
		$this->rs['Hash']='';
		$this->rs['Expiry_Date']='';

  if ($id)
    $this->retrieve($id);
  }
  
  function generate_hash($user_id, $ignore_pending=false)
  {
  	$dbh = getdbh();
  	
  	$hash=false;
  	
  	$statement = 'SELECT userid FROM T_User WHERE '.($ignore_pending?'':"status='pending' AND").' userid=?';
  	$sql = $dbh->prepare($statement);
  	$sql->execute(array($user_id));
  	$result = $sql->fetchAll();
  	if($result) {
  		$hash=md5(microtime().$user_id);
  		
  		$sql = $dbh->prepare('DELETE FROM T_Hash WHERE userid=?');
  		$sql->execute(array($user_id));
  		
  		$sql = $dbh->prepare('INSERT INTO T_Hash (userid,hash,expiry_date) VALUES (?,?,NOW()+INTERVAL 24 HOUR)');
  		$sql->execute(array($user_id,$hash));
  	}
		
  	return $hash;
  }
  
  function retrieve_hash($user_id, $ignore_delete=false)
  {
  	$dbh = getdbh();
  	
  	if(!$ignore_delete) {
  		$sql = $dbh->prepare('DELETE FROM T_Hash WHERE expiry_date<NOW()');
  		$sql->execute();
  	}
  	
  	$sql = $dbh->prepare('SELECT * FROM T_Hash WHERE userid=? LIMIT 1');
  	$sql->execute(array($user_id));
  	$result = $sql->fetchAll();
  	
  	return $result;
  }
  
  static function retrieve_user($hash, $ignore_delete=false)
  {
  	$dbh = getdbh();
  	
  	if(!$ignore_delete) {
  		$sql = $dbh->prepare('DELETE FROM T_Hash WHERE expiry_date<NOW()');
  		$sql->execute();
  	}
  	
  	$sql = $dbh->prepare('SELECT * FROM T_Hash WHERE hash=? LIMIT 1');
  	$sql->execute(array($hash));
  	$result = $sql->fetch();
  	
  	if(!$ignore_delete) {
  		$sql = $dbh->prepare('DELETE FROM T_Hash WHERE hash=? LIMIT 1');
  		$sql->execute(array($hash));
  	}
  	
  	return $result;
  }
}
