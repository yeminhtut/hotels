<?php 
$user=User::getUser(); //always retrieve logged in user, for convenience
?>
<ul>
  <li><a title="Package Deals" href="/package-deals"><img src="/img/icons/icons-deal-1.png" alt=""></a></li>
	<li id="sidebar_destination">
    <a title="Destinations" href="/destination"><img src="/img/icons/icons-destination-1.png" alt=""></a>
  </li>
	<!-- <li><a title="Promotions" href="/promotions"><img src="/img/icons/icons-discount-1.png" alt=""></a></li>	-->
	<li><a title="Travel Agencies" href="/directory"><img src="/img/icons/icons-directory-1.png" alt=""></a></li>	
	<!--<li><a title="Shortlist" href="/user/shortlist"><img src="/img/icons/icons-shortlist-1.jpg" alt=""></a></li>
	<li><a title="<?php// echo (isset($user) && $user)?'My Profile':'Login' ?>" href="/user/profile"><img src="/img/icons/icons-user-1.png" alt=""></a></li>	-->	
</ul>