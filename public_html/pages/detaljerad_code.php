<?php
require_once($_SERVER["DOCUMENT_ROOT"]."/php/init.php");
// Ta bort eventuella temp-sträckor som inte sparats:
if(isset($USER)) {
	$USER->cleanTempStrackor();
}
$req = new stdClass;
!empty($_REQUEST['mid']) ? $req->mm_id = addslashes($_REQUEST['mid']) : $req->mm_id = '';
$medlem = (!empty($req->mm_id)) ? Medlem::loadById($req->mm_id) : $USER;
?>

<?php print_r($medlem); ?>
<h1>Detaljerad rapport - <?php echo $medlem->getAnamn(); ?></h1>
<div id="profil_id" style="display: none;"><?php echo $req->mm_id; ?></div>  

<?php 
  $heading = "Snittsteg för företaget under hela tävlingen";
  $legend1 = "Företagets snittsteg";
  //$dateSelector = false;
  $mid = 
include(BASE_PATH . '/wordpress/wp-content/themes/motiomera/snippets/inc_graph.php');


