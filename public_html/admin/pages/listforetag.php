<?php
require_once($_SERVER["DOCUMENT_ROOT"]."/php/init.php");
Security::demand(ADMIN);
$smarty = new AdminSmarty;


$showValid = "";
$checked = "checked";
$field = "id";


if(!empty($_GET["way"])){
	if($_GET['way']!="DESC"){
		$way="ASC";
	}else{
		$way="DESC";
	}
}else{
		$way="DESC";
}

if(!empty($_GET['showValid']) && $_GET['showValid']=="true"){
	$showValid=$_GET['showValid'];
	$checked = "checked";
}else{
	$checked = "";
	$showValid = "false";
}	


if(!empty($_GET['offset'])){
	$offset=$_GET['offset'];
}else{
	$offset=0;
}
	
if(!empty($_GET['limit'])){
	$limit=$_GET['limit'];
}else{
	$limit=40;
}

if(!empty($_GET['sort'])){
	$sort=$_GET['sort'];
}else{
	$sort="id";
}

//only get field when search is set 
if(!empty($_GET['search'])){
	$search = $_GET['search'];
	$field= $_GET["field"];		
} else {
	$search = null;
	$field = null;	
}

$listForetag = Foretag::listForetag($offset, $limit, $field, $search, $way, $showValid);

$smarty->assign("search", $search);
$smarty->assign("offset", $offset);
$smarty->assign("way", $way);
$smarty->assign("limit", $limit);
$smarty->assign("field", $field);
$smarty->assign("checked", $checked);
$smarty->assign("searchOpt", array("namn"=> "Företagsnamn","id"=> "Id","epost"=> "E-Mail"));
if($field == null){  //just to keep dropdown in sync with last search
	$field = "id";
}
$smarty->assign("searchSel", $field);

$smarty->assign("listForetag", $listForetag);

$smarty->display('listforetag.tpl');

?>