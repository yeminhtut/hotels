<?php
class Company extends Model {

  function __construct($id='') {
    parent::__construct('id','company');
    $this->rs['id'] = '';
    $this->rs['catid'] = '';
    $this->rs['display_name'] = '';
    $this->rs['fullname'] = '';
    $this->rs['description'] = '';
    $this->rs['opening_hours'] = '';
    $this->rs['email'] = '';
    $this->rs['website1'] = '';
    $this->rs['website2'] = '';
    $this->rs['address'] = array();
    $this->rs['address1'] = '';
    $this->rs['address2'] = '';
    $this->rs['states'] = '';
    $this->rs['lat'] = '';
    $this->rs['lng'] = '';
    $this->rs['contact'] = array();
    $this->rs['contact1'] = '';
    $this->rs['contact2'] = '';
    $this->rs['agency_number'] = '';
    $this->rs['contact_title'] = '';
    $this->rs['contact_person'] = '';
    $this->rs['contact_email'] = '';
    $this->rs['remarks'] = '';
    $this->rs['status'] = '';
    $this->rs['featured_expiry_date'] = '';
    $this->rs['featured_packages_limit'] = '';
    $this->rs['edm_emails'] = '';
    $this->rs['source'] = '';
    $this->rs['thumbnail_url'] = '';
    $this->rs['created_dt'] = '';
    $this->rs['flags'] = '';

    if ($id)
      $this->retrieve($id);
  }
  
  function getpermalink($fullurl=true) {
    return Package::makepermalink($this->get('id'),$this->get('display_name'),$fullurl);
  }
  
  static function makepermalink($id,$name,$fullurl=true) {
    $id=(int)$id;
    $url='/directory/review/'.$id.'/'.makeslug($name);
    if ($fullurl)
      $url=WEB_DOMAIN.$url;
    return $url;
  }
  
  function import_company($obj){
    $this->set('id', $obj['CompanyID']);
    $this->set('catid', $obj['Directory_Category_ID']);
    $this->set('display_name', $obj['Name']);
    $this->set('full_name', $obj['Full_Name']);
    $this->set('description', $obj['Description']);
    //$this->set('opening_hours', $obj['Opening_Hours']); //TZ My doesnt have this attribute
    $this->set('email', $obj['Email']);
    $this->set('website1', $obj['Website1']);
    $this->set('website2', $obj['Website2']);
    $this->set('address1', $obj['Address1']);
    $this->set('address2', $obj['Address2']);
    $this->set('states', $obj['States']);
    $this->set('lat', $obj['Lat']);
    $this->set('lng', $obj['Lng']);
    $this->set('contact1', $obj['Contact1']);
    $this->set('contact2', $obj['Contact2']);
    $this->set('agency_number', $obj['Agency_Number']);
    $this->set('contact_title', $obj['Contact_Title']);
    $this->set('contact_email', $obj['Contact_Email']);
    $this->set('remarks', $obj['Remarks']);
    $this->set('status', $obj['Status']);
    $this->set('featured_expiry_date', $obj['Featured_Expiry_Date']);
    $this->set('featured_packages_limit', $obj['Featured_Packages_Limit']);
    $this->set('edm_emails', $obj['Edm_Emails']);
    $this->set('source', $obj['Source']);
    $this->set('created_by', $obj['Created_By_ID']);
    $this->set('created_date', $obj['Created_Date']);
    $this->set('last_updated_by', $obj['Last_Updated_By_ID']);
    $this->set('last_updated_date', $obj['Last_Updated_Date']);
    
    $flags=array();
    if($obj['Deal']=='y')
      $flags[]='deal';
    if($obj['Featured']=='y')
      $flags[]='featured';
    
    $this->set('flags', implode(',', $flags));
    
    return $this;
  }
  
  static function retrieve_company_by_alphabetical($params=false, $n=false, $limit=false, $count_query=FALSE){
    $dbh = getdbh();
    $exc_params=array();
    
    if(isset($params['category']) && $params['category']!=''){
      $sql_filter[]="catid=?";
      $exc_params[] = $params['category'];
    }
    
    if(isset($params['alphabet']) && !is_numeric($params['alphabet']) && $params['alphabet']!=''){
      $sql_filter[]="display_name LIKE ?";
      $exc_params[] = $params['alphabet']."%";
    }
    
    if(isset($params['hex']) && $params['hex']==true){
      $sql_filter[]="display_name REGEXP '^[^A-Z]'";
    }
    
    $sql_filter[]="company.status='approved'";
    
    if($count_query){
      $statement="SELECT COUNT(DISTINCT company.id) FROM company WHERE ".implode(' AND ', $sql_filter)." ".(($limit)?' LIMIT '.(($n)?$n.', ':'').$limit : '');
    }else
      $statement="SELECT GROUP_CONCAT(cpy.id) as ids FROM ( SELECT DISTINCT company.id FROM company WHERE ".implode(' AND ', $sql_filter)." ".(($limit)?' LIMIT '.(($n)?$n.', ':'').$limit : '')." ) cpy";
    
    $sql = $dbh->prepare($statement);
    $sql->execute($exc_params);
    
    if($count_query)
      $result=$sql->fetchColumn();
    else
      $result=$sql->fetch(PDO::FETCH_COLUMN);
    
    return $result;
  }
  
  static function search($params=false, $n=false, $limit=false, $count_query=FALSE, $randomize=false){
    $dbh = getdbh();
    $sql_filter='';
    $exc_params=array();
    $status="company.status='approved' ";
    
    //get the total records or retrieve the records
    if($count_query)
      $statement="SELECT COUNT(DISTINCT company.id) FROM company ";
    else
      $statement="SELECT GROUP_CONCAT(cp.id) as ids FROM (SELECT DISTINCT company.id FROM company ";
    
    //check the parameters that has been passed from Controllers
    if($params){
      foreach($params as $key=>$value){
        if($value!='~'){
          switch ($key) {
            case "featured":
              if($value!='' && $value=='y'){
                $sql_filter.="AND company.flags LIKE ? ";
                $exc_params[]='%featured%';
              }else if($value!='' && $value=='n'){
                $sql_filter.="AND company.flags NOT LIKE ? ";
                $exc_params[]='%featured%';
              }
            break;
            case "keyword":
              if($value!=''){
                $sql_filter.="AND company.display_name LIKE ? ";
                $exc_params[]='%'.$value.'%';
              }
            break;
            default:
              $sql_filter.='';
          }
        }
      }
    }
    
    //set the final Statement
    $statement.="WHERE ".$status.$sql_filter;
    //limit for retrieve the records, not for the "total records"
    if(!$count_query){
      $statement.=($limit?' LIMIT '.($n?$n.', ':' ').$limit.') cp':$limit.') cp');
      
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
}
