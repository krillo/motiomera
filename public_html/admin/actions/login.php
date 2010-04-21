<?php

require_once($_SERVER["DOCUMENT_ROOT"]."/php/init.php");
$remember = (isset($_POST["autologin"])) ? true : false;
$adminstatus = Admin::loggaIn($_POST["username"],$_POST["password"], $remember);

if($adminstatus){
	if(!empty($_GET["redirect"]))
		header("Location: ".$_GET["redirect"]);
	else
		header("Location: /admin/");
}else{
	throw new UserException("Felaktigt inlogg", "Användarnamnet eller lösenordet var felaktigt!", "/pages/toolbox.php", "Försök igen");
}

?>