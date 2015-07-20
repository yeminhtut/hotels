<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.7.2/jquery.min.js"></script>
<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.8.18/jquery-ui.min.js"></script>
<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.8.18/jquery-ui.min.js"></script>

<script type="text/javascript">
$(document).ready(function(){
		$( "#where" ).autocomplete({
		  source: function( request, response ) {
			  $.ajax({
		      url: "http://localhost/hotels/scraper",
		      dataType: 'json',
		      type: 'POST',
		      data: request,
		      success: function(data){
		      	console.log(data);
		    	  response(data);		         
	        }
		    });
		  },
		  minLength: 2,
		  select: function( event, ui ) {
	  		$('#destination_code').val(ui.item.id);	
	  		$('#autocomplete').val('y');
		  },
		  open: function() {
		    $( this ).removeClass( "ui-corner-all" ).addClass( "ui-corner-top" );
		    $( this ).autocomplete( 'widget' ).css( 'z-index' , 100);
		  },
		  close: function() {
		    $( this ).removeClass( "ui-corner-top" ).addClass( "ui-corner-all" );
		  }
		});	

});

</script>

<div class="search_table_cell" style="padding-right: 12px;">
	<input class="required search_table_cell_input" id="where" name="where" type="text" placeholder="eg. Singapore"/>	  		
	<input class="" id="destination_code" type="hidden" name="destination_code" value="">
	<input class="" id="autocomplete" type="hidden" name="autocomplete" value="n">
</div>
<style type="text/css">
/* highlight results */
.ui-autocomplete span.hl_results {
    background-color: #ffff66;
}
 
/* loading - the AJAX indicator */
.ui-autocomplete-loading {
    background: white url('../img/ui-anim_basic_16x16.gif') right center no-repeat;
}
 
/* scroll results */
.ui-autocomplete {
    max-height: 250px;
    overflow-y: auto;
    /* prevent horizontal scrollbar */
    overflow-x: hidden;
    /* add padding for vertical scrollbar */
    padding-right: 5px;
}
 
.ui-autocomplete li {
    font-size: 16px;
}
 
/* IE 6 doesn't support max-height
* we use height instead, but this forces the menu to always be this tall
*/
* html .ui-autocomplete {
    height: 250px;
}
</style>