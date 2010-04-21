<?php

require_once($_SERVER["DOCUMENT_ROOT"]."/php/init.php");

Security::demand(USER);

$smarty = new MMSmarty();
$smarty->assign("pagetitle", "Välj Kommun");

$rutt = new Rutt($USER);
$rutter = $rutt->getRutt();
if(isset($rutter[$rutt->getCurrentIndex()+1]))
	$urlHandler->redirect("Medlem", URL_VIEW_OWN);


$current_kommun = $rutt->getCurrentKommun();


$replace = array(" ","å","ä","ö","Ö","Lan");
$with = array("","a","a","o","O","lan");

$lan_1 = $current_kommun->getLan();
$lan_1 = str_replace($replace,$with,$lan_1); // omvandlar namnet till kartornas namn

$smarty->assign("lan_1",$lan_1);

// gå igenom grannkommuner och se om de ligger i andra län
$avstand = $current_kommun->listAvstand();	

$count = 2;
foreach($avstand as $tmp){

	$ktmp = Kommun::loadById($tmp["id"]);
	$ltmp = $ktmp->getLan();
	$ltmp = str_replace($replace,$with,$ltmp); // omvandlar namnet till kartornas namn
		
	$add = true;
	for($i=1;$i<$count;$i++) {
	
		$lan = "lan_$i";
		
		if($ltmp == "" || $ltmp == $$lan) {
		
			$add = false;
			break;
		}
	}
	
	if($add) {
	
		$next_lan = "lan_$count";
			
		$$next_lan = $ltmp;
		
		$smarty->assign("$next_lan",$$next_lan);

		$count++;
		

	}
}


$smarty->display('valjKommun.tpl');

?>