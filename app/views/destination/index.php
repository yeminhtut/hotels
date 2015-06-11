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
	// var items = $('.list');
	// var numItems = $('.list').length;	
	// if (numItems<1) {
	// 	console.log('need to reload');
	// 	setTimeout(function(){ window.location.reload();}, 1000)
	// };
	var searchComplete = $('.error').text();
	console.log(searchComplete);
	if (searchComplete == '') {
		console.log('need to reload');
		setTimeout(function(){ window.location.reload();}, 1000)
	};	
})
</script>