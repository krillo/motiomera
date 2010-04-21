<?php

require $_SERVER["DOCUMENT_ROOT"] . "/php/init.php";

$stegList = Steg::listByMedlem($USER);

$aktiviteter = Aktivitet::listAll();
$first = true;
foreach($stegList as $steg){
	if($first) $first = false; else echo "|";
	$last = ($steg->getLast()) ? 1 : 0;
	echo "[datum]" . date("F, j Y 00:00:00", strtotime($steg->getDatum())) . "%[aid]" . $steg->getAktivitetId() . "%[antal]" . $steg->getAntal() . "%[last]" . $last . "%[id]" . $steg->getId();
}

?>