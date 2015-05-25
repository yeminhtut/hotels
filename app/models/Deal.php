<?php
Class Deal extends Model
{
  function __construct($id='') {
    parent::__construct('id','deal');
    $this->rs['id'] = '0';
    $this->rs['title'] = '';
    $this->rs['cid'] = '0';
    $this->rs['eid'] = '0';
    $this->rs['fileid'] = '0';
    $this->rs['excerpt'] = '';
    $this->rs['description'] = '';
    $this->rs['url'] = '';
    $this->rs['option'] = '';
    $this->rs['phone'] = '';
    $this->rs['email'] = '';
    $this->rs['price'] = '';
    $this->rs['original_price'] = '';
    $this->rs['price_usd'] = '';
    $this->rs['image_url'] = '';
    $this->rs['type'] = '';
    $this->rs['theme'] = '';
    $this->rs['book_by'] = '';
    $this->rs['travel_from'] = '';
    $this->rs['travel_to'] = '';
    $this->rs['expire_on'] = '';
    $this->rs['status'] = '';
    $this->rs['flags'] = '';
    $this->rs['created_by'] = 0;
    $this->rs['created_date'] = '0000-00-00 00:00:00';
    $this->rs['last_updated_by'] = 0;
    $this->rs['last_updated_date'] = '0000-00-00 00:00:00';

    if ($id)
      $this->retrieve($id);
  }

    function getpermalink($fullurl=true) {
    return Deal::makepermalink($this->get('id'),$this->get('title'),$fullurl);
  }
  
  static function makepermalink($id,$title,$fullurl=true) {
    $id=(int)$id;
    $url='/travel/deal/'.$id.'/'.makeslug($title);
    if ($fullurl)
      $url=WEB_DOMAIN.$url;
    return $url;
  }
  /*This one has problem*/
  function retrieve_all_cities($limit=null){
    $dbh = getdbh();
    $statement = 'SELECT T_Country_City.Name AS CityName, T_Country.Name as CountryName, COUNT(DISTINCT T_Travel_Deals.Travel_Deal_ID) AS DealsCount 
        FROM `T_Travel_Deals` 
        LEFT JOIN T_Country_City_Travel_Deals ON T_Country_City_Travel_Deals.Travel_Deal_ID=T_Travel_Deals.Travel_Deal_ID 
        LEFT JOIN T_Country_City ON T_Country_City.Country_City_ID=T_Country_City_Travel_Deals.Country_City_ID 
        LEFT JOIN T_Country ON T_Country.CountryID=T_Country_City.CountryID 
        LEFT JOIN T_Directory ON T_Directory.CompanyID=T_Travel_Deals.CompanyID 
        WHERE T_Travel_Deals.Expiry_Date>NOW() 
        AND T_Travel_Deals.Status="active" 
        AND T_Travel_Deals.Draft="n"  
        AND T_Country_City.Name IS NOT NULL 
        AND T_Travel_Deals.Type IN ("package","daily_deal") 
        AND T_Directory.Directory_Category_ID=1 
        GROUP BY T_Country_City.Name 
        ORDER BY COUNT(DISTINCT T_Travel_Deals.Travel_Deal_ID) DESC';
    if($limit)
      $statement .= " LIMIT $limit";
    $sql = $dbh->prepare($statement);
    $sql->execute();
    $result = $sql->fetchAll();
    return $result;
  }

function get_all_deal_destinations($type){
  $dbh = getdbh();
  if ($type == 'global') {
    $sql_filter = ' AND destination.parentid = 0';
  }
  if ($type == 'country') {
    $sql_filter = ' AND destination.type = \'country\'';
  }
  if ($type == 'city') {
    $sql_filter = ' AND destination.type = \'city\'';
  }
     
    $statement="SELECT DISTINCT deal_destination.destination_id,destination.name,destination.parentid,COUNT(DISTINCT deal.id) AS dealscount
                FROM deal_destination 
                LEFT JOIN deal ON deal.id = deal_destination.deal_id 
                LEFT JOIN destination ON destination.id = deal_destination.destination_id
                WHERE deal.expire_on > NOW()"; 
    $statement .= $sql_filter;
    $statement .= ' GROUP BY destination.name ORDER BY COUNT(DISTINCT deal.id) DESC';     
    $sql = $dbh->prepare($statement);
    $sql->execute();
    $result=$sql->fetchAll();    
    return $result;
}


  static function search($params=false, $n=false, $limit=false, $count_query=FALSE, $randomize=false){
    $dbh = getdbh();
    $sql_filter='';
    $exc_params=array();
    $join_table=false;
    $join_new_table=false;
    $status="deal.status='active' AND (NOT(FIND_IN_SET('draft', deal.flags))) AND deal.expire_on > '".date('Y-m-d H:i:s',(floor((strtotime('now') + 900) / 900) * 900))."' ";
    
    //get the total records or retrieve the records
    if($count_query)
      $statement="SELECT COUNT(DISTINCT deal.id) FROM deal ";
    else
      $statement="SELECT GROUP_CONCAT(dl.id) as ids FROM (SELECT DISTINCT deal.id FROM deal ";
    
    //check the parameters that has been passed from Controllers
    if($params){
      foreach($params as $key=>$value){
        if($value!='~'){
          switch ($key) {
            case "dom_int":
              if($value!=''){
                if($value=="domestic"){
                  $join_table=true;
                  $sql_filter.="AND country_city_deal.country_id=105 ";
                }else if($value=="international"){
                  $join_table=true;
                  $sql_filter.="AND country_city_deal.country_id!=105 ";
                }
              }
            break;
            case "dom_int_new":
              if($value!=''){
                $join_new_table=true;
                if($value=="domestic")
                  $sql_filter.="AND deal_destination.deal_id IN (SELECT deal_id FROM deal_destination WHERE destination_id=23) ";
                else if($value=="international")
                  $sql_filter.="AND deal_destination.deal_id NOT IN (SELECT deal_id FROM deal_destination WHERE destination_id=23) ";
              }
            break;
            case "destination":
              if($value!=''){
                $join_new_table=true;
                $sql_filter.="AND deal_destination.destination_id IN ($value) ";
              }
            break;
            case "continent":
              if($value!=''){
                $join_table=true;
                $sql_filter.="AND country_city_deal.country_id IN ($value) ";
              }
            break;
            case "country":
              if($value!=''){
                $join_table=true;
                $sql_filter.="AND country_city_deal.country_id IN ($value) ";
              }
            break;
            case "city":
              if($value!=''){
                $join_table=true;
                $sql_filter.="AND country_city_deal.city_id IN ($value) ";
              }
            break;
            case "departure":
              if($value!=''){
                $values = explode(',',$value);
                
                $temp=explode('_',$value);
                $departure_date=$temp[0].'-'.str_pad($temp[1],2,'0',STR_PAD_LEFT).'-'.str_pad($temp[2],2,'0',STR_PAD_LEFT);
                $sql_filter.='AND (deal.travel_to >=? AND deal.travel_from <=?) ';
                $exc_params[] = $departure_date;
                $exc_params[] = $departure_date;
              }
            break;
            case "featured":
              if($value!='' && $value=='y'){
                $sql_filter.="AND (? IN (deal.flags)) ";
                $exc_params[]='featured';
              }else if($value!='' && $value=='n'){
                $sql_filter.="AND (? NOT IN (deal.flags)) ";
                $exc_params[]='featured';
              }
            break;
            case "except_daily_deal":
              if($value!=''){
                $sql_filter.="AND FIND_IN_SET(?, deal.type)=0 ";
                $exc_params[]='daily_deal';
              }
            break;
            case "type":
              if($value!=''){
                if(count(explode(',',$value)) > 1) {
                  $deal_types=explode(',', $value);
                  foreach($deal_types as $type)
                    $types_array[]=" FIND_IN_SET ('".$type."', deal.type) ";
                  
                  $sql_filter.="AND ( ".implode(' OR ', $types_array)." ) ";
                }else{
                  $sql_filter.="AND FIND_IN_SET (?, deal.type) ";
                  $exc_params[]=trim($value);
                }
              }
            break;
            case "theme":
              if($value!=''){
                if(count(explode(',',$value)) > 1) {
                  $deal_themes=explode(',', $value);
                  foreach($deal_themes as $theme)
                    $themes_array[]=" FIND_IN_SET ('".$theme."', deal.theme) ";
                  
                  $sql_filter.="AND ( ".implode(' OR ', $themes_array)." ) ";
                }else{
                  $sql_filter.="AND FIND_IN_SET (?, deal.theme) ";
                  $exc_params[]=trim($value);
                }
              }
            break;
            case "agency":
              if($value!=''){
                if(count(explode(',',$value)) > 1) {
                  $sql_filter.="AND deal.cid IN (".$value.") ";
                }else{
                  $sql_filter.="AND deal.cid=? ";
                  $exc_params[]=trim($value);
                }
              }
            break;
            case "keyword":
              if($value!=''){
                $sql_filter.="AND deal.title LIKE ? ";
                $exc_params[]='%'.$value.'%';
              }
            break;
            case "event":
              if($value!=''){
                $sql_filter.="AND deal.eid=? ";
                $exc_params[]=$value;
              }
            break;
            case "status":
              if($value!=''){
                if($value=='inactive')
                  $status="deal.status='active' AND (FIND_IN_SET('draft', deal.flags)) ";
                else if($value=='expired')
                  $status="deal.status='expired' ";
                else if($value=='active')
                  $status="deal.status='active' AND (NOT(FIND_IN_SET('draft', deal.flags))) AND deal.expire_on > '".date('Y-m-d H:i:s',(floor((strtotime('now') + 900) / 900) * 900))."' ";
              }
            break;
            case "lowest_price":
              if($value=="yes"){
                $lowest_price=TRUE;
              }
            break;
            default:
              $sql_filter.='';
          }
        }
      }
    }
    
    $order_statement=$sort_on='';
    if( (isset($_SESSION['sort_on']) && $_SESSION['sort_on']!='') && (isset($_SESSION['sort_by']) && $_SESSION['sort_by']!='') ){
      /*if($_SESSION['sort_on']=='sort_by_popularity'){
        $sort_on='';
        $order_statement.=' ORDER BY '.$sort_on;
      }else */if($_SESSION['sort_on']=='sort_by_expiry'){
        $sort_on=' deal.expire_on '.$_SESSION['sort_by'];
        $order_statement.=' ORDER BY '.$sort_on;
      }else if($_SESSION['sort_on']=='sort_by_recently'){
        $sort_on=' deal.created_date '.$_SESSION['sort_by'];
        $order_statement.=' ORDER BY '.$sort_on;
      }
    }else{
      if($randomize)
        $order_statement.=' ORDER BY RAND() ';
      else
        $order_statement.=' ORDER BY deal.last_updated_date DESC ';
    }
    
    if(isset($lowest_price) && $lowest_price)
      $order_statement=' ORDER BY deal.price_usd=0.00, deal.price_usd ASC ';
    
    //join the table
    if($join_table)
      $statement.="LEFT JOIN country_city_deal ON country_city_deal.deal_id=deal.id ";
    
    if($join_new_table)
      $statement.="LEFT JOIN deal_destination ON deal_destination.deal_id=deal.id ";
    
    //set the final Statement
    $statement.="WHERE ".$status.$sql_filter.$order_statement;
    //limit for retrieve the records, not for the "total records"
    if(!$count_query){
      $statement.=($limit?' LIMIT '.($n?$n.', ':' ').$limit.') dl':$limit.') dl');
      
      //IMPORTANT : to set the max len of "group_concat", so the "group_concat" of pck.id won't be limited
      //The maximum permitted result length in bytes for the GROUP_CONCAT() function. The default is 1024.
      $dbh->prepare('SET group_concat_max_len = 999999')->execute();
    }
    
    //var_dump($statement, $exc_params); exit;
    $sql = $dbh->prepare($statement);
    $sql->execute($exc_params);
    
    //get the result, will be returned as string
    //returns as number(total records) or ids separated by comma(deal ids)
    if($count_query)
      $result=$sql->fetchColumn();
    else
      $result=$sql->fetch(PDO::FETCH_COLUMN);
    
    return $result;
  }
  static function retrieve_company_by_deal(){
    $dbh = getdbh();
    $statement="SELECT GROUP_CONCAT(cpy.cid) as ids FROM (SELECT DISTINCT deal.cid FROM deal WHERE deal.status='active' AND deal.flags!='draft') cpy";

    $sql = $dbh->prepare($statement);
    $sql->execute();
    $result=$sql->fetch(PDO::FETCH_COLUMN);

    return $result;
  }
  static function get_all_countries(){
    $dbh = getdbh();
      $statement="SELECT name FROM `destination` WHERE `type` = 'country'";
      $sql = $dbh->prepare($statement);
      $sql->execute();
      $result=$sql->fetchAll();    
      return $result;
  }


}
