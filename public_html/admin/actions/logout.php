<?php

global $ADMIN;
require $_SERVER["DOCUMENT_ROOT"] . "/php/init.php";

$ADMIN->loggaut();

header("Location: /admin/");

?>