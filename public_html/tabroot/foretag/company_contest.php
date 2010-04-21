<?php
	global $USER,$db,$Memcache;
	
	$cache_time_foretag = 3600;
	$cache_time_lag = 120;
	$cache_time_medlem = 30;
	
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

	//echo "**<br/>";
	//echo "Current memory usage: " . number_format(memory_get_usage(),0,","," ") . "<br/>";
	
	if (MEMCACHE) {
		$tf = $Memcache->getClassic("topplista_foretag");
		$tl = $Memcache->getClassic("topplista_lag");
		$tm = $Memcache->getClassic("topplista_medlem");
	}
	
	$t = Misc::get_milliseconds();

	$foretag_ids = array();
	if(!$tf) {
	
		$tfObjects = Foretag::listAll();
			
		$tf = array();
		foreach($tfObjects as $foretag){
			if(!empty($foretag) && $foretag->getSlutDatum() > date('Y-m-d')){
				
				$stegindex = $foretag->getStegIndex();
				if ($stegindex != 0) {
					
					$foretag_ids[] = $foretag->getId();
	
					if($stegindex != 0){
						$tf[] = array("stegindex" => $stegindex,"namn"=> $foretag->getNamn(), "id"=> $foretag->getId());
					}
				}
				unset($foretag);
	
			}
			
			//$db->clearBufferObjects("mm_foretag",$foretag);
			
		}
		unset($tfObjects);
		unset($foretag_stegtotal_cache);
		//$db->clearBufferObjects("mm_foretag");
	
		if(count($tf) != 0){
		 array_multisort($tf, SORT_DESC);
		}
	}
	else {
		//echo "using memcache for foretag";
	}
	
	
	//echo "Current memory usage: " . number_format(memory_get_usage(),0,","," ") . "<br/>";
	//echo "Time for Foretag: " . (Misc::get_milliseconds() - $t) . "<br/>";
	//echo "**<br/>";
	//$t = Misc::get_milliseconds();
	
	if(!$tl) {
		$tlObjects = Lag::listAll();
	
		$tl = array();
		foreach($tlObjects as $lag){
			if(!empty($lag) && $lag->getForetag()->getSlutDatum() > date('Y-m-d')){
				$stegindex = $lag->getStegIndex();
				if($stegindex != 0){
	
					$tl[] = array("stegindex" => $stegindex,"namn"=> $lag->getNamn() ."<br />från ". $lag->getForetag()->getNamn(), "id"=> $lag->getId());
	
				}
				
				unset($lag);
	
			}
			
	
		}
		unset($tlObjects);
		unset($lag_stegtotal_cache);
		$db->clearBufferObjects("mm_lag");
	}
	else {
		//echo "using memcache for lag";
	}

	//echo "Current memory usage: " . number_format(memory_get_usage(),0,","," ") . "<br/>";
	//echo "Time for Lag: " . (Misc::get_milliseconds() - $t) . "<br/>";
	//echo "**<br/>";
	//$t = Misc::get_milliseconds();
	
	if(!$tm) {
		
	
		if(count($tl) != 0){
			array_multisort($tl, SORT_DESC);
		}
	
		$tm = array();
		//$tmObjects = Medlem::listAllInForetag();
		
		// $foretag_ids will be empty if foretag_topplista is loaded from memcache
		if(empty($foretag_ids)) {
			$foretag_ids = array();
			foreach($tf as $foretag) {
				$foretag_ids[] = $foretag["id"];
			}
		}
		
		$sql = "SELECT foretag_id, medlem_id, aNamn FROM " . Foretag::KEY_TABLE . " f INNER JOIN " . Medlem::TABLE . " m ON f.medlem_id=m.id WHERE medlem_id > 0 AND foretag_id IN (" . implode(",",$foretag_ids) . ")";
		$res = $db->query($sql);
		
		while($row = mysql_fetch_array($res)) {
			try {
				$foretag = Foretag::loadById($row["foretag_id"]);
				$medlem_id = $row["medlem_id"];
				$medlem_anamn = $row["aNamn"];
			}
			catch(Exception $e) {
				// Foretag doesn't exist (we check this just in case)
				continue;
			}
			if(!empty($medlem_id)){
				$stegindex = Medlem::getStegIndexForMedlemId($medlem_id,$foretag);
				if($foretag != null && $stegindex != null && $foretag->getSlutDatum() > date('Y-m-d')){
	
					$tm[] = array("stegindex"=> $stegindex, "namn"=> $medlem_anamn, "id"=> $medlem_id);
	
				}
			}
		}
		
		unset($foretag);
		unset($medlem_stegtotal_cache);
		unset($res);
		//echo "Current memory usage: " . number_format(memory_get_usage(),0,","," ") . "<br/>";
	
		$db->clearBufferObjects("mm_foretag");
	
		if(count($tm) != 0){
			array_multisort($tm, SORT_DESC);
		}
	}
	else {
		//echo "using memcache for medlem";
	}
	//echo "Current memory usage: " . number_format(memory_get_usage(),0,","," ") . "<br/>";
	//echo "Time for Medlem: " . (Misc::get_milliseconds() - $t) . "<br/>";
	//echo "**<br/>";
	$t = Misc::get_milliseconds();
	
	if (MEMCACHE) {
		$Memcache->setClassic("topplista_foretag",$tf,false, $cache_time_foretag);
		$Memcache->setClassic("topplista_lag",$tl,false, $cache_time_lag);
		$Memcache->setClassic("topplista_medlem",$tm,false, $cache_time_medlem);
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
		$smarty->assign("toplist_count", 11);
	$output = $smarty->fetch('widget_deltagartoppen.tpl');
	$output .= $smarty->fetch('widget_lagtoppen.tpl');
	$output .= $smarty->fetch('widget_foretagstoppen.tpl');
	echo $output;
?>
