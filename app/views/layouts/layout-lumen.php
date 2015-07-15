<?php
$authuid=isset($_SESSION['authuid']) ? $_SESSION['authuid'] : 0;
$pagetitle=isset($pagename) ? $GLOBALS['sitename'].' - '.$pagename : $GLOBALS['sitename'];
$foot[]=getjAlert();
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<title><?=$pagetitle?></title>
<link rel="stylesheet" type="text/css" href="/hotels/css/reset.css">
<link rel="stylesheet" type="text/css" href="http://localhost/hotels/web/css/bootstrap.min.css">
<link rel="stylesheet" type="text/css" href="/hotels/css/main.css">
<link rel="stylesheet" type="text/css" href="/hotels/css/site.css">
<script type="text/javascript" src="<?=myUrl('/js/jquery.min.js')?>"></script>
<script type="text/javascript" src="<?=myUrl('/js/dateformat.js')?>"></script>
<script src="//cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.3.0/js/bootstrap-datepicker.min.js"></script>
<script type="text/javascript" src="http://magdev.tripzilla.com/ajax/js/jquery.backstretch.min.js"></script>
<script type="text/javascript" src="<?=myUrl('/js/progressbar.js')?>"></script>
<style type="text/css">
  @import "<?=myUrl('/css/datepicker3.min.css')?>";
  /*@import "<?=myUrl('/css/skeleton.css')?>";*/
  @import "<?=myUrl('/css/tab-style.css')?>";
  @import "<?=myUrl('/css/main.css')?>";
</style>


<?=(isset($head) && is_array($head)) ? implode("\n",$head) : ''?>
</head>
<body style="background-color: rgb(241, 242, 246);">

   <div class="docs-header">
      <!--nav-->
      <nav class="navbar navbar-default navbar-custom" role="navigation">
        <div class="container">
          <div class="navbar-header">
            <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
              <span class="sr-only">Toggle navigation</span>
              <span class="icon-bar"></span>
              <span class="icon-bar"></span>
              <span class="icon-bar"></span>
            </button>
            <a class="navbar-brand" href="/hotels">logo</a>
          </div>          
        </div>
      </nav>     
    </div>

  <div class="container">
      <?=(isset($body) && is_array($body)) ? implode("\n",$body) : ''?>
  </div>

  <div id="footer">
    
  </div>

<?=(isset($foot) && is_array($foot)) ? implode("\n",$foot) : ''?>

</body>
</html>