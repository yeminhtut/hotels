<?php
function _shortlist($id, $check=FALSE) {
	$id=explode(",",$id);
	
	$shortlisted = array();
	$user=User::getUser();
	if($user)
	{
		$user_id = $user->getUID();
		$result=Shortlist::retrieve_shortlists_by_user_id($user_id);
		
		if(count($result))
		{
			foreach($result as $row)
			{
				$shortlisted[] = $row['PostingID'];
			}
		}
	} else {
		// get from sessions
		if(isset($_SESSION['shortlist'])) {
			$shortlisted=$_SESSION['shortlist'];
			$shortlisted=explode(",",$shortlisted);
		}
	}
	
	$out="";
	$json=array();
	foreach($id as $key=>$val)
	{
		if($check=='check') {
			if(in_array($val,$shortlisted))
			{
				$json[]=$val;
			}			
		} else {
			if($user)
			{
				$user_id = $user->getUID();
				Shortlist::toggle_shortlist_by_user_id_and_posting_id($user_id, $val);
			} else {
				if(in_array($val,$shortlisted))
				{
					foreach($shortlisted as $key=>$val2)
					{
						if($val2==$val)
							$shortlisted[$key]=null;
					}
				}
				else
				{
					$shortlisted[]=$val;
				}
				foreach($shortlisted as $row)
				{
					$row = intval($row);
					if($row!=null) {
						$out.=$row.",";
					}
				}
				$out=substr($out,0,strlen($out)-1);
				$_SESSION['shortlist'] = $out;
			}
		}
	}
	echo json_encode($json);
}
