<?php 
function _search_price($sort) {
	$_SESSION['search_sort'] = 'price';
	$_SESSION['search_sort_direction'] = $sort;
}