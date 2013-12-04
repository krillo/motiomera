<?php
require $_SERVER["DOCUMENT_ROOT"] . "/php/init.php";
Security::demand(ADMIN);
if(!empty($_POST['email'])){
	echo "This is sent via Misc:logEmailSend(). \n See also logfile, /usr/local/motiomera/log/email.log";
  Misc::sendEmail($_POST['email'], $SETTINGS["email"], "Testmail from motiomera (". $SETTINGS["email"].")" , "This is sent via Misc:logEmailSend(). \n See also logfile, /usr/local/motiomera/log/email.log");		
}
header("Location: " . $_SERVER["HTTP_REFERER"]);
?>