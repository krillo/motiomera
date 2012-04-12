<?php
require_once($_SERVER["DOCUMENT_ROOT"]."/php/init.php");
Security::demand(ADMIN);
$smarty = new AdminSmarty;


$showValid = "";
$checked = "checked";
$field = "id";


if(!empty($_GET['foretagid'])){
	$foretagid=$_GET['foretagid'];
}else{
	$foretagid="0";
}


if(!empty($_GET['showComp'])){
	$showForetag=true;
}else{
	$showForetag=false;
}





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
	$limit=0;
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


if($foretagid != "0"){
	$listOrder = Order::listOrderIdsByForetagId($foretagid);
} else {
	$listOrder = Order::listOrderKrillo($offset, $limit, $field, $search, $way, $showValid, $showForetag);
  //print_r($listOrder);
  //die();
}
$smarty->assign("search", $search);
$smarty->assign("offset", $offset);
$smarty->assign("way", $way);
$smarty->assign("limit", $limit);
$smarty->assign("field", $field);
$smarty->assign("checked", $checked);
$smarty->assign("searchOpt", array("id"=> "Id","companyName"=> "Företagsnamn"));
if($field == null){  //just to keep dropdown in sync with last search
	$field = "id";
}
$smarty->assign("searchSel", $field);

$smarty->assign("listOrder", $listOrder);

$smarty->display('listorder.tpl');

?>