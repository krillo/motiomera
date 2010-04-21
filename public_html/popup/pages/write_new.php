<?php

require_once($_SERVER["DOCUMENT_ROOT"]."/php/init.php");

Security::demand(USER, null, false);
$smarty = new PopSmarty();

$id = Security::escape($USER->getId());

$myself = Medlem::loadById($id);
$my_contacts = $myself->getUsersThatHasMeAsContact(0);
$smarty->assign("my_contacts", $my_contacts);
$smarty->assign("my_id", $USER->getId());
$smarty->display('write_new.tpl');
?>