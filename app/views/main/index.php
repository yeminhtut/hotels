<div class="topic">
	<div class="container"></div>
	<div class="topic__infos">
		<div class="container">
			<form class="form-inline" action="property/search_new" method="POST" autocomplete="off">
			<div class="form-group">
				<select class="form-control" name="where">
					<?= $location ?>
				</select>
			</div>
			<div class="form-group">			    
				<input type="text" class="form-control" id="datepicker" placeholder="Check-in" name="check-in-date">
			</div>
			<div class="form-group">			   
				<input type="text" class="form-control" id="datepickerCheckout" placeholder="Check-out" name="check-out-date" disabled>
			</div>
			<div class="form-group">	
				<select class="form-control" name="no_of_guests">					
					<option value="1">1 Guests</option>
					<option value="2">2 of Guests</option>
					<option value="3">3 of Guests</option>
					<option value="4">4 of Guests</option>
				</select>
			</div>
			<div class="form-group">	
				<select class="form-control" name="no_of_rooms">					
					<option value="1">1 rooms</option>
					<option value="2">2 rooms</option>
					<option value="3">3 rooms</option>
					<option value="4">4 rooms</option>
				</select>
			</div>
			<button type="submit" class="btn green-btn">Search</button>
			</form>
		</div>
	</div>
</div>

<script>
$(document).ready(function() {
	var now = new Date();
	var d = new Date(new Date().getTime() + 168 * 60 * 60 * 1000);
	var checkInStart = d.format("mm/dd/yyyy");	

	$('#datepicker').datepicker({
		startDate: checkInStart,    	
	    autoclose: true,	    
	    
	});
	$('#datepicker').change(function(){
    var changedate = $('#datepicker').val();
    var myDate = new Date(changedate);	
    var checkout = myDate.setDate(myDate.getDate() + 1);
    var time = 1435334400000;
	var date = new Date(checkout);
	var t = date.toString("MMMM yyyy");
	var newdate = new Date(t);
	var can = (newdate.getMonth() + 1) + '/' + newdate.getDate() + '/' +  newdate.getFullYear();
	$("#datepickerCheckout").removeAttr('disabled');
	    $('#datepickerCheckout').datepicker({
			startDate: can,	    
		    autoclose: true,
		    
		});
	});
	
});
</script>
