<?php
Class Article extends Model
{
  function __construct($id='')
  {
    parent::__construct('ArticleID','T_Article','getdbh');
      $this->rs['ArticleID']=0;
      $this->rs['UserID']='';
      $this->rs['CountryID']='';
      $this->rs['Title']='';
      $this->rs['Short_Description']='';
      $this->rs['Content']='';
      $this->rs['Expiry_Date']='';
      $this->rs['Status']='';
      $this->rs['Source']='';
      $this->rs['EventID']='';
      $this->rs['Exclusive']='';
      $this->rs['Remarks']='';
      $this->rs['Created_By_ID']='';
      $this->rs['Created_Date']='';
      $this->rs['Last_Updated_By_ID']='';
      $this->rs['Last_Updated_Date']='';

    if ($id)
      $this->retrieve($id);
  }
  
  function format_listview($article)
  {
    $html = '';
    $file = File::retrieve_random_file($article->get('ArticleID'),'article');
    if($file)
      $image_url = "<img width='90' height='90' style='margin: 10px auto auto 5px;' alt='".$article->get('Title')."' src='".TN_PATH."square/90x90/".$file->get('FileID').".".$file->get('Extension')."'/>";
    else
      $image_url = '';
    $html .= '
      <li>
        <a href="/m/travelguide/view/'.$article->get('ArticleID').'/'.url_title($article->get('Title')).'" style="white-space: normal;">
          '.$image_url.$article->get('Title').'
          <br/><div style="font-size: 12px; font-weight: normal; margin-top: 5px; white-space: normal;">'.$article->get('Short_Description').'</div>
        </a>
      </li>    
    ';    
    return $html;
  }
  
	function retrieve_all_articles($limit=null)
	{
    $article = new Article();
    $article_arr = $article->retrieve_many("(Expiry_Date>='".date("Y-m-d")."' OR Expiry_Date IS NULL) AND status='active' ORDER BY created_date DESC".($limit?" LIMIT ".$limit:""),'');
		return $article_arr;		
	}
  
	function retrieve_articles_by_tag($tag,$limit=null, $from=null)
	{
    $dbh = getdbh();
    $tag = filter_var($tag, FILTER_SANITIZE_STRING);
    if($limit && $from)
      $extend_sql = 'LIMIT '.$from.', '.$limit;
    elseif($limit && !$from)
      $extend_sql = 'LIMIT '.$limit;
    else
      $extend_sql = '';
    $statement = "SELECT T_Article.* FROM T_Article LEFT JOIN T_Article_Tag ON T_Article.ArticleID=T_Article_Tag.ArticleID LEFT JOIN T_Tag ON T_Tag.TagID=T_Article_Tag.TagID WHERE (T_Article.Expiry_Date>='".date('Y-m-d')."' OR T_Article.Expiry_Date IS NULL) AND T_Article.status='active' AND T_Tag.Name='".$tag."' ORDER BY T_Article.created_date DESC ".$extend_sql;
    
    //echo $statement;
    
    $sql = $dbh->prepare($statement);
    $sql->execute();
    $result = $sql->fetchAll();
    if($result)
      return $result;
    else
      return '';    
	}
}
?>