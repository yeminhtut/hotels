<div class="row" style="background:#FFF;margin-top:30px;padding:10px;">
   <div class="col-md-8">
      <!--Start New Form -->
      <form id="form-booking" class="form-horizontal" action="/hotels/property/booking_result" method="POST">
         <ul>
            <li>
               <h3>Guest Information</h3>
               <div class="form-group form-group-sm">
               		<input class="form-control" type="hidden" id="formGroupInputSmall" name="booking_key" value="<?= $booking_key ?>">
	                <label class="col-sm-2 control-label" for="formGroupInputSmall">Title</label>
	                <div class="col-sm-10">
	                    <select class="form-control" name="salutation">
	                    	<option value="0"></option>
	                        <option value="Mr.">Mr.</option>
	                        <option value="Ms.">Ms.</option>
	                        <option value="Mrs.">Mrs.</option>
	                    </select>
	                </div>
               </div>
               <div class="form-group form-group-sm">
                  <label class="col-sm-2 control-label" for="formGroupInputSmall">Frist Name</label>
                  <div class="col-sm-10">
                     <input class="form-control" type="text" id="formGroupInputSmall" placeholder="First Name" name="first_name">
                  </div>
               </div>
               <div class="form-group form-group-sm">
                  <label class="col-sm-2 control-label" for="formGroupInputSmall">Last Name</label>
                  <div class="col-sm-10">
                     <input class="form-control" type="text" id="formGroupInputSmall" placeholder="Last Name" name="last_name">
                  </div>
               </div>
               <div class="form-group form-group-sm">
                  <label class="col-sm-2 control-label" for="formGroupInputSmall">Your Email</label>
                  <div class="col-sm-10">
                     <input class="form-control" type="text" id="formGroupInputSmall" placeholder="Email" name="email">
                  </div>
               </div>
            </li>
            <li>
               <h3>Credit Card Information</h3>
               <div class="form-group form-group-sm">
                  <label class="col-sm-2 control-label" for="formGroupInputSmall">Credit Card Number</label>
                  <div class="col-sm-10">
                     <input class="form-control" type="text" id="formGroupInputSmall" placeholder="Credit Card Number" name="credit_card_number">
                  </div>
               </div>
               <div class="form-group form-group-sm">
                  <label class="col-sm-2 control-label" for="formGroupInputSmall">Name On Card</label>
                  <div class="col-sm-10">
                     <input class="form-control" type="text" id="formGroupInputSmall" placeholder="Name On Card" name="name_on_card">
                  </div>
               </div>
               <div class="form-group form-group-sm">
                  <label class="col-sm-2 control-label" for="formGroupInputSmall">Expiry</label>
                  <div class="col-xs-4">
                     <select class="form-control" name="credit_month">
                        <option value="0">Month</option>
                        <?php 
                           for ($i=01; $i < 13 ; $i++) { ?>
                        <option value="<?php echo $i;?>"><?php echo $i;?></option>
                        <?php	}
                           ?>
                     </select>
                  </div>
                  <div class="col-xs-4">
                     <select class="form-control" name="credit_year">
                        <option value="0">Year</option>
                        <?php 
                           for ($i=2015; $i < 2023 ; $i++) { ?>
                        <option value="<?php echo $i;?>"><?php echo $i;?></option>
                        <?php	}
                           ?>
                     </select>
                     <input class="form-control" type="hidden" id="formGroupInputSmall" name="credit_card" value="credit_card">
                  </div>
               </div>
               <div class="form-group form-group-sm">
                  <label class="col-sm-2 control-label" for="formGroupInputSmall">Secruity Code</label>
                  <div class="col-sm-10">
                     <input class="form-control" type="text" id="formGroupInputSmall" placeholder="" name="secruity">
                  </div>
               </div>
            </li>
            <li>
               <h3>Billing Address</h3>
                <div class="form-group form-group-sm">
                    <label class="col-sm-2 control-label" for="formGroupInputSmall">Address</label>
                    <div class="col-sm-10">
                    <input class="form-control" type="text" id="formGroupInputSmall" placeholder="City" name="billing_address">
                </div>
                </div>
                <div class="form-group form-group-sm">
                    <label class="col-sm-2 control-label" for="formGroupInputSmall">City</label>
                    <div class="col-sm-10">
                    	<input class="form-control" type="text" id="formGroupInputSmall" placeholder="City" name="billing_city">
                    </div>
                </div>
               
                <div class="form-group form-group-sm">
                    <label class="col-sm-2 control-label" for="formGroupInputSmall">Country</label>
                    <div class="col-sm-10">
                    	<input class="form-control" type="text" id="formGroupInputSmall" placeholder="Country" name="billing_country">
                    </div>
                </div>
               <div class="form-group form-group-sm">
                    <label class="col-sm-2 control-label" for="formGroupInputSmall">Postal Code</label>
                    <div class="col-sm-10">
                    	<input class="form-control" type="text" id="formGroupInputSmall" placeholder="postal_code" name="billing_postal">
                    </div>
               </div>
            </li>
            <li>
                <div class="form-group form-group-sm">
                	<button type="submit" class="btn btn-primary btn-block">Book</button>
                </div>
            </li>
         </ul>
      </form>
   </div>
   <!--end of booking-info -->
   <div class="col-md-4">Booking Summary</div>
</div>

<style type="text/css">
#form-booking li:not(:last-child){
  border-bottom: 1px solid #EBEBEB;
}

</style>