<?php
require_once($_SERVER["DOCUMENT_ROOT"] . "/php/init.php");
// Ta bort eventuella temp-sträckor som inte sparats:
if (isset($USER)) {
  $USER->cleanTempStrackor();
}
$req = new stdClass;
!empty($_REQUEST['mid']) ? $req->mm_id = addslashes($_REQUEST['mid']) : $req->mm_id = '';
$medlem = (!empty($req->mm_id)) ? Medlem::loadById($req->mm_id) : $USER;
?>

<?php print_r($medlem); ?>
<h1>Detaljerad rapport för <?php echo $medlem->getAnamn(); ?></h1>
<div id="profil_id" style="display: none;"><?php echo $req->mm_id; ?></div>  

<img id="mmInstallningarAvatar" src="/files/avatarer/<?php echo $medlem->getAvatarFilename(); ?>" alt="" class="mmAvatar">
<a href="/kommun/<?php echo $medlem->getJustNuKommunNamn(); ?>/" /><?php echo $medlem->getJustNuKommunNamn(); ?> </a> 
<br>
medlem sedan <?php echo $medlem->getSkapadDateOnly(); ?>
<br>
<br>
<?php echo $medlem->getAvatar(); ?>
<br>
<?php echo $medlem->getCustomVisningsbild(); ?>
<br>





<?php
$fid = $medlem->getForetagsId();
if ($fid > 0) {
  echo $fid;
  echo '<br>';
  echo $medlem->getForetag()->getNamn();
  echo '<br>';
  $startDatum = $medlem->getForetag()->getStartdatum();
  echo 'Tävlingsstart: ' . $startDatum;
  $nbrDays = (int) JDate::dateDaysDiff($startDatum, date('y-m-d'));
  if ($nbrDays > 56) {  //56 = 7 * 8 days
    $heading = "Steg de senaste ";
    $nbrDays = 30;
  }
  echo '<br>';
  echo $nbrDays;
  $heading = "Steg under hela tävlingen ";
} else {
  $heading = "Steg de senaste ";
  $nbrDays = 30;
}



$legend1 = "Dina snittsteg";
$dateSelector = true;
include(BASE_PATH . '/wordpress/wp-content/themes/motiomera/snippets/inc_graph.php');

echo '<div class="clear"></div>';
$show = true;
$enableInput = false;
$showComments = false;
include(BASE_PATH . '/wordpress/wp-content/themes/motiomera/snippets/inc_steps.php');