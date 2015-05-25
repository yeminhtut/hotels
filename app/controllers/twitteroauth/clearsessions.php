<?php
function _clearsessions() {

//=======================================
  session_start();
  session_destroy();
 
  /* Redirect to page with the connect to Twitter option. */
  redirect('');

}