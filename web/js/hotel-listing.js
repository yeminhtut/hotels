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
            //console.log('response count is ' + response_count);
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
    var competitor_provider = '';
    var price = '<h3><span>S$' + hotel_price + '</span><span>/per night</span></h3>';
    var competitor = 0;
    if (comp_price !== null) {
        if (item.rates.compRates[0]['price'] > hotel_price) {
            competitor = item.rates.compRates[0]['price'];
            var competitor_price = '<span class="ori_price">S$' + competitor + '</span>';
            var hotel_price = item.rates.packages[0].chargeableRate;           
            var featured = '<span class="best_deal">Best deal</span>';
            var price = '<h3>' + competitor_price + '<span>S$' + hotel_price + '</span><span>/per night</span></h3>';
        }             
    };

    var image_src = item.image_details.prefix + '/1' + item.image_details.suffix;
    var thumbnail_div = '<div class="col-lg-4 col-md-4 col-sm-4" style="padding-left:0px;">' + featured + '<div class="img_list">\
                        <img width="180" height="120" src="' + image_src + '" onerror="imgError(this);"></div></div>';

    var item_name = '<h3 class="link-title">' + item.name + '</h3>';

    //detail//
    var detail_tabs = '<ul class="tab-list">\
                      <li class="btn btn-default tab-details-item content-hide" data-id="' + hotel_id + '" data-link="details' + hotel_id + '" onclick="show_hide_fn(this)">\
                      Details</li>\
                      <li class="btn btn-default tab-details-item content-hide" data-id="' + hotel_id + '" data-link="map' + hotel_id + '" onclick="show_hide_fn(this)">Map</li>\
                      <li class="btn btn-default tab-details-item content-hide" data-id="' + hotel_id + '" data-link="rates' + hotel_id + '" onclick="show_hide_fn(this)">View more rates</li>\
                      </ul>';
    var amenities_html = '<ul>';
    
    var amenities = item.amenities;
    for (var key in amenities) {
      if (amenities.hasOwnProperty(key)) {        
        if (amenities[key] == true) {
            key = spacey(key);
            amenities_html += '<li><span class="glyphicon glyphicon-ok-circle"></span><span class="amenities">'+key+'</span></li>';
        };
      }
    }
    
    amenities_html += '</ul>'

    var item_details = '<div class="tab-content" id="details' + hotel_id + '">\
                        <div class="col-md-8">' + item.description +'</div>\
                        <div class="col-md-4">' + amenities_html +'</div><div class="clear"></div></div>';

   
    var map_div = '<div class="tab-content" id="map' + hotel_id + '">map</div>';

    //room rates//
    var room_items = item.rates.packages;
    
    var ratehtml = '<thead><tr><th>Room Type</th><th>Rate</th><th></th></tr></thead><tbody>';
    $.each(room_items, function(i, room_items) {                    
                        ratehtml += '<tr><td>'+room_items.roomDescription+'</td><td>S$'+room_items.chargeableRate+'</td><td class="price_td">\
                                    <a href="http://localhost/hotels/property/booking/'+room_items.key+'" class="btn btn-danger" data-roomKey="'+room_items.key+'" target="_blank">Go</a></td></tr>';              
                });
    ratehtml += '</tbody>';

    var rates_div = '<div class="tab-content" id="rates' + hotel_id + '"><table class="table">'+ratehtml+'</table></div>';
    //star rating//
    var rating = '';
    for (i = 0; i < item.rating; i++) { 
        rating += '<span class="glyphicon glyphicon-star" aria-hidden="true"></span>';
    }
    //price//
    var item_price_div = '<div class="price-title">'+price+'<a  class="btn green-btn">Enquiry</a></div>';

    var item_content = '<div class="col-lg-6 col-md-6 col-sm-6"><div class="hotel_content">' + item_name + '<div id="rating">'+rating+'</div><span class="glyphicon glyphicon-map-marker"></span>\
                        <span>' + item.address + '</span></div>' + detail_tabs + '</div>';
    newhtml = '<li class="hotel-row ' + j + '" data-price="' + hotel_price + '" data-best-price="' + competitor + '">' + thumbnail_div + '' + item_content + ''+item_price_div+'<div class="clear"></div>'+rates_div+'' + item_details + '' + map_div + '\
                <div id="' + hotel_id + 'panel" style="margin-top:10px;padding:10px;"></div></li>'
    return newhtml;
}

function spacey(str) {  
    return str.substring(0, 1) +
           str.substring(1).replace(/([a-z])?([A-Z])/g, "$1 $2");
}

function show_hide_fn(element) {
    var target = $(element).attr("data-link");
    var hotel_id = $(element).attr("data-id")
    var content = $("#" + target).html();
    var show_panel = hotel_id + 'panel';
    if ($(element).hasClass('content-hide')) {
        $('li[ data-id=' + hotel_id + ']').removeClass('content-show').addClass('content-hide');
        $(element).removeClass('content-hide').addClass('content-show');
        $("#" + show_panel).html(content);
    } else if ($(element).hasClass('content-show')) {
        $(element).removeClass('content-show').addClass('content-hide');
        $("#" + show_panel).empty();
    }
}

function close_fn() {
    $("#hotel_detail_content").empty();
}



