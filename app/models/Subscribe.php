<?php
class Subscribe extends Model
{
  function __construct($id='')
  {
		parent::__construct('SubscribeID','T_Subscribe','getdbh');
		$this->rs['SubscribeID']=0;
		$this->rs['Email']='';
		$this->rs['Source']='tp';
		$this->rs['Unsubscribe']='';
		$this->rs['Status']='';
		$this->rs['Created_By_ID']='';
		$this->rs['Created_Date']='';
		$this->rs['Last_Updated_By_ID']='';
		$this->rs['Last_Updated_Date']='';

  if ($id)
    $this->retrieve($id);
  }
  
  function add_subscriber($email,$source='enquiry')
  {
  	$dbh = getdbh();
  	$statement = 'SELECT * FROM T_Subscribe WHERE email LIKE ?';
  	$sql = $dbh->prepare($statement);
  	$sql->execute(array($email));
  	$result = $sql->fetch();
  	if($result) {
  		$statement = 'UPDATE `T_Subscribe` SET `Unsubscribe` = "n", last_updated_date=NOW() WHERE `email` = ?';
  		$sql = $dbh->prepare($statement);
  		$sql->execute(array($email));
  	} else {
  		$statement = 'INSERT INTO T_Subscribe (email,source,created_date,last_updated_date) VALUES (?,?,NOW(),NOW())';
  		$sql = $dbh->prepare($statement);
  		$sql->execute(array($email,$source));
  	}
  }
  
  function remove_subscriber($email)
  {
  	$dbh = getdbh();
  	
  	$statement = 'UPDATE `T_Subscribe` SET `Unsubscribe` = "y", last_updated_date=NOW() WHERE `email` = ?';
  	$sql = $dbh->prepare($statement);
  	$sql->execute(array($email));
  }
  
  function retrieve_subscriber($email)
  {
  	$dbh = getdbh();
  	 
  	$statement = 'SELECT * FROM T_Subscribe WHERE email=? AND `Unsubscribe` = "n" LIMIT 1';
  	$sql = $dbh->prepare($statement);
  	$sql->execute(array($email));
  	
  	return $sql->fetch();
  }
}
