<?php

require $_SERVER["DOCUMENT_ROOT"] . "/php/init.php";

$to = $SETTINGS["rapport_mail"];
$from = $_POST["user_epost"];
$subject = "Motiomera anmälan";
$message = "Av: ".utf8_encode($_POST["user_namn"]);
$message .= " (".$_POST["user_id"].")\n\n";
$message .= "E-post: ".$_POST["user_epost"]."\n\n";
$message .= "Webbläsare: ".$_POST["browser"]."\n\n";
$message .= "Adress ".$_POST["sida"]."";
$message .= utf8_encode($_POST["beskrivning"]);

Misc::sendEmail($to, $from, $subject, $message);

echo "OK";

?>