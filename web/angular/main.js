(function() {
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

})();