<?php
 /**
  * 13-12-27 Kristian Erendi, Reptilo.se
  */
include $_SERVER["DOCUMENT_ROOT"]  . "/php/init.php";
$smarty = new MMSmarty();
$smarty->assign("pagetitle", "Kommunjakten");
$smarty->display('kommuner.tpl');