<?php

require_once($_SERVER["DOCUMENT_ROOT"] . "/php/init.php");
Security::demand(ADMIN);
$smarty = new AdminSmarty();
if (isset($ADMIN) && $ADMIN->getTyp() == "superadmin") {
  global $SETTINGS;
  $settings = print_r($SETTINGS, true);
  $settings .= 'AVATAR_PATH: ' . AVATAR_PATH . '<br/>';
  $settings .= 'LAG_BILD_PATH: ' . LAG_BILD_PATH . '<br/>';
  $settings .= 'FORETAGS_BILD_PATH: ' . FORETAGS_BILD_PATH . '<br/>';
  $settings .= 'LAGNAMN_PATH: ' . LAGNAMN_PATH . '<br/>';
  $settings .= 'VISNINGSBILD_PATH: ' . VISNINGSBILD_PATH . '<br/>';
  $settings .= 'CUSTOM_VISNINGSBILD_PATH: ' . CUSTOM_VISNINGSBILD_PATH . '<br/>';
  $settings .= 'KOMMUN_IMAGES_PATH: ' . KOMMUN_IMAGES_PATH . '<br/>';
  $settings .= 'FOTOALBUM_PATH: ' . FOTOALBUM_PATH . '<br/>';
  $settings .= 'TAB_BOX_TABROOT: ' . TAB_BOX_TABROOT . '<br/>';
  $settings .= 'EMAIL_SEND_LOG_FILE: ' . EMAIL_SEND_LOG_FILE . '<br/>';
  $settings .= 'LOG_DIR: ' . LOG_DIR . '<br/>';
  $smarty->assign("settings", $settings);
  if ($ADMIN->getDebug() == "true") {
    $smarty->assign("isdebug", " checked=\"checked\"");
  }
}
$smarty->display('installningar.tpl');
?>