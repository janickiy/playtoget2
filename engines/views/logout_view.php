<?php

defined('PLAYTOGET') || exit('Playtoget: access denied!');

// authorization
Auth::authorization();

// clear cookies and sessions
Auth::Logout();

$redirect = "http://".$_SERVER['SERVER_NAME'];
	
header("Location: ".$redirect."");

exit();