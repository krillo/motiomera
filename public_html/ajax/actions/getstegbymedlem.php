<?php

require $_SERVER["DOCUMENT_ROOT"] . "/php/init.php";

$medlem = Medlem::loadById($_POST["mid"]);

$datumList = Steg::listDatumByMedlem($medlem);
$first = true;
foreach($datumList as $datum=>$steg){
	if($first) $first = false; else echo "|";
	echo $datum . "+" . number_format($steg, 0, null, " ");

}

?>