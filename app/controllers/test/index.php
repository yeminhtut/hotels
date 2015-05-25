<?php

// SETTINGS
$PID = '6501709';
// Hotels.com's ID
$CID = '1702763';
// Hotelopia's ID
//$CID = '1398451';
// Secret Key
$API_KEY = '0090734772bfede66ef203453ef93f21a93afc4686bf6d83a2c0183ae6b623f98858867c75555382ab12b7dfcb38dee838bcee49e3da3703afa5a182399600502f/237a2cb222316bc713e979b85dbf03a56cb7491b0f28f68ba262511518e6fd01fda93834d9aa0358c50d483670832bcb428d8163f1f7d7f1be1e219b4d733701';
// Filters
$filters = '&low-price=1';

// Product Catalogue Search
$url = 'https://product-search.api.cj.com/v2/product-search?website-id='.$PID.'&advertiser-ids='.$CID.$filters;
// Advertiser Search
//$url = 'https://advertiser-lookup.api.cj.com/v3/advertiser-lookup?advertiser-ids=joined';
// Link Search
//$url = 'https://linksearch.api.cj.com/v2/link-search?website-id='.$PID.'&advertiser-ids='.$CID;

//  Expedia - API KEY is hardcoded
$url = 'http://ews.expedia.com/wsapi/rest/hotel/v1/search?regionids=2114,9779&key=2724F7DF-509C-4275-B5B9-B003A1657507';

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/xml'));
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
$response = curl_exec($ch);
curl_close($ch);

print_r($response);
die();

$response = simplexml_load_string($response);

echo '<pre>';
print_r($response);
die();
?>