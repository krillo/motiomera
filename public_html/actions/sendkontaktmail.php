<?php

require $_SERVER["DOCUMENT_ROOT"] . "/php/init.php";

$to = $SETTINGS["kontakt"];
$from = $_POST["user_epost"];
$subject = "MotioMera - Kontakta oss";
$message = "Från: ".utf8_encode($_POST["user_namn"]);
$message .= " (".$_POST["user_id"].")\n\n";
$message .= "E-post: ".$_POST["user_epost"]."\n\n";
$message .= "Från sida: ".$_POST["sida"]."\n\n";
$message .= "Webbläsare: ".$_POST["browser"]."\n\n\n\n";
$message .= utf8_encode($_POST["beskrivning"]);

Misc::sendEmail($to, $from, $subject, $message);

echo "OK";

?>