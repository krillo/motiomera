<?php
session_start();

function mm_login($username, $password) {
	$_GET["mode"] = "login";
	$_POST["username"] = $username;
	$_POST["password"] = $password;
	$_POST["sid"] = "2858a88b9ce6714517833b06c3f53ba2";
	include("forum/ucp.php");

}

//mm_login("oskar", "qwerty");


define('IN_PHPBB', true);
define('PHPBB_ROOT_PATH', "forum/");
define('IN_LOGIN', true);
$phpbb_root_path = (defined('PHPBB_ROOT_PATH')) ? PHPBB_ROOT_PATH : './';
$phpEx = substr(strrchr(__FILE__, '.'), 1);
require($phpbb_root_path . 'common.' . $phpEx);
require($phpbb_root_path . 'includes/functions_module.' . $phpEx);
require($phpbb_root_path . 'includes/functions_user.' . $phpEx);


	$_GET["mode"] = "login";
	$_POST["username"] = $username;
	$_POST["password"] = $password;
	$_POST["sid"] = "2858a88b9ce6714517833b06c3f53ba2";

$username = "oskar";
$pass = "qwerty";
					$user->session_id = "2858a88b9ce6714517833b06c3f53ba2"; 
print_r($auth->login($username, $pass));

?>
