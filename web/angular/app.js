(function() {
  var app = angular.module('HotelBookingApp', []);

  
app.controller('TabController', function(){
    //this.tab = 1;

    this.setTab = function(newValue){
      this.tab = newValue;
    };

    this.isSet = function(tabName){
      return this.tab === tabName;
    };
  });  

//hotel controller
app.controller('hotelsCtrl', function($scope, $http) {
  $http.get("http://localhost/hotels/ajax/search_hotels_new",{ cache: true})
  .success(function (response) {
    console.log(response);
    $scope.hotels = response.hotels;
  }); 
});
  

})();

function imgError(image) {
    image.onerror = "";
    image.src = "/hotels/web/img/default.png";
    return true;
}