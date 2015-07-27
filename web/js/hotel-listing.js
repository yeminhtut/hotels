var time = 0;
var newhtml = '';

//Sorting//

$('#sort_by').on('change', function(e) {
    var optionSelected = $("option:selected", this);
    var valueSelected = this.value;
    switch (valueSelected) {
        case 'price_lth':
            price_low_to_high();
            break;
        case 'price_htl':
            price_high_to_low();
            break;

        default:
            sort_by_best_deals();
            break;
    }
});

function price_low_to_high() {
    var $wrapper = $('.hotel-list');
    $wrapper.find('.hotel-row').sort(function(a, b) {
        return +a.getAttribute('data-price') - +b.getAttribute('data-price');
    }).appendTo($wrapper);
}

function price_high_to_low() {
    var $wrapper = $('.hotel-list');
    $wrapper.find('.hotel-row').sort(function(a, b) {
        return +b.getAttribute('data-price') - +a.getAttribute('data-price');
    }).appendTo($wrapper);
}

function sort_by_best_deals() {
    var $wrapper = $('.hotel-list');
    $wrapper.find('.hotel-row').sort(function(a, b) {
        return +b.getAttribute('data-best-price') - +a.getAttribute('data-best-price');
    }).appendTo($wrapper);
}

//End of sorting//

function imgError(image) {
    image.onerror = "";
    image.src = "http://localhost/hotels/web/img/default.png";
    return true;
}


function load_select() {
    var cur_url = window.location.href;
    var parse_arr = cur_url.split("/");
    var destination = parse_arr[5];
    var checkin = parse_arr[7];
    var checkout = parse_arr[8];
    var persons = parse_arr[9];
    var rooms = parse_arr[10];
    var newhtml = '';
    $.ajax({
        type: "POST",
        url: "http://localhost/hotels/ajax/search_hotels",
        dataType: 'json',
        data: {
            destination: destination,
            checkin: checkin,
            checkout: checkout,
            persons: persons,
            rooms: rooms
        },
        success: function(response) {
        	console.log(response);
        	var response_count = Object.keys(response).length;
        	console.log('response count is ' +response_count);
        	var count = 0;
        	if (response_count > 0) {
        		var search_complete = response.search_completed;
	            var data = response.hotels;
	            count = Object.keys(data).length;
	            console.log(count);
        	};           

            if (count > 1) {
                var j = 1;
                var hotel_ids_arr = [];
                $.each(data, function(i, item) {  
                	hotel_ids_arr.push(item.id[0]);                 
                    newhtml = hotel_listing_view(j, item);
                    $(".hotel-list").append(newhtml);
                    $('.hotel-list').css("display", "block");
                    $('#avaliable-list').remove();
                    j++;
                });
                $('#status').html('1');
                var hotel_count = j - 1;
                var result_list_count = '<strong>' + hotel_count + '</strong> hotels found';
                $('#results-bar').html(result_list_count);
                $('#results-bar').css("display", "block");
                $('#sorting_select').css("display", "block");                
            };
        },
        complete: function() {
            var status = $("#status").html();
            console.log(status);
            if (status !== '1') {
                console.log('need to call again');
                if (time < 30001) {
                    console.log(time);
                    if (status !== 1) {};
                    setTimeout(load_select, 5000);
                    time = time + 5000;
                } else if (time > 30001 && status !== 1) {
                    $("#avaliable-list").html("<p><center style=\"font-weight:bold;\">Sorry, no available hotels found.. change search criteria...</center></p>");
                }
            } else {
                console.log('no need to call');
                sort_by_best_deals();
            };
        }
    });
}

function hotel_listing_view(j, item) {
    var hotel_id = item.id[0];

    var hotel_price = item.rates.packages[0].chargeableRate;

    var comp_price = item.rates.compRates;
    var best_price = 0;
    var featured = '';
    var original_price = '';
    if (comp_price !== null) {
        var best_price = item.rates.compRates[0]['price'];
        //if (best_price < hotel_price) {
        	var hotel_price = best_price;
	        var original_price = '<span class="ori_price">' + item.rates.packages[0].chargeableRate + '</span>';
	        var featured = '<span class="best_deal">Best deal</span>';
        //};        
    };
    var image_src = item.image_details.prefix + '/1' + item.image_details.suffix;
    var thumbnail_div = '<div class="col-lg-4 col-md-4 col-sm-4" style="padding-left:0px;">'+ featured +'<div class="img_list"><img width="180" height="120" src="' + image_src + '" onerror="imgError(this);"></div></div>';

    var item_name = '<h3 class="link-title">' + item.name + '</h3>';

    var detail_collapse = '<a class="btn btn-default collapse-expand" role="button" data-toggle="collapse" href="#details' + hotel_id + '" aria-expanded="false" aria-controls="details' + hotel_id + '">Details</a>';
    var item_details = '<div class="collapse" id="details' + hotel_id + '"><div class="events">Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit </div></div>';

    var map_collapse = '<a class="btn btn-default collapse-expand" role="button" data-toggle="collapse" href="#map' + hotel_id + '" aria-expanded="false" aria-controls="details' + hotel_id + '">Map</a>';
    var map_div = '<div class="collapse" id="map' + hotel_id + '"><div class="events">map</div></div>';
    var item_info = '';
    var item_content = '<div class="col-lg-6 col-md-6 col-sm-6"><div class="hotel_content">' + item_name + '<span class="glyphicon glyphicon-map-marker"></span><span>' + item.address + '</span></div>' + detail_collapse + '' + map_collapse + '</div>';

    newhtml = '<li class="hotel-row ' + j + '" data-price="' + hotel_price + '" data-best-price="' + best_price + '">' + thumbnail_div + '' + item_content + '<div class="price-title"><h3>' + original_price + '<span>S$' + hotel_price + '</span><span>/per night</span></h3><button type="submit" class="btn green-btn">Enquiry</button></div><div class="clear"></div>' + item_details + '' + map_div + '</li>'
    return newhtml;
}