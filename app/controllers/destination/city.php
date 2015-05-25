<?php 
	function _city($location_id='',$location_slug='',$offset=1){		
		
		$hotel_list_arr = get_all_hotels_with_locationID($location_id);
		$number_of_chunks = 20;
		$data = array_chunk($hotel_list_arr, $number_of_chunks);
		$pagecount = count($data);
		//echo $pagecount;
		if (isset($offset) && (is_numeric($offset)))
	    {
	    if ($offset > $pagecount)
	        {
	        die('<span style="color:#FF0000">Error: Page Does Not Exist</span>');
	        }
	    	$i = $offset - 1;
	    }
		else
	    {
	   		$i = 0;
	    }
	 
		$hotel_list_html = make_html_hotel($data[$i]);
		$pagination = make_pagination($pagecount,$location_id,$location_slug);		
		$content['hotel_list'] = $hotel_list_html;
		$content['pagination'] = $pagination;
		$data['pagename']= $location_slug;
		  
		$data['body'][]=View::do_fetch(VIEW_PATH.'destination/index.php',$content);
		View::do_dump(VIEW_PATH.'layouts/layout.php',$data);
	}

function make_pagination($pagecount,$location_id,$location_slug){	
	$html = '';
	$html .= '<ul class="pagination">';
	for ($i = 1; $i <= $pagecount; $i++)
	    {
	    	$html.= '<li><a href="/hotels/destination/'.$location_id.'/'.$location_slug.'/'.$i.'">'.$i.'</a></li>';
	    }
	$html .= '</ul>';
	return $html;
	
}
function get_all_hotels_with_locationID($location_id){
	$dbh = getdbh();
	$statement = "SELECT * FROM `t_property` WHERE `location_id` LIKE '$location_id'";	
	$sql = $dbh->prepare($statement);
    $sql->execute();
    $result = $sql->fetchAll();
    return $result;
	
}
function make_html_hotel($list){
	$html = '';	
	foreach ($list as $list) {
		$name = $list['property_name'];
		$address = $list['address'];
		$thumbnail = make_hotel_thumb($list['image_details']);
		$html.='<div class="col-sm-6 col-md-3" style="height:425px;">
              <div class="thumbnail">
                <img class="img-rounded thumb" src="'.$thumbnail.'">
                <div class="caption text-center">
                  <h3>'.$name.'</h3>
                  <p>'.$address.'</p>
                  <p><a href="#" class="btn btn-info btn-block" role="button">View</a></p>
                </div>
              </div>
            </div>';	
	}
	return $html;	
}

function make_hotel_thumb($image_arr){
	$image_arr = explode(',', $image_arr);
	$count = $image_arr[0];
	$prefix = $image_arr[1];
	$suffix = $image_arr[2];
	$image_name = rand(1,$count);
	$src = $prefix.'/'.$image_name.$suffix;
	return $src;
}