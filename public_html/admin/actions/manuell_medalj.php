<?php
require $_SERVER["DOCUMENT_ROOT"] . "/php/init.php";
Security::demand(ADMIN);
if(!empty($_GET['year']) && !empty($_GET['weekstart']) && !empty($_GET['weekstop'])){
  echo "This is executed via Sammanstallning:sammanstallMedaljer(".$_GET['year'].", ".$_GET['weekstart'].") \nSee also the logfile, /usr/local/motiomera/log/motiomera.log \n\n";
  Misc::logMotiomera("Manual medalj batch started from Admin by ". $ADMIN->getANamn() .", ". $_GET['year']." from week ". $_GET['weekstart']." to week ". $_GET['weekstop'] , 'INFO');
  for ($i=$_GET['weekstart']; $i <= $_GET['weekstop']; $i++) { 
    Sammanstallning::sammanstallMedaljer($_GET['year'], $i);
  }  
}else{
  echo '<h3>Det saknas paramatrar!</h3>';
}
?>