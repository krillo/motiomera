<?php
	include $_SERVER["DOCUMENT_ROOT"]  . "/php/init.php";
	Security::demand(USER);
	$smarty = new MMSmarty();

  /*
	$tabs = new TabBox("foretag", 590, null);
	if (strtotime($USER->getForetag()->getSlutdatum()) > time())
	{
		$tabs->addTab("Pågående företagstävling", "company_contest");
	}
	$tabs->addTab("All time high", "company_halloffame");

	$smarty->assign("tabs", $tabs);
*/
	$smarty->assign("pagetitle", "Företagstävling");
	$smarty->display("foretagstavling.tpl");
?>
