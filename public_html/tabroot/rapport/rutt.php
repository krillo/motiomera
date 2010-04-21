<?php global $USER, $urlHandler, $medlem, $opt_angransande, $rutt, $rutten, $currentKommun;

	$smarty = new MMSmarty();
	
	$smarty->noShowHeaderFooter(); // dölj header och footer då detta är en flik

	$smarty->assign("medlem",$medlem);

	if(!isset($USER) || (!empty($_GET["id"]) && $_GET["id"] != $USER->getId())) {
		$smarty->assign("notown", "true");
	}

	$smarty->assign("opt_angransande", $opt_angransande);

	$smarty->assign("rutt", $rutt);
	if ($rutten[0]):
		$smarty->assign("rutten", $rutten);
	endif;
	$smarty->assign("currentKommun", $currentKommun);

	if (isset($USER) && ($USER->getId() == $_GET["id"] or trim($_GET["id"])) == ""):
		$smarty->assign("egensida", "1");
	endif;


	$fastaUtmaningar = Rutt::getAllFastaUtmaningar();
	$smarty->assign("fastaUtmaningar", $fastaUtmaningar);
	if(!empty($USER)){
		$userOnStaticRoute = $USER->getUserOnStaticRoute();
		$userIsAbroadId = $USER->getUserAbroadId();
		if ($userOnStaticRoute == true) {
			$smarty->assign("userOnStaticRoute", $userOnStaticRoute);
		}

		//die($userIsAbroadId);
		if ($userIsAbroadId != null) {
			if (file_exists(ROOT.'/files/staticroute/'.$userIsAbroadId)) {
				# code...
				$abroadImage = '/files/staticroute/'.$userIsAbroadId;
			} else {
				# code...
				$abroadImage = '/img/routesAbroad.png';
			}
			$smarty->assign('abroadImage', $abroadImage);
			$smarty->assign('abroadId', $userIsAbroadId);
		}
	}

	$smarty->display('rapport_rutt.tpl');

?>
