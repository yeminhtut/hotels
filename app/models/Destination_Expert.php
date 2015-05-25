<?php
class Destination_Expert extends Model {

  function __construct($id='') {
    parent::__construct('id','destination_expert');
    $this->rs['id'] = '';
    $this->rs['country_id'] = '';
    $this->rs['cid'] = '';
    $this->rs['keyword'] = '';
    $this->rs['start_date'] = '';
    $this->rs['end_date'] = '';
    $this->rs['status'] = '';
    $this->rs['created_dt'] = '';

    if ($id)
      $this->retrieve($id);
  }
}