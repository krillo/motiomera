<?php

require_once($_SERVER["DOCUMENT_ROOT"]."/php/init.php");

Security::demand(USER);

$smarty = new MMSmarty();
$smarty->assign("pagetitle", "Väkommen till en ny kommun");

$rutt = new Rutt($USER);
$rutter = $rutt->getRutt();
/*if(isset($rutter[$rutt->getCurrentIndex()+1]))
	$urlHandler->redirect("Medlem", URL_VIEW_OWN);
*/

$kommun = $rutt->getCurrentKommun();
$smarty->assign("kommun",$kommun);

$kommunvapen = $kommun->getKommunvapen();
$smarty->assign("kommunvapen", $kommunvapen);

$kommunbilder = $kommun->listKommunbilder();
$kommunbild = current($kommunbilder);
$smarty->assign("kommunbilder", $kommunbild);



$smarty->display('nykommun.tpl');

?>