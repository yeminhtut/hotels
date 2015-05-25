<?php
function _logout()
{
  User::logout();
  redirect('');
  exit();
}
?>