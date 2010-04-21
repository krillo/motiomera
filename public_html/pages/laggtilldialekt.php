<?php


include $_SERVER["DOCUMENT_ROOT"]  . "/php/init.php";
Security::demand(USER);

$smarty = new MMSmarty();

$kommun = Kommun::loadById($_GET["kid"]);
$smarty->assign("kommun", $kommun);

$opt_alder = array(""=>"Välj...", "ung"=>"Ung", "gammal"=>"Gammal");
$smarty->assign("opt_alder", $opt_alder);

$opt_kon = array(""=>"Välj...", "man"=>"Man", "kvinna"=>"Kvinna");
$smarty->assign("opt_kon", $opt_kon);


$smarty->display('laggtilldialekt.tpl');

?>