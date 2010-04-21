<?
/**
 *	Called before internal mails are sent to members (using freestring)
 *
 */
include $_SERVER["DOCUMENT_ROOT"]  . "/php/init.php";

Security::demand(USER);
if (isset($_POST['freestring'])) {

	echo Medlem::verifyValidUsername($_POST['freestring']);

}

?>