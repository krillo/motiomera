<?php global $USER, $urlHandler, $medlem, $graf;


	$smarty = new MMSmarty();
	
	$smarty->noShowHeaderFooter(); // dölj header och footer då detta är en flik

	$smarty->assign("medlem",$medlem);
	$smarty->assign("graf",$graf);

	$smarty->display('rapport_detaljerat.tpl');

?>