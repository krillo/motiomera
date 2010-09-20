<?php
/**
* Class and Function List:
* Function list:
* - sammanstallPokaler()
* - sammanstallMedaljer()
* - nyMedalj()
* - nyPokal()
* - listMedaljer()
* - listPokaler()
* - res2Array()
* Classes list:
* - Sammanstallning
*/

class Sammanstallning
{
	const MEDALJ_TABLE = "mm_medalj";
	const POKAL_TABLE = "mm_pokal";
	const M_GULD = "guld";
	const M_SILVER = "silver";
	const P_GULD = "guld";
	const P_SILVER = "silver";
	const MEDALJ_SILVER_NIVA = 49000;
	const MEDALJ_GULD_NIVA = 77000;
	const POKAL_GULD_NIVA = 1000000;
	const POKAL_SILVER_NIVA = 637000;
	const KVARTAL = 91; // antal dagar i ett kvartal



    public static function krillo(){
      echo date('m:i') . " Sammanstallning::krillo - executing \n";
    }


/**
 * this function iterates all users and calculates if a pokal is deserved.
 * The start date is calculated like this: 
 * 1. if it is missing - date of the first recorded steg is set
 * 2. if it is less than stop date (start + self::KVARTAL) - nothing is done
 * 3. if it is more than stop date (start + self::KVARTAL) - todays date is set as a new start date
 *
 * If a silver-pokal is deserved then the start date is still the same
 * If a gold-pokal is deserved then todays date is set as the new start date
 * 
 * This function should be run every morning as a batch
 *
 * @return void
 * @author Aller Internet, Kristian Erendi
 */
  public static function sammanstallPokaler(){
    global $db;
    $today = date("Y-m-d");
    $i = 0;
    $nbr = 0;
    $pokal = null;
    $medlemmar = Medlem::listAll();
    //$medlemmar = Medlem::loadById(6568);
    //$medlemmar = array($medlemmar);
    Misc::logMotiomera("Start: Sammanstallning::sammanstallPokaler(), ". sizeof($medlemmar). " members to run throuh", 'info');
    foreach($medlemmar as $medlem) {
      $nbr++;
      //echo $nbr . ' - medlem: ' . $medlem->getId() .' '. $medlem->getANamn() . "\n";
      try{
       if (!$medlem->getPokalStart()){
         //no start date, create one
         $sql = "SELECT datum FROM " . Steg::TABLE . " WHERE medlem_id = " . $medlem->getId() . " ORDER BY datum	 ASC LIMIT 1";
         $datum = $db->value($sql);
         if ($datum != "") {
           $pokalStartDate = $datum;
         }
       } else { 
         //get existing start date
         $pokalStartDate = $medlem->getPokalStart();
       }

       $pokalStopDateArray = JDate::addDays(self::KVARTAL + 1, $pokalStartDate);  //one extra day slack on date
       if(time() > $pokalStopDateArray['date_unix']){
        //pokal date expired, use this date to check if pokal is deserved and reset pokal start date
        $steg = $medlem->getStegTotal($pokalStartDate , $today);
        if ($steg >= self::POKAL_GULD_NIVA) {
          $pokal = self::P_GULD;
        }else{
          if ($steg >= self::POKAL_SILVER_NIVA) {
            $pokal = self::P_SILVER;
          }
          // no pokal deserved
        }
        if($pokal!=null){
         $i++;
         self::nyPokal($medlem, $pokal, $today, $steg, $i);
        }
        $pokal = null;
        $medlem->setPokalStart($today);
        $medlem->commit();

       } 
     } catch (Exception $e){
        Misc::logMotiomera("Pokal batch, ". $nbr ." members to run throuh, medlem: ". $medlem->getId() ." ". $medlem->getANamn(), 'ERROR');
        Misc::logMotiomera($e);
     }
   }
   Misc::logMotiomera("End: Sammanstallning::sammanstallPokaler() ", 'info');
  }


/**
 * This function inserts a pokal in the db
 * Checks if there allready is one for that date, in that case no insert is made
 * Logging to /log/motiomera.log
 *
 * @param Medlem $medlem 
 * @param string $pokal 
 * @param string $datum 
 * @param string $steg 
 * @param string $i 
 * @return void
 * @author Aller Internet, Kristian Erendi
 */
  public static function nyPokal(Medlem $medlem, $pokal, $datum, $steg, $i=0){
    global $db;
    $sql = "SELECT count(*) FROM " . self::POKAL_TABLE . " WHERE medlem_id = " . $medlem->getId() . " AND datum = '$datum'";
    if ($db->value($sql) == "0") {
      $sql = "INSERT INTO " . self::POKAL_TABLE . " values (null, " . $medlem->getId() . ", '$pokal', $steg, '$datum')";
      $db->nonquery($sql);
      Misc::logMotiomera("nbr $i New pokal for medlemId: ". $medlem->getId() .", $pokal, steg: $steg", 'OK');
    }else {
      Misc::logMotiomera("nbr $i Duplicate pokal for medlemId: ". $medlem->getId() .", $pokal, steg: $steg", 'ERROR');
    }
  }


/**
 * This function iterates all members and counts the number of steps they have taken last week.
 * This is intended to be run as a batch once a week 
 * Optionally it is possible to submit year and week and run from motiomera.se/admin/pages/installningar.php, also called DEBUG in the admin menu
 * Logging to /log/motiomera.log
 *
 * The function is rewritten by krillo 2010-07-30 
 *
 * @return void
 * @author Aller Internet, Kristian Erendi
 */
  public static function sammanstallMedaljer($year=null, $week=null) {
    $i = 0;
    $nbr = 0;
    $medalj = null;
    try {
      if ($year != null && $week != null) {
        $weekArray = JDate::getDateFromWeek($year, $week);
      } else {
        $weekArray = JDate::addWeeks(-1);
      }
      Misc::logMotiomera("Start: Sammanstallning::sammanstallMedaljer() , year: " . $weekArray['year'] . ", week: " . $weekArray['week_number'], 'INFO');
      $medlemmar = Medlem::listAll();
      //$medlemmar = Medlem::loadById(6568);
      //$medlemmar = array($medlemmar);
      //print_r($weekArray);
      Misc::logMotiomera(count($medlemmar) . " of members to itterate for new medals ", 'INFO');
      foreach ($medlemmar as $medlem) {
        $nbr++;
        $steg = $medlem->getStegTotal($weekArray['monday'], $weekArray['sunday']);
        if ($steg >= self::MEDALJ_GULD_NIVA) {
          $medalj = self::M_GULD;
        } else {
          if ($steg >= self::MEDALJ_SILVER_NIVA) {
            $medalj = self::M_SILVER;
          }
        }
        //echo '$steg: ' . $steg . "\n" .'$medalj: ' . $medalj . "\n" . '$medalj: ' . $medalj . "\n" . 'veckastart: ' . $weekArray['monday'] . "\n" . 'veckastop: ' . $weekArray['sunday'] . "\n";
        //echo 'ar: ' . $weekArray['year'] . "\n" . 'vecka: ' . $weekArray['week_number'] . "\n";
        if ($medalj != null) {
          $i++;
          self::nyMedalj($medlem, $medalj, $weekArray['year'], $weekArray['week_number'], $steg, $i);
        }
        $medalj = null;
      }
    } catch (Exception $e) {
      Misc::logMotiomera("Medalj batch, " . $nbr . " members to run throuh, medlem: " . $medlem->getId() . " " . $medlem->getANamn(), 'ERROR');
      Misc::logMotiomera($e);
    }
    Misc::logMotiomera("End: Sammanstallning::sammanstallMedaljer()", 'INFO');
  }

	
	
  /**
   * This function inserts a medallion in the db
   * Checks if there allready is one for that week, in that case no insert is made
   * Logging to /log/motiomera.log
   *
   * @param Medlem $medlem 
   * @param string $medalj 
   * @param string $ar 
   * @param string $vecka 
   * @param string $steg 
   * @param string $i 
   * @return void
   * @author Aller Internet, Kristian Erendi
   */
  private static function nyMedalj(Medlem $medlem, $medalj, $ar, $vecka, $steg, $i=0){
    global $db;
    $sql = "SELECT count(*) FROM " . self::MEDALJ_TABLE . " WHERE medlem_id = " . $medlem->getId() . " AND ar = " . $ar . " AND vecka = " . $vecka;

    if ($db->value($sql) == "0") {
      $sql = "INSERT INTO " . self::MEDALJ_TABLE . " VALUES (null, " . $medlem->getId() . ", '$medalj', $steg, $vecka, $ar);";
      $db->nonquery($sql);
      Misc::logMotiomera("nbr $i New medalj for medlemId: ". $medlem->getId() .", $medalj, steg: $steg", 'OK');
    }else {
      Misc::logMotiomera("nbr $i Duplicate medalj for medlemId: ". $medlem->getId() .", $medalj, steg: $steg", 'ERROR');
    }
  }
	
	

	
	public static function listMedaljer(Medlem $medlem = null, $medalj = null, $vecka = null, $ar = null)
	{
		global $db;
		$sql = "SELECT * FROM " . self::MEDALJ_TABLE . " WHERE 1 = 1 ";
		
		if ($medlem) $sql.= "AND medlem_id = " . $medlem->getId() . " ";
		
		if ($medalj) $sql.= "AND medalj = '" . $medalj . "' ";
		
		if ($vecka) $sql.= "AND vecka = $vecka ";
		
		if ($ar) $sql.= "AND ar = $ar ";
		$res = $db->query($sql);
		return self::res2Array($res, "medalj");
	}
	
	public static function listPokaler(Medlem $medlem = null, $pokal = null)
	{
		global $db;
		$sql = "SELECT * FROM " . self::POKAL_TABLE . " WHERE 1 = 1 ";
		
		if ($medlem) $sql.= "AND medlem_id = " . $medlem->getId() . " ";
		
		if ($pokal) $sql.= "AND pokal = '$pokal'";
		$res = $db->query($sql);
		return self::res2Array($res, "pokal");
	}
	
	private static function res2Array($res, $typ)
	{
		$result = array();
		while ($row = mysql_fetch_assoc($res)) {
			
			if ($typ == "medalj") $result[$row["ar"] . "-" . $row["vecka"]] = $row;
			else $result[$row["datum"]] = $row;
		}
		return $result;
	}
}
?>
