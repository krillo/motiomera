<?php

require $_SERVER["DOCUMENT_ROOT"] . "/php/init.php";

Security::demand(ADMIN);

$_SESSION['isLoggedIn'] = true;
$_SESSION['user'] = $USER->getANamn();

header("location: " . $_POST['return_url']);
die;