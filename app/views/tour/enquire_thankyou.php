<div role="main"> </div>
<!-- end of main-->

<div class="content_div" onmouseover="hide_all()">
	
	<div class="navbar_left_div">
		<?php include(VIEW_PATH."layouts/layout_leftsidebar.php");?>
	</div>
	<!-- Body Content-->
	<div class="container">
		
		
		
		<div class="row">
		  <div class="row_alignleft"><h1>Enquiry Submitted</h1></div>
		  
			<p>Your query has been sent. A copy of the email has also been sent to you with the contact details of the vendor. Please contact the vendor directly if you need further assistance.</p>
			
		</div>
	</div>
</div>
<?php
// Virtual Page View for GA Events / Goals
if(isset($_SESSION['flash_similar_packages'])) {
  $flash_similar_packages = explode(',',$_SESSION['flash_similar_packages']);
  if(sizeof($flash_similar_packages)>0) {
    foreach($flash_similar_packages as $posting_id) {
    ?>
    <script type="text/javascript">
      _gaq.push(['_trackPageview', '/tour/enquire_thankyou/<?=$posting_id?>/bulk-sent']);
    </script>
    <?php
    }
  }
}

// unset similar_packages
unset($_SESSION['flash_similar_packages']);