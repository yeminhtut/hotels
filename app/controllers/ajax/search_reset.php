<?php
function _search_reset(){
  unset($_SESSION['where']);
  /*unset($_SESSION['dom_int']);REMOVE!!*/
  unset($_SESSION['departure']);
  unset($_SESSION['days']);
  unset($_SESSION['prices']);
  unset($_SESSION['deal_showing']);
  unset($_SESSION['package_type']);
  unset($_SESSION['deal_type']);
  unset($_SESSION['package_theme']);
  unset($_SESSION['deal_theme']);
  unset($_SESSION['package_agency']);
  unset($_SESSION['deal_agency']);
  unset($_SESSION['sort_on']);
  unset($_SESSION['sort_by']);
}