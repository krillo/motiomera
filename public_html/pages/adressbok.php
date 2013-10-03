<?php

include $_SERVER["DOCUMENT_ROOT"]  . "/php/init.php";

Security::demand(USER);

$smarty = new MMSmarty();


$tabs = new TabBox("adressbok", 750, null);
$tabs->addTab("Mina vänner", "kontakter");
$tabs->addTab("Sök medlem", "medlemmar");
$tabs->addTab("Avancerad medlemssökning", "topplista_special");
$tabs->addTab("Nya vänner / Bjud in", "forfragningar", true);

if(isset($_GET["tab"]))
	$tabs->setSelected($_GET["tab"]);

$smarty->assign("tabs", $tabs);



$smarty->display('adressbok.tpl');


?>