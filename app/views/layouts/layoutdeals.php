<?php 
$user=User::getUser(); //always retrieve logged in user, for convenience
$fb_login = false;
if(isset($_SESSION['access_token']))
	$fb_login = true;
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xmlns:fb="http://www.facebook.com/2008/fbml" xml:lang="en" lang="en">
<!doctype html>
<!--[if lt IE 7]> <html class="no-js lt-ie9 lt-ie8 lt-ie7" lang="en"> <![endif]-->
<!--[if IE 7]>    <html class="no-js lt-ie9 lt-ie8" lang="en"> <![endif]-->
<!--[if IE 8]>    <html class="no-js lt-ie9" lang="en"> <![endif]-->
<!--[if gt IE 8]><!--> <html class="no-js" lang="en"> <!--<![endif]-->

<head>
<title><?php echo isset($page_title)?$page_title:'Tourpackages SG'?></title>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
<meta name="description" content="<?php echo isset($meta_description)?$meta_description:'' ?>">
<meta name="keywords" content="<?php echo isset($meta_keywords)?$meta_keywords:'tour packages, flights, cheapest flights, hotels, travel agency, singapore travel, vacation packages, deals'?>">
<meta name="author" content="Travelogy.com Pte Ltd - http://travelogy.com">
<link rel="shortcut icon" type="image/x-icon" href="/favicon.ico">

<!-- css -->
<link rel="stylesheet" href="/css/style.css">
<link rel="stylesheet" href="/css/custom.css">
<link rel="stylesheet" href="/css/bootstrapdeals.css">
	
<!--<link rel="stylesheet" href="css/bootstrap-responsive.css">-->
<meta name="robots" content="/robots.txt">
<meta name="humans" content="/humans.txt">

<!-- Place favicon.ico and apple-touch-icon.png in root directory -->
<link rel="shortcut icon" href="/img/favicon.png" type="image/x-icon">
<link rel="apple-touch-icon" href="/img/apple-touch-icon.png">

<?php if(isset($og_image)) { ?>
<meta property="og:image" content="<?php echo $og_image ?>"/>
<?php } ?>

<!--<meta name="viewport" content="width=device-width">-->

<!--[if lt IE 9]>
<script src="http://ie7-js.googlecode.com/svn/version/2.1(beta4)/IE9.js"></script>
<![endif]-->

<link rel="stylesheet" href="/css/slider.css" type="text/css" media="screen">
<script src="/js/slider/jquery-1.7.1.min.js"></script>

<script type="text/javascript">

  var _gaq = _gaq || [];
  _gaq.push(['_setAccount', 'UA-18745286-11']);
  _gaq.push(['_trackPageview']);

  (function() {
    var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
    ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
    var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
  })();

</script>

<?php echo (isset($head) && is_array($head)) ? implode("\n",$head) : ''?>
</head>
<body>
<div id="fb-root"></div>
<script>
window.fbAsyncInit = function() {
// init the FB JS SDK
FB.init({
  appId      : '185725878230849', // App ID from the App Dashboard
  channelUrl : '//<?=$_SERVER['HTTP_HOST']?>/channel.php', // Channel File for x-domain communication
      status     : true, // check the login status upon init?
      cookie     : true, // set sessions cookies to allow your server to access the session?
      xfbml      : true  // parse XFBML tags on this page?
    });

    // Additional initialization code such as adding Event Listeners goes here  
  };

  // Load the SDK's source Asynchronously
  (function(d){
     var js, id = 'facebook-jssdk', ref = d.getElementsByTagName('script')[0];
     if (d.getElementById(id)) {return;}
     js = d.createElement('script'); js.id = id; js.async = true;
     js.src = "//connect.facebook.net/en_US/all.js";
     ref.parentNode.insertBefore(js, ref);
   }(document));
</script>

<div id="app_header">
  <div id="">
    <div class="container" id="nav-wrapper">
    	<a href="<?php echo WEB_DOMAIN;?>" class="nav-btn" alt="top" id="header_logo" style="text-decoration: none">
				<div><font id="logoheader">TourPackages.com.sg</font></div>
			</a>
			
      <div class="header_2">
       <ul>
      <li onmouseover="hide_all()"><a id="" class="nav_a" href="/package-deals"><img src="/img/icons/icons-deal-1.png" class="nav_icon" alt="">Travel Deals</a></li>
			<li  id="link_home" onmouseover="show_destination()"><a class="active nav_a" href="/destination"><img src="/img/icons/icons-destination-1.png" class="nav_icon" alt="">Destinations</a>
			  <div class="dropdown_wrapper_div" id="dropdown_destination" style="display: none; " onmouseout="hide_all()">
				<div class="" id="login_form" style="display:block">
				  <h4>Top countries</h4>
				  <div class="drop_countries">
					<p>Asia</p>
					<ul class="counties">
						<li><a href="/asia/china">China</a></li>
						<li><a href="/asia/hong-kong">Hong Kong</a></li>
						<li><a href="/asia/india">India</a></li>
						<li><a href="/asia/indonesia">Indonesia</a></li>
						<li><a href="/asia/japan">Japan</a></li>
					  <li><a href="/asia/malaysia">Malaysia</a></li>
					  <li><a href="/asia/philippines">Philippines</a></li>
					  <li><a href="/asia/south-korea">South Korea</a></li>
					  <li><a href="/asia/taiwan">Taiwan</a></li>
					  <li><a href="/asia/thailand">Thailand</a></li>
					  <li><a href="/asia/vietnam">Vietnam</a></li>
					</ul>
				  </div>
				  <div class="drop_countries">
					<p>Oceania</p>
					<ul class="counties">
					  <li><a href="/australia-and-pacific/australia">Australia</a></li>					  
					  <li><a href="/europe/europe">Europe</a></li>
					  <li style="width: 100px"><a href="/australia-and-pacific/new-zealand">New Zealand</a></li>
					</ul>
				  </div>
				  <div class="show_all_div"> <a href="/destination">Show All</a> </div>
				</div>
			  </div>
			  <!-- end of dropdown_wrapper_div --> 
			  
			</li>
			<!-- <li onmouseover="hide_all()"><a id="" class="nav_a" href="/promotions"><img src="/img/icons/icons-discount-1.png" class="nav_icon" alt="">Promotions</a></li> -->
			<li onmouseover="hide_all()"><a id="" class="nav_a" href="/directory"><img src="/img/icons/icons-directory-1.png" class="nav_icon" alt="">Travel Agencies</a></li>
			<!--<li onmouseover="hide_all()"><a id="" class="nav_a" href="/user/shortlist"><img src="/img/icons/icons-travel-1.jpg" class="nav_icon" alt="">Shortlist</a></li>-->
			
			</ul>

      </div>
      <!-- end of header_2 --> 
      
      <div style="clear: both;"></div>
      <div style="margin-top: -5px;"><font id="logosubheader">Find Singapore travel promotions</font></div>
      
    </div>
  </div>
</div>

<?php echo (isset($body) && is_array($body)) ? implode("\n",$body) : ''?>

<?php if (isset($user) && $user) { ?>
	<!-- logged in -->
<?php } else { ?>
<div class="signup_form">
  	<div class="container">
	        <form id="publisher_signup_form" action="/user/process_register" method="post">
            
                <ul class="signup_form_ul">
                  			<li>
                            <div style="line-height: 18px; text-align: center;">
                            	<font style="font-weight: bold; font-size: 20px;">Travel at Half Price</font><br />
                            	<font style="font-size: 12px;">Receive great promotions from us</font>
                            </div>
                        </li>
                                          
                        <li>
                            <input type="text" name="email" id="email_address" placeholder="Enter your email address">
                        </li>
                        <div class="notification-email notification">
                            <p class="email-error">
                                <span id="email-error" class="error_text"></span>
                            </p>
                        </div>
                  
                        <li class="">
                            <button id="signup_button" class="sign_up red_button" type="submit">Sign up for free</button>
                        </li>
               
               			<li class="">
                        	or
                        </li>
                        
                        <li class="" style="margin-right: 0px">
                        	<a href="javascript:facebook_login();">
                        		<img class="footer_nav_facebook" src="/img/icons/login-facebook-2.png" alt="" />
                        	</a>
                        </li>
                        
             
                    
                </ul>
                
                
            </form> 
                   <div class="clearfix"></div>    
            </div>        
		</div>
<?php } ?>
    


<div class="main_con">

</div> <!-- end of main_con-->

<div id="homepage_footer" <?php echo (isset($user) && $user)?'style="margin: 10px 0 0;"':''?>>
  <div class="footer_container container">
    <ul>
      <li><a href="/about">About</a></li>
      <!-- <li><a href="/faq">FAQ</a></li> -->
      <li><a href="/terms">Terms and Conditions</a></li>
      <li><a href="/contact">Contact</a></li>
 
    </ul>
   <div class="social_icons">
          
              <a class="facebook" href="https://www.facebook.com/TourPackages.com.sg" target="_blank">f</a> <a class="twitter" href="https://twitter.com/TripZillaTravel" target="_blank">l</a> <!-- <a class="google" href="#">g</a> <a class="linkedin" href="#">i</a> --> </div>
    
    
    
    
 <div class="footer_copyright">
      <div class="footer_copyright">© 2012 Travelogy.com Pte. Ltd. All rights reserved.</div>
      <div class="clearfix"></div>
    </div>
    
    <div class="clear"></div>
    <!--<div id="footer_contact">Any enquiries? Email to <a href="mailto:hello@pyrks.com">hello@pyrks.com</a></div>--> 
  </div>
</div>

<link href="http://ajax.googleapis.com/ajax/libs/jqueryui/1.8/themes/base/jquery-ui.css" rel="stylesheet" type="text/css"/>
<script src="/js/jquery-ui.min.js"></script>
<script src="/js/slider/superfish.js"></script>
<script src="/js/slider/script.js"></script>
<script src="/js/slider/jquery.responsivemenu.js"></script>
<script src="/js/slider/slides.min.jquery.js"></script>
<script src="/js/slider/jquery.easing.1.3.js"></script>
<script src="/js/common.js"></script>
<script src="/js/plugins.js"></script> 
<script src="/js/script.js"></script> 
<script src="/js/bootstrap.js"></script>
<script src="/js/jshashtable-2.1_src.js"></script>
<script src="/js/jquery.numberformatter-1.2.3.js"></script>
<script src="/js/tmpl.js"></script>
<script src="/js/jquery.dependClass-0.1.js"></script>
<script src="/js/draggable-0.1.js"></script>
<script src="/js/jquery.slider.js"></script>
<!-- end scripts--> 

<?php echo (isset($foot) && is_array($foot)) ? implode("\n",$foot) : ''?>
</body>
</html>
