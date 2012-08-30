<?php

/**
 *
 * 
 * 
 * NO longer in use    Krillo 2012
 * 
 * 
 * 
 * 
 * 
 *  
 */











	global $USER;
	$smarty = new MMSmarty();
	
	$TEAM = ""; //Medlemslag
	$COMPANY = ""; //Medlemsforetag

	if(isset($USER)){
		if($USER->getLag()){
			$TEAM = $USER->getLag();
		}
		if($USER->getForetag()){
			$COMPANY = $USER->getForetag();
		}
	}

	$tfObjects = Tavling::getHallOfFameForetag();
	$tf = array();
	foreach($tfObjects as $f){
		if(!empty($f)) {
			// $foretag = Foretag::loadById($f['foretag_id']);
		
			$tf[] = array('stegindex'=> ($f['steg']/$f['lag']/Foretag::TAVLINGSPERIOD_DAGAR), 'namn'=> $f['namn']);
		}
	}

	if(count($tf) != 0){
	 array_multisort($tf, SORT_DESC);
	}
	
	
	$tlObjects = Tavling::getHallOfFameLag();

	$tl = array();
	foreach($tlObjects as $l){
		if(!empty($l)){
			// $foretag = Foretag::loadById($f['foretag_id']);
			$tl[] = array("stegindex" => ($l['steg']/$l['medlemmar']/Foretag::TAVLINGSPERIOD_DAGAR),"namn"=> $l['lag_namn'] ."<br />från ".  $l['foretag_namn'], "id"=> $l['lag_id']);
		}
	}

	if(count($tl) != 0){
		array_multisort($tl, SORT_DESC);
	}

	$tm = array();
	$tmObjects = Tavling::getHallOfFameMedlemmar();
	$ids = array();
	$stegindex = array();
	//print_r($tmObjects);
	//die();
	foreach($tmObjects as $m){
		if(!empty($m)){
			$ids[] = $m['medlem_id'];
			$stegindex[$m['medlem_id']] = $m['steg'];
		}
	}
	
	$medlemmar = Medlem::loadByIds($ids);
	// print_r($medlemmar);
	foreach ($medlemmar as $medlem) {
		if (!empty($medlem)) {
			$id = $medlem->getId();
			$s = ($stegindex[$id]/Foretag::TAVLINGSPERIOD_DAGAR);
			$tm[] = array("stegindex"=> $s, "namn"=> $medlem->getANamn(), "id"=> $id);
		}
	}

	if(count($tm) != 0){
		array_multisort($tm, SORT_DESC);
	}
	$smarty->assign("topplista_foretag", $tf);
	$smarty->assign("topplista_lag", $tl);
	$smarty->assign("topplista_medlem", $tm);
	unset($tf);
	unset($tl);
	unset($tm);
	if(isset($USER)){
		if($TEAM){
			$smarty->assign("TEAM", $TEAM);
		}
		if($COMPANY){
			$smarty->assign("COMPANY", $COMPANY);
		}
	}
	
	$smarty->assign("toplist_count", 101);
	$smarty->assign("hof", true);
	$output = $smarty->fetch('widget_deltagartoppen.tpl');
	$output .= $smarty->fetch('widget_lagtoppen.tpl');
	$output .= $smarty->fetch('widget_foretagstoppen.tpl');
	echo $output;	
?>
