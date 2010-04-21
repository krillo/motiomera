<?php
include $_SERVER["DOCUMENT_ROOT"]  . "/php/init.php";
//Security::demand(ADMIN);






echo "********************* sendRemindAboutSteg_krillo_debug ***************************<br/>";
echo "*** debug only, stripped version of Foretag.sendRemindAboutSte() only printout ***<br/>";
sendRemindAboutSteg_krillo_debug();
echo "<br/><br/><br/>";
echo "********************** saveAndEndForetagsTavling_krillo_debug ***************************<br/>";
echo "*** debug only, stripped version of Foretag.saveAndEndForetagsTavling() only printout ***<br/>";
saveAndEndForetagsTavling_krillo_debug();
    

 
  /**
   *  debug only, stripped version of Foretag.sendRemindAboutSte()
   *  only printout
   */ 
  function sendRemindAboutSteg_krillo_debug(){ 
    global $db;

    $sql = 'SELECT a.id FROM mm_medlem a, mm_foretagsnycklar b, mm_foretag c
    WHERE a.id = b.medlem_id
    AND b.foretag_id = c.id
    AND a.epostBekraftad = 1
    AND UNIX_TIMESTAMP(c.startDatum) >= '. (time() - ((Foretag::TAVLINGSPERIOD_DAGAR) * 86400)) .
    ' AND UNIX_TIMESTAMP(c.startDatum) < '. (time() - ((Foretag::TAVLINGSPERIOD_DAGAR - 4) * 86400));
      
    
    $unixtimestamp_first = (time() - ((Foretag::TAVLINGSPERIOD_DAGAR) * 86400));
    $unixtimestamp_second = (time() - ((Foretag::TAVLINGSPERIOD_DAGAR -4) * 86400));            
    echo "unixtimestamp_first : " . $unixtimestamp_first . " |   " . date("Y-m-d",$unixtimestamp_first) . "<br/>";
    echo "unixtimestamp_second : " . $unixtimestamp_second . " |   " . date("Y-m-d",$unixtimestamp_second) . "<br/>";
    
    
    $slutDatum = date("Y-m-d", time() + (7 * 86400));
    $slutDatum = Misc::dateToTimestamp($slutDatum);

    echo "slutDatum " . $slutDatum . " |  slutDatum " . date("Y-m-d",$slutDatum);
    
    foreach($db->valuesAsArray($sql) as $user) {
      //$i++;
      $medlem = Medlem::loadById($user);
      
      if (isset($medlem)) {
        //$slutVecka = strftime("%V", $slutDatum) + 1;
        //$foretagsSlutVecka = strftime("%V", Misc::dateToTimestamp($medlem->getForetag()->getSlutdatum())) + 1;
        
        //echo  $i .  "  slutVecka  ". $slutVecka . " | foretagsSlutVecka: " . $foretagsSlutVecka;
        
        if ((Misc::isEmail($medlem->getEpost() , false))) {
          echo " " . $medlem->getForetag()->getNamn() . " " . $medlem->getForetag()->getNamn() . " | epost: " . $medlem->getEpost() . "<br/>";
 
         }       
     }
    }
  }
  
 
  /**
   *  debug only, stripped version of Foretag.saveAndEndForetagsTavling()
   *  only printout
   */   
  function saveAndEndForetagsTavling_krillo_debug()
  {
    $emailName = "Tavling avslutad - tisdag";   
    global $db;

    $sql = 'SELECT a.id FROM mm_medlem a, mm_foretagsnycklar b, mm_foretag c
    WHERE a.id = b.medlem_id
    AND b.foretag_id = c.id
    AND a.epostBekraftad = 1
    AND UNIX_TIMESTAMP(c.startDatum) >= '. (time() - ((Foretag::TAVLINGSPERIOD_DAGAR + 3) * 86400)) .
    ' AND UNIX_TIMESTAMP(c.startDatum) < '. (time() - ((Foretag::TAVLINGSPERIOD_DAGAR) * 86400));        

    
    
    
    $unixtimestamp_first = (time() - ((Foretag::TAVLINGSPERIOD_DAGAR + 3 ) * 86400));
    $unixtimestamp_second = (time() - ((Foretag::TAVLINGSPERIOD_DAGAR ) * 86400));
    echo "unixtimestamp_first : " . $unixtimestamp_first . " |   " . date("Y-m-d",$unixtimestamp_first) . "<br/>";
    echo "unixtimestamp_second : " . $unixtimestamp_second . " |   " . date("Y-m-d",$unixtimestamp_second) . "<br/><br/>";
            
    
    $slutDatum = date("Y-m-d", time());

    $slutDatum = Misc::dateToTimestamp($slutDatum);
    $tavling = new Tavling('0000-00-00');

    $save = array();
    foreach($db->valuesAsArray($sql) as $user) {
      $medlem = Medlem::loadById($user);
      
      if (isset($medlem)) {

           
        
        $slutVecka = strftime("%V", $slutDatum);
        $foretagsSlutVecka = strftime("%V", Misc::dateToTimestamp($medlem->getForetag()->getSlutdatum()));

        echo "$slutVecka: " . $slutVecka ." | ". "$foretagsSlutVecka: " . $foretagsSlutVecka ."<br>";
        
        if ((Misc::isEmail($medlem->getEpost() , false)) && $medlem->getForetag() && $medlem->getLag()) {
          //self::logEmailSend(false, $emailName, "try", $medlem);
          
         echo " " . $medlem->getForetag()->getNamn() . " " . $medlem->getForetag()->getNamn() . " | epost: " . $medlem->getEpost() . "<br/>";
        
        }
      }
    }   
  }
  
  
  
?>