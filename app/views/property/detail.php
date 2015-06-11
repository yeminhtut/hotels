<div class="row" style="margin-top:50px;margin-bottom:50px;">
	<div id="property_facts_wrapper">
		<div id="property_img">
			<img src="https://s3-ap-southeast-1.amazonaws.com/zumata/assets/hotels/2.0/ee1029c1-ad51-4c77-7410-35a908c8632a/images/25.jpg">
		</div>
		<div id="property_facts">
			<?php echo "<p>".$property->address."</p>";?>
		</div>
		<div class="clear"></div>
	</div>
	
</div>
<div class="row">
	<?= $hotel_rooms ?>
</div>
<style type="text/css">
#property_facts_wrapper{
	background:#FFF;
	height:400px;
	width:100%;
}
#property_img{
	width: 50%;
	float:left;
}
#property_facts{
	width:50%;
	float:left;
}
#property_img img{height:400px;}
.clear{
	clear:both;
}
</style>