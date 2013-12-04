<?php
require_once($_SERVER["DOCUMENT_ROOT"]."/php/init.php");

Security::demand(USER);

$smarty = new MMSmarty;
if(!empty($_GET['mid'])) {
	$medlem = Medlem::loadById($_GET['mid']);
	$rss = $medlem->getLatestCachedRss(true);
	$rss['pubDate'] = date('Y-m-d', $rss['pubDate']);
	$smarty->assign('rss', $rss);
	$smarty->assign("pagetitle", $medlem->getAnamn()."s Senaste Blogg Inlägg");
} else {
	throw new UserException('Profilen finns inte', 'denna medlem finns inte eller har avregistrerat sig');
}
$smarty->display('minblogg.tpl');
?>
