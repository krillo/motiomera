<?php


require $_SERVER["DOCUMENT_ROOT"] . "/php/init.php";

Security::demand(FORETAG);

$FORETAG->generateNycklar($_POST["antal"]);

$urlHandler->redirect("Foretag", URL_EDIT, array(null, 3));

?>