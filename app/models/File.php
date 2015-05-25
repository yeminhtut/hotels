<?php
class File extends Model {

  function __construct($id='') {
    parent::__construct('id','file');
    $this->rs['id'] = 0;
    $this->rs['extension'] = '';
    $this->rs['destination_id'] = 0;
    $this->rs['reference_id'] = 0;
    $this->rs['reference_type'] = '';
    $this->rs['type'] = '';
    $this->rs['name'] = '';
    $this->rs['created_by'] = 0;
    $this->rs['created_date'] = '0000-00-00 00:00:00';
    $this->rs['last_updated_by'] = 0;
    $this->rs['last_updated_date'] = '0000-00-00 00:00:00';

    if ($id)
      $this->retrieve($id);
  }
  
  function import_file($obj){
    $this->set( 'id' , $obj['FileID']);
    $this->set( 'extension' , $obj['Extension']);
    
    if($obj['ReferenceType']=='country')
      $this->set( 'destination_id' , $obj['ReferenceID']);
    
    $this->set( 'reference_id' , $obj['ReferenceID']);
    if($obj['ReferenceType']=='posting')
      $this->set( 'reference_type' , 'package');
    else if($obj['ReferenceType']=='traveldeal')
      $this->set( 'reference_type' , 'deal');
    else if($obj['ReferenceType']=='directory')
      $this->set( 'reference_type' , 'company');
    else
      $this->set( 'reference_type' , $obj['ReferenceType']);
    
    $this->set( 'type' , $obj['Type']);
    $this->set( 'name' , $obj['Name']);
    
    $this->set( 'created_by' , $obj['Created_By_ID']);
    $this->set( 'created_date' , $obj['Created_Date']);
    $this->set( 'last_updated_by' , $obj['Last_Updated_By_ID']);
    $this->set( 'last_updated_date' , $obj['Last_Updated_Date']);
    
    return $this;
  }
  
  static function delete_expired_deals_files(){
    $dbh = getdbh();
    
    $sql=$dbh->prepare("SELECT file.extension,file.id FROM file LEFT JOIN deal ON file.reference_id=deal.id WHERE file.reference_type='deal' AND deal.expire_on < NOW() AND deal.eid=0");
    $sql->execute();
    $result=$sql->fetchAll();
    
    if(count($result)>0){
      foreach($result as $row){
        @unlink("/var/www/sites/trip_my/web/files/".$row['id'].".".$row['extension']);
        $sql=$dbh->prepare("DELETE FROM file WHERE id = ? AND reference_type='deal'");
        $sql->execute(array($row['id']));
      }
    }
  }
}