<?php

require $_SERVER["DOCUMENT_ROOT"] . "/php/init.php";

Security::demand(ADMIN);

header("location:" . $_GET["return_url"]);

?>