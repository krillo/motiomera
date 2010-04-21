<?php

require_once($_SERVER["DOCUMENT_ROOT"]."/php/init.php");

Security::demand(USER);

$smarty = new MMSmarty();

if (empty($_GET["fid"])) {
	$urlHandler->redirect("Fotoalbum", "URL_LIST");
}


$fotoalbum = Fotoalbum::loadById($_GET["fid"]);
$medlem = Medlem::loadById($fotoalbum->getMedlemId());
$smarty->assign("pagetitle", ucfirst($fotoalbum->getNamn()). " &mdash; ". $medlem->getANamn()  ." &mdash; Visa fotoalbum");

if ($fotoalbum->isAgare()) {
	// Besökaren äger detta album
	$smarty->assign("isAgare", true);

	// vilka har tillgång till detta fotoalbum?
	$tilltrade = $fotoalbum->getTilltrade();
	if ($tilltrade == "alla") {
		$smarty->assign("tilltrade", "Alla har tillträde till detta album");
	} else {
		if ($fotoalbum->getTilltradeAllaGrupper() == "ja") {
			$tilltrade = "Alla grupper";
			$grupper = null;
		} else {
			$grupper = $fotoalbum->getTilltradesGrupper();
		
			if ($grupper != null) {
				if (count($grupper) == 1) {
					$grupp = Grupp::loadById($grupper[0]);
					$tilltrade = "Gruppen " . $grupp->getNamn();
				} else {
					$tilltrade = "Följande grupper: <strong>";
					for($x=0;$x<count($grupper);$x++) {
						$grupp = Grupp::loadById($grupper[$x]);
						$tilltrade .= $grupp->getNamn() . ", ";
					}
					$tilltrade = substr($tilltrade, 0, strlen($tilltrade)-2);
					$tilltrade .= "</strong>";
				}
			}
		}

		if ($fotoalbum->harForetagTilltrade() == true) {
			$foretag = Foretag::loadByMedlem($fotoalbum->getMedlem());
			if ($grupper != null || $fotoalbum->getTilltradeAllaGrupper() == "ja") {
				// både grupper och företag har tillgång
				$tilltrade .= " samt företaget <strong>" . $foretag->getNamn() . "</strong>";
			} else {
				// endast företag har tillgång
				$tilltrade = "Endast företaget <strong>" . $foretag->getNamn() . "</strong>";
			}
		}
		if (isset($foretag) || $grupper != null || $fotoalbum->getTilltradeAllaGrupper() == "ja") {
			$tilltrade .= " har tillträde till detta fotoalbum";
		} else {
			$tilltrade = "Ingen har tillträde till detta fotoalbum";
		}
	
		$smarty->assign("tilltrade", $tilltrade);
	}
} else {
	// har besökaren tilltrade till detta fotoalbum?
	if (!$fotoalbum->harMedlemTilltrade($USER)) {
		// nej, skicka till sitt eget fotoalbum
		$urlHandler->redirect("Fotoalbum", "URL_LIST");
	}
}



$bilder = FotoalbumBild::listAsArray($medlem, $fotoalbum->getId());

$smarty->assign("fotoalbum", $fotoalbum);
$smarty->assign("bilder", $bilder);
$smarty->assign("x", 0);
$smarty->assign("show", false);
$smarty->assign("fid", $_GET["fid"]);

$bilder = Fotoalbum::loadBilder($_GET["fid"]);

$smarty->display('fotoalbumvisa.tpl');


?>