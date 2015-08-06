<?php
class Booking_Temp extends Model {

  function __construct($booking_id='') {
    parent::__construct('id','booking_temp');
    $this->rs['room_key']=0;
    $this->rs['room_des']='';
    $this->rs['price']='';
    $this->rs['hotel_id']='';
    $this->rs['hotel_img']='';
    $this->rs['hotel_name']='';  
    $this->rs['persons']='';
    $this->rs['rooms']='';
    $this->rs['check_in']='0000-00-00 00:00:00';
    $this->rs['check_out']='0000-00-00 00:00:00';  
    $this->rs['created_dt']='0000-00-00 00:00:00'; 
    
    if ($id)
      $this->retrieve($id);
  }
}
