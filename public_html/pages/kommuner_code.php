<?php
/**
 * 13-12-27 Kristian Erendi, Reptilo.se
 */
require_once($_SERVER["DOCUMENT_ROOT"] . "/php/init.php");
//error_reporting(E_ALL);
//ini_set('display_errors', '1');
global $USER;
Security::demand(USER);
$kommuner = Kommun::listAllOrderBy($USER->getId(), 'lan');
//print_r($kommuner);
$out = '<div>';
$lan = '';
foreach ($kommuner as $key => $kommun) {
  if ($lan != $kommun['lan']) {
    $lan = $kommun['lan'];
    $out .= '</div>';
    $out .= '<div class="kommunvapenlistlan">';
    $out .= "<h2>$lan</h2>";
  }
  $out .= '<a href="/kommun/' . $kommun['namn'] . '/" title="' . $kommun['namn'] . '" class="kommunvapenlistbox" >';
  $out .= '<div class="kommunvapenlist" style="background-image:url(\'/files/kommunbilder/thumb_vapen_' . $kommun['id'] . '.jpg\');">';
  if(!empty($kommun['medlem_id'])){
    $out .= '<img src="../../img/icons/gronbock.png" alt="Klarat quiz" class="kommunvapenklarat">';
  }
  $out .= '</div><div style="clear: left;">' . $kommun['namn'] . '</div></a>';
}
$out .= '</div></div>';
echo $out;