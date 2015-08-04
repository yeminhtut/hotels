(function() {
  var app = angular.module('gemStore', []);

  

  app.controller('StoreController', function(){
    this.hotels = hotels;
    this.products = gems;
  });

  app.controller('TabController', function(){
    this.tab = 1;

    this.setTab = function(newValue){
      this.tab = newValue;
    };

    this.isSet = function(tabName){
      return this.tab === tabName;
    };
  });

  var gems = [
    {
      name: 'Azurite',
      description: "Some gems have hidden qualities beyond their luster, beyond their shine... Azurite is one of those gems.",
      shine: 8,
      price: 110.50,
      rarity: 7,
      color: '#CCC',
      faces: 14,
      images: [
        "images/gem-02.gif",
        "images/gem-05.gif",
        "images/gem-09.gif"
      ],
      reviews: [{
        stars: 5,
        body: "I love this gem!",
        author: "joe@example.org",
        createdOn: 1397490980837
      }, {
        stars: 1,
        body: "This gem sucks.",
        author: "tim@example.org",
        createdOn: 1397490980837
      }]
    },
    {
      name: 'Bloodstone',
      description: "Origin of the Bloodstone is unknown, hence its low value. It has a very high shine and 12 sides, however.",
      shine: 9,
      price: 22.90,
      rarity: 6,
      color: '#EEE',
      faces: 12,
      images: [
        "images/gem-01.gif",
        "images/gem-03.gif",
        "images/gem-04.gif",
      ],
      reviews: [{
        stars: 3,
        body: "I think this gem was just OK, could honestly use more shine, IMO.",
        author: "JimmyDean@example.org",
        createdOn: 1397490980837
      }, {
        stars: 4,
        body: "Any gem with 12 faces is for me!",
        author: "gemsRock@example.org",
        createdOn: 1397490980837
      }]
    },
    {
      name: 'Zircon',
      description: "Zircon is our most coveted and sought after gem. You will pay much to be the proud owner of this gorgeous and high shine gem.",
      shine: 70,
      price: 1100,
      rarity: 2,
      color: '#000',
      faces: 6,
      images: [
        "images/gem-06.gif",
        "images/gem-07.gif",
        "images/gem-09.gif"
      ],
      reviews: [{
        stars: 1,
        body: "This gem is WAY too expensive for its rarity value.",
        author: "turtleguyy@example.org",
        createdOn: 1397490980837
      }, {
        stars: 1,
        body: "BBW: High Shine != High Quality.",
        author: "LouisW407@example.org",
        createdOn: 1397490980837
      }, {
        stars: 1,
        body: "Don't waste your rubles!",
        author: "nat@example.org",
        createdOn: 1397490980837
      }]
    }
  ];

  var hotels = [
                {"address":"No.12,Pho Sein Road,Tamwe Township",
                "approximate_cost":0,
                "id":["4a37ce15-064c-4851-79e8-35906bf0f1c5","4a37ce15-064c-4851-79e8-35906bf0f1c5"],
                "image_details":{"count":5,"prefix":"https:\/\/s3-ap-southeast-1.amazonaws.com\/zumata\/assets\/hotels\/2.0\/4a37ce15-064c-4851-79e8-35906bf0f1c5\/images","suffix":".jpg"},
                "latitude":16.8015,
                "longitude":96.1696,
                "name":"Best Western Green Hill Hotel",
                "rating":3,"trip_advisor_rating":4,
                "trip_advisor_review_count":86,

                "rates":{
                  "packages":[
                              {"key":"8e3c13dff3b20984",
                                "roomRate":151.78,
                                "marketRate":151.78,
                                "chargeableRate":151.78,
                                "deals":[""],
                                "roomDescription":"DOUBLE DELUXE - BED AND BREAKFAST",
                                "normalizedRoomDescription":"Deluxe Room Double Bed",
                                "additionalInfo":{"food":2},
                                "supplierName":"11",
                                "includesFood":true,
                                "partnerType":"1"},
                              {"key":"8e3c13df4be2368d",
                                "roomRate":153.23,
                                "marketRate":153.23,
                                "chargeableRate":153.23,
                                "deals":[""],
                                "roomDescription":"Single room standard H - RB",
                                "normalizedRoomDescription":"Standard Room Single Bed",
                                "additionalInfo":{"food":2},
                                "supplierName":"5",
                                "includesFood":true,"partnerType":"1"},
                              {"key":"8e3c13dfb3c18425",
                                "roomRate":190.38,"marketRate":190.38,
                                "chargeableRate":190.38,
                                "deals":[""],
                                "roomDescription":"Deluxe Room, 2 Single Beds, Non Smoking - Advanced Purchase - Full Breakfast",
                                "normalizedRoomDescription":"Deluxe Room 2 Single Beds","additionalInfo":{"food":2},
                                "supplierName":"1",
                                "includesFood":true,
                                "partnerType":"1"},
                              {"key":"8e3c13dfb7e97561","roomRate":257.12,"marketRate":257.12,
                                "chargeableRate":257.12,"deals":[""],
                                "roomDescription":"Premier Room, 1 Double Bed, Non Smoking - Advanced Purchase - Full Breakfast",
                                "normalizedRoomDescription":"Premier Room Double Bed",
                                "additionalInfo":{"food":2},"supplierName":"1",
                                "includesFood":true,"partnerType":"1"},
                              ],
                              "compRates":[{"price":125.61,"provider":"Expedia"}]
                            }
                          },
                  {"address":"No. 40, Natmauk Rd, Tamwe Towship","approximate_cost":0,
                  "id":["7f235f3b-25f2-49e3-5ce4-dc307f78a7a2","7f235f3b-25f2-49e3-5ce4-dc307f78a7a2"],
                  "image_details":{"count":47,"prefix":"https:\/\/s3-ap-southeast-1.amazonaws.com\/zumata\/assets\/hotels\/2.0\/7f235f3b-25f2-49e3-5ce4-dc307f78a7a2\/images","suffix":".jpg"},
                  "latitude":16.800362,
                  "longitude":96.16895,
                  "name":"Chatrium Hotel Royal Lake Yangon",
                  "rating":5,"trip_advisor_rating":0,
                  "trip_advisor_review_count":0,
                  "rates":{
                    "packages":[
                      {
                        "key":"8e3c13df5384052a",
                        "roomRate":243.2,
                        "marketRate":243.2,
                        "chargeableRate":243.2,
                        "roomDescription":"Deluxe Room - Breakfast",
                        "normalizedRoomDescription":"Deluxe Room",
                        "additionalInfo":{"food":2},"supplierName":"28",
                        "includesFood":true,
                        "partnerType":"5"
                      },
                      {
                        "key":"8e3c13df42443b3f",
                        "roomRate":249.28,
                        "marketRate":249.28,
                        "chargeableRate":249.28,
                        "deals":[""],
                        "roomDescription":"Double or Twin CITY VIEW-DELUXE - ROOM ONLY","normalizedRoomDescription":"Deluxe Room Double Bed Or Twin Bed With City View",
                        "additionalInfo":{"food":1},
                        "supplierName":"11","includesFood":false,"partnerType":"1"
                      },
                      {
                        "key":"8e3c13df58f5f394",
                        "roomRate":250.66,
                        "marketRate":250.66,
                        "chargeableRate":250.66,
                        "deals":[""],
                        "roomDescription":"Double or Twin CITY VIEW-DELUXE - BED AND BREAKFAST","normalizedRoomDescription":"Deluxe Room Double Bed Or Twin Bed With City View",
                        "additionalInfo":{"food":2},
                        "supplierName":"11",
                        "includesFood":true,
                        "partnerType":"1"
                      },
                      {
                        "key":"8e3c13dfb64e5f29",
                        "roomRate":275.44,
                        "marketRate":275.44,
                        "chargeableRate":275.44,
                        "deals":[""],"roomDescription":"Twin For Sole Use (Deluxe Room) - Breakfast",
                        "normalizedRoomDescription":"Deluxe Room Twin Bed",
                        "additionalInfo":{"food":2},
                        "supplierName":"17",
                        "includesFood":true,
                        "partnerType":"1"
                      }
                    ],
                    "compRates":null}}
                    ];
    //  console.log(hotels);             

  app.controller('HotelController', function($scope, $http){
    function fetch() {
      $http.get("http://localhost/hotels/ajax/search_hotels_new")
           .success(function(response){
            console.log(response);
            $scope.details = response;
          });
    }
    var angular_hotels = fetch();
    console.log(hotels);
  });
  

})();
