<?php
  require $_SERVER["DOCUMENT_ROOT"] . "/php/init.php";
  Security::demand(ADMIN);
  
  if(!empty($_GET['memberid']) && !empty($_GET['pokal'])){
    $medlem = Medlem::loadById($_GET['memberid']);
    echo "Manual ". $_GET['pokal']."-pokal to ". $medlem->getANamn() .", id = ". $_GET['memberid'] ." added to from Admin by ". $ADMIN->getANamn() . ", Sammanstallning:::nyPokal()"; 
    echo "\nSee also the logfile, /usr/local/motiomera/log/motiomera_xxx.log \n\n";    
    Misc::logMotiomera( $_GET['pokal']."-pokal to ". $medlem->getANamn() .", id = ". $_GET['memberid'] ." added to from Admin by ". $ADMIN->getANamn(), 'INFO');    
    Sammanstallning::nyPokal($medlem, $_GET['pokal'], date("Y-m-d"), 0, 1);
  }else{
    echo '<h3>Det saknas paramatrar!</h3>';
  }
?>