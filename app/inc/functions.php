<?php
function myUrl($url='',$fullurl=false) {
  $s=$fullurl ? WEB_DOMAIN : '';
  $s.=WEB_FOLDER.$url;
  return $s;
}

function redirect($url,$alertmsg='') {
  if ($alertmsg)
    addjAlert($alertmsg,$url);
  header('Location: '.myUrl($url));
  exit;
}

function require_login() {
  if (!isset($_SESSION['authuid']))
    redirect('main/login');
}

//session must have started
//$uri indicates which uri will activate the alert (substring check)
function addjAlert($msg,$uri='') {
  if ($msg) {
    $s="alert(\"$msg\");";
    $_SESSION['jAlert'][]=array($uri,$s);
  }
}

function getjAlert() {
  if (!isset($_SESSION['jAlert']) || !$_SESSION['jAlert'])
    return '';
  $pageuri=$_SERVER['REQUEST_URI'];
  $current=null;
  $remainder=null;
  foreach ($_SESSION['jAlert'] as $x) {
    $uri=$x[0];
    if (!$uri || strpos($pageuri,$uri)!==false)
      $current[]=$x[1];
    else
      $remainder[]=$x;
  }
  if ($current) {
    if ($remainder)
      $_SESSION['jAlert']=$remainder;
    else
      unset($_SESSION['jAlert']);
    return '<script type="text/javascript">'."\n".implode("\n",$current)."\n</script>\n";
  }
  return '';
}

function makeslug($str) {
	$str = strtolower(trim($str));
	$str = preg_replace('/[\'()\.]/','',$str);
	$str = preg_replace('/[^@0-9-\.\p{L}]/u', '-', $str);
	$str = preg_replace('/-+/', '-', $str);
	$str = trim($str,'-');
	return $str;
}

function itineraryExists($url) {
	$temp=explode("/",$url);
	$enc_url='';
	foreach($temp as $key=>$val){
		if($key>2)
			$val=rawurlencode($val); // encode spaces
		if($key>0)
			$enc_url.='/'; // add slashes
		$enc_url.=$val;
	}

	$curl = curl_init($enc_url);
	curl_setopt($curl, CURLOPT_NOBODY, true);
	$result = curl_exec($curl);
	$ret = false;
	if ($result !== false) {
		$statusCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
		if ($statusCode == 200) {
			$ret = true;
		}
	}
	curl_close($curl);
	return $ret;
}

function url_title($str, $separator = 'dash', $lowercase = TRUE)
{
	if ($separator == 'dash')
	{
		$search		= '_';
		$replace	= '-';
	}
	else
	{
		$search		= '-';
		$replace	= '_';
	}

	$trans = array(
			'&\#\d+?;'				=> '',
			'&\S+?;'				=> '',
			'\s+'					=> $replace,
			'[^a-z0-9\-\._]'		=> '',
			$replace.'+'			=> $replace,
			$replace.'$'			=> $replace,
			'^'.$replace			=> $replace,
			'\.+$'					=> ''
	);

	$str = strip_tags($str);

	foreach ($trans as $key => $val)
	{
		$str = preg_replace("#".$key."#i", $val, $str);
	}

	if ($lowercase === TRUE)
	{
		$str = strtolower($str);
	}

	return trim(stripslashes($str));
}

// SHA512 Hash
function myHash($content, $salt='')
{
	$content = hash('sha512', $content.$salt);
	return $content;
}

function valid_email($address)
{
	return ( ! preg_match("/^([a-z0-9\+_\-]+)(\.[a-z0-9\+_\-]+)*@([a-z0-9\-]+\.)+[a-z]{2,6}$/ix", $address)) ? FALSE : TRUE;
}

function is_robot()
{
	$ua = strtolower($_SERVER['HTTP_USER_AGENT']);
	
	// From CodeIngiter config/user_agents
	$robots = array(
		'googlebot'			=> 'Googlebot',
		'msnbot'			=> 'MSNBot',
		'slurp'				=> 'Inktomi Slurp',
		'yahoo'				=> 'Yahoo',
		'askjeeves'			=> 'AskJeeves',
		'fastcrawler'		=> 'FastCrawler',
		'infoseek'			=> 'InfoSeek Robot 1.0',
		'lycos'				=> 'Lycos'
	);
	
	foreach ($robots as $key => $val) {
		if (preg_match("|".preg_quote($key)."|i", $ua))
		{
			return TRUE;
		}
	}

	return FALSE;
}
