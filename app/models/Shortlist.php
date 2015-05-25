<?php 
class Shortlist extends Model
{
  function __construct($id='')
  {
		parent::__construct('ShortlistID','T_Shortlist','getdbh');
		$this->rs['ShortlistID']=0;
		$this->rs['PostingID']='';
		$this->rs['UserID']='';
		$this->rs['Created_By_ID']='';
		$this->rs['Created_Date']='';
		$this->rs['Last_Updated_By_ID']='';
		$this->rs['Last_Updated_Date']='';

	  if ($id)
	    $this->retrieve($id);
  }
  
  static function retrieve_shortlists_by_user_id($user_id, $limit=null)
	{
		$dbh = getdbh();
		$statement = "SELECT PostingID FROM T_Shortlist WHERE userid = ?".($limit?' LIMIT '.$limit:'');
		$sql = $dbh->prepare($statement);
		$sql->execute(array($user_id));
		$result = $sql->fetchAll();
		
		return $result;
	}
	
	static function toggle_shortlist_by_user_id_and_posting_id($user_id, $posting_id)
	{
		$dbh = getdbh();
		$statement = 'SELECT * FROM T_Shortlist WHERE postingid = ? AND userid = ?';
		$sql = $dbh->prepare($statement);
		$sql->execute(array($posting_id,$user_id));
		$result = $sql->fetchAll();
		if($result) {
			$statement = 'DELETE FROM T_Shortlist WHERE postingid = ? AND userid = ?';
			$sql = $dbh->prepare($statement);
			$sql->execute(array($posting_id,$user_id));
		} else {
			$statement = 'INSERT INTO T_Shortlist (postingid,userid,created_by_id,created_date,last_updated_by_id,last_updated_date) VALUES (?,?,?,NOW(),?,NOW())';
			$sql = $dbh->prepare($statement);
			$sql->execute(array($posting_id,$user_id,$user_id,$user_id));
		}
	}
	
	static function add_shortlist_by_user_id_and_posting_id($user_id, $posting_id)
	{
		$dbh = getdbh();
		$statement = 'SELECT * FROM T_Shortlist WHERE postingid = ? AND userid = ?';
		$sql = $dbh->prepare($statement);
		$sql->execute(array($posting_id,$user_id));
		$result = $sql->fetchAll();
		if($result) {
			// exist
		} else {
			$statement = 'INSERT INTO T_Shortlist (postingid,userid,created_by_id,created_date,last_updated_by_id,last_updated_date) VALUES (?,?,?,NOW(),?,NOW())';
			$sql = $dbh->prepare($statement);
			$sql->execute(array($posting_id,$user_id,$user_id,$user_id));
		}
	}
	
	function is_shortlisted($posting_id) {
		$dbh = getdbh();
	
		$user=User::getUser();
		if($user)
		{
			$user_id = $user->getUID();
			$statement = 'SELECT * FROM T_Shortlist WHERE postingid = ? AND userid = ?';
			$sql = $dbh->prepare($statement);
			$sql->execute(array($posting_id,$user_id));
			$result = $sql->fetch();
			if($result)
				return TRUE;
		} else {
			// get from sessions
			if(isset($_SESSION['shortlist'])) {
				$shortlisted=$_SESSION['shortlist'];
				$shortlisted=explode(",",$shortlisted);
				if(in_array($posting_id,$shortlisted))
					return TRUE;
			}
		}
	
		return FALSE;
	}
}
