<?php

require_once($_SERVER["DOCUMENT_ROOT"]."/php/init.php");

Security::demand(ADMIN);

$smarty = new AdminSmarty;

if(!empty($_GET["way"])){
	if($_GET['way']!="DESC"){
		$way="ASC";
	}else{
		$way="DESC";
	}
}else{
		$way="DESC";
}

if(!empty($_GET['search'])){
	$search = $_GET['search'];
	$sort= $_GET["field"];
	
	if(!empty($_GET['offset'])){
		$offset=$_GET['offset'];
	}else{
		$offset=0;
	}
	
    if(!empty($_GET['limit'])){
		$limit=$_GET['limit'];
	}else{
		$limit=0;
	}
	
}else{
	$search = null;

	if(!empty($_GET['offset'])){
		$offset=$_GET['offset'];
	}else{
		$offset=0;
	}

	if(!empty($_GET['limit'])){
		$limit=$_GET['limit'];
	}else{
		$limit=20;
	}

	if(!empty($_GET['sort'])){
		$sort=$_GET['sort'];
	}else{
		$sort="id";
	}
}

$medlemmar = Medlem::listMedlemmar($offset,$limit,$sort,$search,$way);

$smarty->assign("medlemmar", $medlemmar);
$smarty->assign("search", $search);
$smarty->assign("offset", $offset);
$smarty->assign("way", $way);
$smarty->assign("limit", $limit);
$smarty->assign("sort", $sort);
$smarty->assign("searchOpt", array("fNamn" => "Förnamn","eNamn"=> "Efternamn","aNamn"=> "Användarnamn","id"=> "Id","epost"=> "E-Mail"));
$smarty->assign("searchSel", $sort);
$smarty->display('medlemmar.tpl');
?>