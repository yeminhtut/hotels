<div ng-controller="hotelsCtrl">
  <div id="image" style="background:#FFF;height:400px;width:100%;text-align:center;" ng-hide="hotels.length">
    <img src="http://localhost/hotels/web/img/ajax-loader.gif" height="14px;" width="256px;" style="margin-top:200px;">
  </div>
  <ul>    
      <li class="hotel-row 16" data-price="99.19" data-best-price="0" ng-repeat="hotel in hotels">
        <div class="col-lg-4 col-md-4 col-sm-4" style="padding-left:0px;">
          <div class="img_list">
            <img ng-src="{{hotel.image_details.prefix}}/1{{hotel.image_details.suffix}}" width="180px;" height="120px" onerror="imgError(this);">
          </div>
        </div>
        <div class="col-lg-6 col-md-6 col-sm-6">
          <div class="hotel_content"><h3 class="link-title">{{hotel.name}}</h3>
            <div id="rating">
              <span class="glyphicon glyphicon-star" aria-hidden="true"></span>
              <span class="glyphicon glyphicon-star" aria-hidden="true"></span>
              <span class="glyphicon glyphicon-star" aria-hidden="true"></span>
            </div>
            <span class="glyphicon glyphicon-map-marker"></span><span>{{hotel.address}}</span>
          </div>
          <div ng-controller="TabController as tab">
              <ul class="tab-list">
                <li ng-class="{ active: tab.isSet(1) }" class="btn btn-default tab-details-item content-hide"><a href ng-click="tab.setTab(1)">Details</a></li>    
                <li ng-class="{ active: tab.isSet(2) }"class="btn btn-default tab-details-item content-hide"><a href ng-click="tab.setTab(2)">Map</a></li>
                <li ng-class="{ active: tab.isSet(3) }"class="btn btn-default tab-details-item content-hide"><a href ng-click="tab.setTab(3)">View more rates</a></li>
              </ul>
              <div ng-show="tab.isSet(1)">
                <h4>Description</h4>
                <div>description</div>
              </div>
              <div ng-show="tab.isSet(2)">
                <h4>Map</h4>
                <div>Map</div>
              </div>
              <div ng-show="tab.isSet(3)">
                <h4>More Rates</h4>
                <table class="table">
                  <thead><tr><th>Room Type</th><th>Rate</th><th></th></tr></thead>
                  <tbody>
                    <tr ng-repeat="package in hotel.rates.packages"><td>{{package.normalizedRoomDescription}}</td><td>{{package.chargeableRate | currency}}</td></tr>
                  </tbody>
              </table> 
              </div>
          </div>          
            
        </div>
        <div class="price-title">
          <h3><span>S${{hotel.rates.packages[0].chargeableRate}}</span><span>/per night</span></h3>
          <a class="btn green-btn">Enquiry</a>
        </div>
        <div class="clear" style="clear:both;"></div>       
      </li>    
  </ul>
</div>
