<?php
function _show() {
	require(APP_PATH.'/inc/securimage/securimage.php');
	
	$img = new Securimage();
	
	//Change some settings
	$img->image_width = 175;
	$img->image_height = 45;
	$img->perturbation = 0;
	$img->num_lines = 0; // no lines, just the code
	$img->charset = 'abcdefghklmnprstuvwyz23456789';
	
	$img->show(); // alternate use:  $img->show('/path/to/background_image.jpg');
}