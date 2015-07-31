<?php
class Booking_Info extends Model {

  function __construct($booking_id='') {
    parent::__construct('booking_id','t_booking_info');
    $this->rs['booking_id']=0;
    $this->rs['booking_key']='';
    $this->rs['salutation']='';
    $this->rs['first_name']='';
    $this->rs['last_name']='';
    $this->rs['email']='';
    $this->rs['credit_card_number']='';
    $this->rs['name_on_card']='';
    $this->rs['credit_month']='';
    $this->rs['credit_year']='';
    $this->rs['secruity']='';
    $this->rs['billing_address']='';
    $this->rs['billing_city']='';
    $this->rs['billing_country']='';
    $this->rs['billing_postal']='';
    $this->rs['created_dt']='0000-00-00 00:00:00'; 
    
    if ($booking_id)
      $this->retrieve($booking_id);
  }
}
