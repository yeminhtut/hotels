<?php 
function _search_days($sort) {
	$_SESSION['search_sort'] = 'days';
	$_SESSION['search_sort_direction'] = $sort;
}