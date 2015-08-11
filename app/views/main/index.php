<div class="topic">
    <div class="container"></div>
    <div class="topic__infos">
        <div class="container">
            <form class="form-inline" action="property/search" method="POST" autocomplete="off" data-toggle="validator">
                <div class="form-group">               					
                        <input class="required search_table_cell_input form-control" id="where" name="where" type="text" placeholder="eg. Singapore"/>            
                        <input class="" id="destination_code" type="hidden" name="destination_code" value="">
                        <input class="" id="autocomplete" type="hidden" name="autocomplete" value="n">               
                </div>               
                <div class="form-group">                    
                    <input type="text" id="from" class="form-control" placeholder="Check-in" name="check-in-date">
                </div>
                <div class="form-group">                    
                    <input type="text" id="to" class="form-control" id="datepickerCheckout" placeholder="Check-out" name="check-out-date">
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

