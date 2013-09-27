<?php
require_once($_SERVER["DOCUMENT_ROOT"]."/php/init.php");
Security::demand(USER);
$smarty = new MMSmarty();
$smarty->assign("pagetitle", "Min profil");

$tabs = new TabBox("installningar", 760, null);
$tabs->addTab("Allm&auml;nt", "allmant");
$tabs->addTab("Om mig", "ommig");
$tabs->addTab("Detaljerat", "detaljerat");
$tabs->addTab("Medlemskap", "medlemskap");
$tabs->addTab("F&ouml;retagsnyckel", "foretagsnyckel");

if(isset($_GET["tab"]))
	$tabs->setSelected($_GET["tab"]);

$smarty->assign("tabs", $tabs);
$visningsbild = $USER->getVisningsbild();
$smarty->assign("visningsbild", $visningsbild);

$kommuner = Kommun::listAll();
$opt_kommuner = array(""=>"Välj...");
foreach($kommuner as $kommun){
	$opt_kommuner[$kommun->getId()] = $kommun->getOrt();
}
$sel_kommun = $USER->getKommunId();
$smarty->assign("opt_kommuner", $opt_kommuner);
$smarty->assign("sel_kommun", $sel_kommun);
$customVisningsbild = $USER->getCustomVisningsbild();
$smarty->assign("customVisningsbild", $customVisningsbild);
$unapprovedVisningsbild = $USER->getCustomVisningsbild(false);
$smarty->assign("unapprovedVisningsbild", $unapprovedVisningsbild);


$opt_access = array("alla"=>"Öppen för alla", "medlem"=>"Öppen för Motiomera-medlemmar", "adressbok"=>"Öppen för alla mina vänner");
if($USER->getForetag()){
	$opt_access["foretag"] = "Öppen för alla i mitt företag";
}else{
	$opt_access["ingen"] = "Inte öppen för någon";
}
$smarty->assign("opt_access", $opt_access);
$sel_access = $USER->getAtkomst();
$smarty->assign("sel_access", $sel_access);
$smarty->display('installningar.tpl');