<?php
//phpinfo(); die();
require_once($_SERVER["DOCUMENT_ROOT"]."/php/init.php");
$smarty = new MMSmarty;
$medlemsprofiler = Medlem::getMedlemmarFrontpage(3);
shuffle($medlemsprofiler);
$smarty->assign("title", "Välkommen till Motiomera");
$smarty->assign("medlemsprofiler", $medlemsprofiler);

list($key,$medlem) = each($medlemsprofiler);
$medlem->getProfilDataValObject("random");

$totalVecka = Steg::getTotalSteg(date("Y-m-d", time()-(60*60*24*7)), date("Y-m-d"));
$smarty->assign("totalVecka", $totalVecka);
$smarty->showSidebar();
$browser = $medlem->getCurrentBrowserVersion();
$smarty->assign("browser", $browser);
$texteditor = TextEditor::loadById(10);
$smarty->assign("texteditor_nh", $texteditor);
$texteditor = TextEditor::loadById(9);
$smarty->assign("texteditor_nm", $texteditor);
$namn = "Startsidan under menyn";
$texteditor = TextEditor::loadByNamn($namn);
$smarty->assign("texteditor_um", $texteditor);

//the rss flow from mabra.com
$file = ROOT . "/files/rsscache/motiofeed.txt";
$fh = fopen($file, "r");
$smotiofeed = file_get_contents($file);
fclose($fh);
$rss = unserialize($smotiofeed);
$smarty->assign("rss", $rss);

// throw new Exception("testar");
// echo Medlem::loadById(12431243);
$smarty->display("index.tpl");
