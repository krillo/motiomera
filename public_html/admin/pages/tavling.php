<?php

require_once ($_SERVER["DOCUMENT_ROOT"] . "/php/init.php");
Security::demand(ADMIN);
$smarty = new AdminSmarty;
$forraVeckan_slut = date("Y-m-d H:i:s", strtotime("last sunday 23.59.59"));
$forraVeckan_start = date("Y-m-d H:i:s", strtotime($forraVeckan_slut) - (60 * 60 * 24 * 7));
$forraVeckan_slut_nice = date("Y-m-d", strtotime("last sunday 23.59.59"));
$forraVeckan_start_nice = date("Y-m-d", strtotime($forraVeckan_slut) - (59 * 60 * 24 * 7));
$steg = array();

for ($i = 1; $i <= 700; $i++) {
  $steg[] = $i * 1000;
}
$smarty->assign('steg', $steg);
$antalMedlemmar = Medlem::getAntalMedlemmar();
$antal = array();
for ($i = 1; $i <= $antalMedlemmar; $i++) {
  $antal[] = $i;
}

$smarty->assign('antalMedlemmar', $antal);
$percent = array();
$percentTimes = array();
for ($i = 0; $i <= 99; $i++) {
  $percent[] = $i;
  $percentTimes[] = '0.' . $i;
}

$percent[] = 100;
$percentTimes[] = 1;
$smarty->assign('percent', $percent);
$smarty->assign('percentTimes', $percentTimes);
$dates = array();
for ($i = 1; $i < 365; $i++) {
  $dates[] = date('Y-m-d', strtotime('-' . $i . ' days'));
}
if (isset($_POST['startTid'])) {
  $stt = $_POST['startTid'];
} else {
  $stt = $forraVeckan_start_nice;
}
if (isset($_POST['slutTid'])) {
  $slt = $_POST['slutTid'];
} else {
  $slt = $forraVeckan_slut_nice;
}
if (isset($_POST['antal_medlemmar'])) {
  $am = $_POST['antal_medlemmar'];
} else {
  $am = 10;
}
if (isset($_POST['antal_steg'])) {
  $as = $_POST['antal_steg'];
} else {
  $as = 49000;
}
if (isset($_POST['procent_pro'])) {
  $per = $_POST['procent_pro'];
} else {
  $per = 1.0;
}
$userArray = Medlem::getTavlingMedlemmar($stt, $slt, $am, $as, $per);
//print_r($userArray); 

if (count($userArray) > 0) {
  $att = null;
  $emaillist = '';
  $html = "<h2>";
  foreach ($userArray as $arr) {
    foreach ($arr as $key => $value) {
      $att.= $value . "	";
    }
    $att.= "\n";
    $html .= "<a href=\"" . $urlHandler->getUrl('Medlem', URL_VIEW, $arr['id']) . "\">" . $arr['aNamn'] . "</a><br />";
    $emaillist .= $arr['epost'] . ", ";
  }
  $html .= "</h2>";

  //get company name
  foreach ($userArray as $key => $value) {
    $mem = medlem::loadById($userArray[$key]['id']);
    $comp = $mem->getForetagsNamn();
    $userArray[$key]['comp'] = $comp;
  }
  $smarty->assign('userArray', $userArray);

  $file = '/files/tavling/' . $stt . "-" . $slt . '_tavling.txt';
  $smarty->assign('html', htmlspecialchars($html));
  $smarty->assign('emaillist', $emaillist);
  $smarty->assign('fileUrl', $file);
  file_put_contents(ROOT . $file, "id	epost	aNamn	pro	steg\n" . $att);
}
// $stt, $slt, $am, $as, $per
$smarty->assign('startTid_sel', $stt);
$smarty->assign('slutTid_sel', $slt);
$smarty->assign('antalMedlemmar_sel', $am);
$smarty->assign('antalSteg_sel', $as);
$smarty->assign('percent_sel', $per);

// print_r($userArray);
$smarty->assign('dates', $dates);
$smarty->display('tavling.tpl');
?>
