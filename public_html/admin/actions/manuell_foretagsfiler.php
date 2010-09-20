<?php
require $_SERVER["DOCUMENT_ROOT"] . "/php/init.php";
Security::demand(ADMIN);
if (!empty($_GET['action'])) {
  switch ($_GET['action']) {
    case 'kundnummer':
      echo date('Y-m-d h:i:s') . " [INFO] Start kundnummer from admin by " . $ADMIN->getANamn(). ". See logfile\n";
      Misc::logMotiomera("Start kundnummer from admin by  " . $ADMIN->getANamn(), 'INFO');
      Order::hamtaNyaKundnummer();
      Order::liftTillaggOrderStatus();
      Misc::logMotiomera("End kundnummer from admin \n", 'INFO');
      break;
    case 'pdf':
      echo date('Y-m-d h:i:s') . " [INFO] Start create PDF-files from admin by " . $ADMIN->getANamn(). ". See logfile\n";
      Misc::logMotiomera("Start create PDF-files from admin by " . $ADMIN->getANamn(), 'INFO');
      Foretag::skapaFiler();
      Misc::logMotiomera("End create PDF-files from admin \n", 'INFO');
      break;
    case 'ftp':
      echo date('Y-m-d h:i:s') . " [INFO] Start put PDF-files on FTP from admin by " . $ADMIN->getANamn(). ". See logfile\n";
      Misc::logMotiomera("Start put PDF-files on FTP from admin by " . $ADMIN->getANamn(), 'INFO');
      Foretag::uploadOrderFilesFTP();
      Misc::logMotiomera("End put PDF-files on FTP from admin \n", 'INFO');
      break;
    default:
      echo '<h3>Det är fel paramatrar!</h3>';
      break;
  }
} else {
  echo '<h3>Det saknas paramatrar!</h3>';
}

?>