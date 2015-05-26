<div class="topic">
	<div class="container"></div>
	<div class="topic__infos">
		<div class="container">
			<form class="form-inline" action="property/search_new" method="POST">
			<div class="form-group">
				<select class="form-control" name="where">
					<?= $location ?>
				</select>
			</div>
			<div class="form-group">			    
				<input type="text" class="form-control" id="datepicker" placeholder="Check-in" name="check-in-date">
			</div>
			<div class="form-group">			   
				<input type="text" class="form-control" id="datepickerCheckout" placeholder="Check-out" name="check-out-date">
			</div>
			<div class="form-group">	
				<select class="form-control" name="no_of_guests">
					<option value="">Number of Guests</option>
					<option value="1">1 Guests</option>
					<option value="2">2 of Guests</option>
					<option value="3">3 of Guests</option>
					<option value="4">4 of Guests</option>
				</select>
			</div>
			<div class="form-group">	
				<select class="form-control" name="no_of_rooms">
					<option value="">Number of rooms</option>
					<option value="1">1 rooms</option>
					<option value="2">2 rooms</option>
					<option value="3">3 rooms</option>
					<option value="4">4 rooms</option>
				</select>
			</div>
			<button type="submit" class="btn btn-default">Search</button>
			</form>
		</div>
	</div>
</div>

<script>
$(document).ready(function() {
	$('#datepicker').datepicker({
		startDate: "05/26/2015",
	    todayBtn: true,
	    calendarWeeks: true,
	    autoclose: true,
	    todayHighlight: true,
	    datesDisabled: ['05/06/2015', '05/21/2015']
	});

	$('#datepickerCheckout').datepicker({
		startDate: "06/26/2015",
	    todayBtn: true,
	    calendarWeeks: true,
	    autoclose: true,
	    todayHighlight: true,
	    datesDisabled: ['05/06/2015', '05/21/2015']
	});
});
</script>

