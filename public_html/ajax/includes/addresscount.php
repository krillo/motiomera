<?php
 /**
  * 12-12-29 Kristian Erendi, Reptilo.se
  */
header("Content-Type: text/html; charset=utf-8");
require_once($_SERVER["DOCUMENT_ROOT"]."/php/init.php");
!empty($_REQUEST['mm_id']) ? $id = addslashes($_REQUEST['mm_id']) : $id = ''; 
$nbr = Adressbok::getUnreadContacts($id);
$html = '';
if($nbr > 0){
  $html = '<div class="logged-in-friend-unread">'.$nbr.'</div>';
}
echo $html;
