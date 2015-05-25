<?php
function _index($letter='A',$offset='') {
  $data['pagename']='Tour Packages';
  $company = new Company(); 
  $letter = strtolower($letter);
  if($letter=="_HEX_")
    $param['hex']=true;
  else
    $param['alphabet']=$letter;  
  $param['category']=1;

  $company_ids=Company::retrieve_company_by_alphabetical($param, $offset, $GLOBALS['pagination']['per_page']);  
  $total_company=Company::retrieve_company_by_alphabetical($param, false, false, true);

  $content['featured_companies']= make_html_featured_company($company);
  $content['make_html_nonfeatured_company']=make_html_nonfeatured_company($company_ids);

  //for pagination
  $content['total_company']=$total_company;
  $pagination_url = myUrl('directory/'.makeslug($letter));
  
  $content['pagination']=pagination::makePagination($offset,$total_company,$pagination_url,$GLOBALS['pagination']);  
  
  $data['body'][]=View::do_fetch(VIEW_PATH.'directory/index.php',$content);
  
  $per_page_title = '';
  if(is_numeric($offset))
  	$per_page_title .= ' - Page '.(($offset / $GLOBALS['pagination']['per_page']) + 1);
  
  $data['page_title'] = 'Travel Agencies Directory, Singapore'.$per_page_title;
  $data['meta_description'] = 'An exhaustive list of travel agencies in Singapore are listed on this directory. Find their tour packages on TourPackages.com.sg'.$per_page_title;
  
  View::do_dump(VIEW_PATH.'layouts/layout.php',$data);
}

function make_html_nonfeatured_company($company_ids=''){
  $html='';
  
  if($company_ids=='')
    $html='<div class="alert alert-warning text-center">We are not able to find any package that meets your search criteria.<br />You may want to refine your search and try again.</div>';
  else{    
      $listing=nonfeatured_company_listing($company_ids);
      $html=nonfeatured_company_html_desktop($listing);    
  }  
  return $html;
}

function nonfeatured_company_listing($company_ids=''){
  $dbh=getdbh();
  $statement="
  SELECT 
  company.id, 
  company.display_name, 
  company.address
  FROM company WHERE company.id IN ($company_ids) 
  ";
  
  $sql=$dbh->prepare($statement);
  $sql->execute();
  $result=$sql->fetchAll(PDO::FETCH_ASSOC);
  
  return $result;
}

function nonfeatured_company_html_desktop($listing=array()){
  if(count($listing)>0){
    $html='';
    foreach($listing as $row){      
      $company = new Company($row['id']);
      $html.='
      <li>
        <div class="name">
          <a href="/directory/review/'.$row['id'].'/'.makeslug($row['display_name']).'"><b>'.$row['display_name'].'</b></a>
        </div>
        <div class="address">
          '.($company->get('address')!=''?'<label>'.implode('<br/>', $company->get('address')).'</label>':'').'
        </div>
      </li>
      ';
    }
  }else
    return false;
  
  return $html;
}

function make_html_featured_company($company){
  $html='';
  $listing = $company->retrieve_many('catid=? AND status=? AND flags=? ORDER BY display_name ASC', array(1, 'approved', 'featured'));
  $html=company_html_desktop($listing);
  return $html;
}
function company_html_desktop($listing){
  $html='';
  if(count($listing)>0){
    foreach($listing as $row){
      
        $company_url = '/directory/review/'.$row->get('id').'/'.makeslug($row->get('display_name'));
      
      $file=new File();
      $file->retrieve_one('reference_id=? and reference_type=? and type=?', array($row->get('id'), 'company', 'image'));
      
      $logo_url=FILES_PATH."no_image.jpg";
      if($file->exists())
        $logo_url=FILES_PATH.$file->get('id').".".$file->get('extension');
      
      $contact = $row->get('contact');
      $contact = ($contact!='' ? '<label class="phone">Tel: '.$contact[0].'</label><br/>' :'');
      $html.='<div class="agency_main">
        <div class="span1 photo" style="border: none;">
          <a href="'.$company_url.'">         
          <img src="'.$logo_url.'" alt="'.$row->get('display_name').'" width="110">
          </a>
        </div>
        <div class="span2 description">
          <a href="'.$company_url.'"><b>'.$row->get('display_name').'</b></a><br/>
          <font class="small_font">
          '.($row->get('address')!=''?''.implode('<br/>', $row->get('address')).'':'').'
          </font>
          <font class="highlight_font"> Packages</font>
          </div>
          </div>
      ';
     
    }
  }else
    return false;
  
  return $html;
}