<div role="main"> </div>
<!-- end of main-->

<div class="content_div" onmouseover="hide_all()">
	
	<div class="navbar_left_div">
		<?php include(VIEW_PATH . 'layouts/layout_leftsidebar.php');?>
	</div>
	<!-- Body Content-->
	<div class="container">
		
		<div class="row">		  
		
			<h2>Enquiries</h2>
		
		  <?php 
		  if(isset($enquiries) && count($enquiries)) {
				$total_packages = count($enquiries);
			
			?>
			<table id="enquiries_table" style="text-align: left;">
				<tr>
					<td width="10%" class="table-header-left"><b>Date</b></td>
					<td width="20%" class="table-header-left"><b>Sent To</b></td>
					<td width="20%" class="table-header-left"><b>Package Name</b></td>
					<td width="50%" class="table-header-right"><b>Enquiry</b></td>
				</tr>
				<?php foreach($enquiries as $row) { ?>
				<tr>
					<td class='table-left'><?php echo date('d M Y', strtotime($row['Created_Date']))?></td>
					<td class='table-left'><a href='/directory/review/<?php echo $row['CompanyID']?>/<?php echo url_title($row['Name'])?>'><?php echo $row['Name']?></td>
						<td class='table-left'><a href='/tour/package/<?php echo $row['PostingID']?>/<?php echo url_title($row['Title'])?>'><?php echo $row['Title']?></a></td>
						<td style='text-align: left' class='table-right'>
						<div class='more-less'>
						<div class='more-block'>
						<p><?php echo nl2br($row['Remarks'])?></p>
						</div>
	          </div>
	        </td>
				</tr>
				<?php } ?>
			</table>
			<?php } else { ?>
				<p>You have no enquiries yet.</p>
			<?php } ?>
		</div>
	</div>
</div>
<script>
$(function(){
	var adjustheight = 18;
	var moreText = '(read more)';
	var lessText = '(less)';
	
	if($('.more-less .more-block').height() > adjustheight) {
		$('.more-less .more-block').css('height', adjustheight).css('overflow', 'hidden');
		$('.more-less').append('<p class=\"continued\"></p><a href=\"#\" class=\"adjust\"></a>');
		$('a.adjust').text(moreText);
		$('.adjust').toggle(function() {
			$(this).parents('div:first').find('.more-block').css('height', 'auto').css('overflow', 'visible');
			$(this).parents('div:first').find('p.continued').css('display', 'none');
			$(this).text(lessText);
		}, function() {
			$(this).parents('div:first').find('.more-block').css('height', adjustheight).css('overflow', 'hidden');
			$(this).parents('div:first').find('p.continued').css('display', 'block');
			$(this).text(moreText);
		});
	}
});
</script>