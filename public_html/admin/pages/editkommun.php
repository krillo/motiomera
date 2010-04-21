<?php

require_once($_SERVER["DOCUMENT_ROOT"]."/php/init.php");

if (empty($_GET["kid"])) {
	Security::demand(EDITOR);
}else{
	$kommun = Kommun::loadById($_GET["kid"]);
	Security::demand(KOMMUN, $kommun);
}

$smarty = new AdminSmarty;

$lan = array(
		"Stockholms Län" => "Stockholms Län",
		"Uppsala Län" => "Uppsala Län",
		"Södermanlands Län" => "Södermanlands Län",
		"Blekinge Län" => "Blekinge Län",
		"Dalarnas Län" => "Dalarnas Län",
		"Gotlands Län" => "Gotlands Län",
		"Gävleborgs Län" => "Gävleborgs Län",
		"Hallands Län" => "Hallands Län",
		"Jämtlands Län" => "Jämtlands Län",
		"Jönköpings Län" => "Jönköpings Län",
		"Kalmar Län" => "Kalmar Län",
		"Kronobergs Län" => "Kronobergs Län",
		"Norrbottens Län" => "Norrbottens Län",
		"Skåne Län" => "Skåne Län",
		"Värmlands Län" => "Värmlands Län",
		"Västerbottens Län" => "Västerbottens Län",
		"Västernorrlands Län" => "Västernorrlands Län",
		"Västmanlands Län" => "Västmanlands Län",
		"Västra Götalands Län" => "Västra Götalands Län",
		"Örebro Län" => "Örebro Län",
		"Östergötlands Län" => "Östergötlands Län"
	);
	
$smarty->assign("opt_lan",$lan);



if(!empty($_GET["kid"])){

	
	$kommunNamn = $kommun->getNamn();
	$kommunId = $kommun->getId();
	$abroad = $kommun->getAbroad();
	$smarty->assign("kommun", $kommun);
	$smarty->assign("kommunNamn", $kommunNamn);
	$smarty->assign("kommunId", $kommunId);
	$smarty->assign("abroad", $abroad);
	
	$smarty->assign("sel_lan",$kommun->getLan());
	
	$smarty->assign("FB_FULLBREDD", Kommunbild::FB_FULLBREDD);
	$smarty->assign("FB_HALVBREDD", Kommunbild::FB_HALVBREDD);
	$smarty->assign("FB_TREDELBREDD", Kommunbild::FB_TREDELBREDD);
	
	$notin = array($kommun->getId());

	$allakommunnamn = Kommun::listNamn($abroad == "true"?true:false);

	$smarty->assign("allakommunnamn", $allakommunnamn);	
	
	$avstand = $kommun->listAvstand();	
	$smarty->assign("avstand", $avstand);

	$avstandArgs = array();
	foreach($avstand as $temp){
		$notin[] = $temp["id"];
		$avstandArgs[$temp["id"]] = array($kommun->getId(), $temp["id"]);
	}
	$smarty->assign("avstandArgs", $avstandArgs);


	$kommunvapen = $kommun->getKommunvapen();
	$smarty->assign("kommunvapen", $kommunvapen);

	$kommunkarta = $kommun->getKommunkarta();
	$smarty->assign("kommunkarta", $kommunkarta);
	
	$kommunbilder = $kommun->listKommunbilder();
	$smarty->assign("kommunbilder", $kommunbilder);
	
	$allKommuner = Kommun::listByIds($notin, true);
	$opt_kommuner = array(""=>"Välj...");
	
	if ($abroad == 'true') {
		$opt_kommuner = Kommun::listNamn(true);
	} else {
		$opt_kommuner = Kommun::listNamn(false);
	}
	$smarty->assign("opt_kommuner", $opt_kommuner);
	
	// dialekter
	
	$dialekter = Kommundialekt::listByKommunId($kommunId);
	
	$smarty->assign("dialekter", $dialekter);
	
	

}

$smarty->display('editkommun.tpl');

?>
