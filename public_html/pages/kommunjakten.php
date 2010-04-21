<?
	include $_SERVER["DOCUMENT_ROOT"]  . "/php/init.php";

	$smarty = new MMSmarty();
	$smarty->assign("pagetitle", "Kommunjakten");

	function convertUrlNamn($namn){
		$from = array("å","ä","ö","Å","Ä","Ö"," ");
		$to = array("aa", "ae", "oe", "AA", "AE", "OE", "_");
		
		//return str_replace($from, $to, $namn);
		return $namn;
	}

	$lanlista = Kommun::listLan();
	
	$lan = array();
	
	foreach($lanlista as $key=>$onelan)	
		$lan[$onelan] = convertUrlNamn($onelan);
		
	$smarty->assign("lan",$lan);
	
	$smarty->assign("kommuner", Kommun::listAll(true));

	$smarty->display('kommunjakten_start.tpl');
	
?>