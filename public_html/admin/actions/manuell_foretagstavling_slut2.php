<?php
require $_SERVER["DOCUMENT_ROOT"] . "/php/init.php";
Security::demand(ADMIN);
if(!empty($_GET['date'])){
  $date = $_GET['date'];
  if(is_numeric($date) && strlen($date) == 8){
    echo "This is executed via Foretag::saveAndEndForetagsTavling(".$date.") \nSee also the logfile, /usr/local/motiomera/log/motiomera.log \n\n";
    Misc::logMotiomera("Manual start of 'End and save competition data' started from Admin by ". $ADMIN->getANamn() .", Date: ". $date , 'INFO');
    Foretag::saveAndEndForetagsTavling($date);
  }else{
    echo '<h3>Det verkar vara fel format på datumet!</h3>';
  }
}else{
  echo '<h3>Det saknas paramatrar!</h3>';
}
?>

