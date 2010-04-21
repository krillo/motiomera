<?php

require_once($_SERVER["DOCUMENT_ROOT"]."/php/init.php");

$smarty = new MMSmarty;
$smarty->assign("pagetitle", "Topplistor");

$namn = "Topplistor";

if(isset($_GET["id"]) && $_GET["id"]) {

	$medlem = Medlem::loadById($_GET["id"]);
}
elseif(isset($USER)) {

	$medlem = $USER;
}
if(isset($medlem)) {
	$smarty->assign("medlem", $medlem);
}

if(isset($_GET["klubb"]) && $_GET["klubb"] > 0) {

	$klubb = Grupp::loadById($_GET["klubb"]);
	$start = $klubb->getStart();
	$smarty->assign("gruppnamn",$klubb->getNamn());
}

$forraVeckan = date("Y-m-d H:i:s",strtotime("-7 days"));

$topplista_sju = new Topplista();
if(isset($klubb)) {
	$topplista_sju->addParameter(Topplista::PARAM_GRUPP, $klubb);
	if($klubb->getStart() > $topplista_sju) {
		$forraVeckan = $klubb->getStart();
	
	}	
}
$topplista_sju->addParameter(Topplista::PARAM_START, $forraVeckan);
$smarty->assign("topplista_sju", $topplista_sju);


$fyraVeckor = date("Y-m-d H:i:s",strtotime("-28 days"));

$topplista_28 = new Topplista();
if(isset($klubb)) {
	
	$topplista_28->addParameter(Topplista::PARAM_GRUPP, $klubb);
	if($klubb->getStart() > $fyraVeckor) {
		$fyraVeckor = $klubb->getStart();
	
	}	
}
$topplista_28->addParameter(Topplista::PARAM_START, $fyraVeckor);
$smarty->assign("topplista_28", $topplista_28);


$quiz = date("Y-m-d H:i:s", strtotime(date("Y-m-d"))-(60*60*24*7));

$topplista_quiz = new Topplista(Topplista::TOPPLISTA_QUIZ);
if(isset($klubb)) {
	$topplista_quiz->addParameter(Topplista::PARAM_GRUPP, $klubb);
}
$topplista_quiz->addParameter(Topplista::PARAM_QUIZ_START, $fyraVeckor);
$smarty->assign("topplista_quiz", $topplista_quiz);


$topplista_kommuner = new Topplista(Topplista::TOPPLISTA_KOMMUNER);
if(isset($klubb)) {
	$topplista_kommuner->addParameter(Topplista::PARAM_GRUPP, $klubb);
	if($klubb->getStart() > $fyraVeckor) {
		$fyraVeckor = $klubb->getStart();
	
	}
}
$topplista_kommuner->addParameter(Topplista::PARAM_KOMMUNER_START, $forraVeckan);
$smarty->assign("topplista_kommuner", $topplista_kommuner);



$smarty->display('topplistor.tpl');

?>