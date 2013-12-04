<?php
/**
 * @author Mattias Borén, The Farm
 *
 * Blocks members on request
 */

include $_SERVER["DOCUMENT_ROOT"] . "/php/init.php";

Security::demand(USER);
if (isset($_GET["bmid"])) {

	$banMember = Medlem::loadById($_GET['bmid']);
	
	if(!empty($banMember)) {

	MedlemsBlockering::blockeraMedlem($USER->getId(), $banMember->getId());
	$urlHandler->redirect("Medlem", URL_VIEW, $banMember->getId());

	}

}


?>