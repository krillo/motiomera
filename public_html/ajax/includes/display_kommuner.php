<?php
 /**
  * 12-12-29 Kristian Erendi, Reptilo.se
  */
header("Content-Type: text/html; charset=utf-8");
require_once($_SERVER["DOCUMENT_ROOT"]."/php/init.php");
!empty($_REQUEST['mm_id']) ? $id = addslashes($_REQUEST['mm_id']) : $id = '107'; 

$kommuner = Kommun::listAllOrderBy($id, 'lan');
//print_r($kommuner);
$out = '<div>';
$lan = '';
foreach ($kommuner as $key => $kommun) {
  if ($lan != $kommun['lan']) {
    $lan = $kommun['lan'];
    $lan_slug = Misc::url_slug($kommun['lan']);
    $out .= '</div>';
    $out .= '<div class="kommunvapenlistlan" id="'.$lan_slug.'">';
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