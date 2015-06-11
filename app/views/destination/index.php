<div class="error"><?php echo $search_complete; ?></div>
<div class="row" style="margin-top:50px;">
<?= $hotel_list; ?>    
</div>
<?= $pagination; ?>
<style type="text/css">
.thumb{width:100%;height: 200px !important;}
</style>
<script type="text/javascript">
$(document).ready(function(){
	// var searchComplete = $('.error').text();
	// console.log(searchComplete);
	// var i = 0;
	// if (searchComplete == '') {
	// 	var i = i+1;
	// 	console.log('need to reload');
	// 	setTimeout(function(){ window.location.reload();}, 1000)
	// };
	
})
</script>