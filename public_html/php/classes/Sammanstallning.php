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

	
	public static function sammanstallPokaler()
	{
		global $db;
		$medlemmar = Medlem::listAll();
		foreach($medlemmar as $medlem) {
			
			if (!$medlem->getPokalStart()) {
				$sql = "SELECT datum FROM " . Steg::TABLE . " WHERE medlem_id = " . $medlem->getId() . " ORDER BY datum	 LIMIT 1";
				$datum = $db->value($sql);
				$start = strtotime($datum);
				
				if ($datum != "") {
					$medlem->setPokalStart(date("Y-m-d", $start));
					$medlem->commit();
				}
			} else {
				$start = strtotime($medlem->getPokalStart());
			}
			
			if ($start != 0) {
				$dayNr = date("w", $start);
				$i = 0;
				while ($dayNr != 1) {
					$i++;
					$dayNr++;
					
					if ($dayNr == 7) $dayNr = 0;
				}
				$start = $start + (60 * 60 * 24 * $i);
				$datum = $start;
				$i = 0;
				while (time() - $datum > (60 * 60 * 24 * self::KVARTAL)) {
					$stop = $datum + (60 * 60 * 24 * self::KVARTAL);
					$steg = $medlem->getStegTotal(date("Y-m-d", $datum) , date("Y-m-d", $stop));
					$guldpokal = false;
					$silverpokal = false;
					
					if ($steg >= self::POKAL_GULD_NIVA) {
						$guldpokal = true;
					} else 
					if ($steg >= self::POKAL_SILVER_NIVA) {
						$silverpokal = true;
					}
					
					if ($guldpokal || $silverpokal) {
						$pokal = ($guldpokal) ? self::P_GULD : self::P_SILVER;
						self::nyPokal($medlem, $pokal, date("Y-m-d", $datum) , $steg);
					}
					$datum = $stop + (60 * 60 * 24);
					$i++;
				}
			}
		}
	}



/**
 * This function iterates all members and counts the number of steps they have taken last week.
 * This is intended to be run once a week 
 * Optionally it is possible to submit year and week and run from motiomera.se/admin/pages/installningar.php, also called DEBUG in the menu
 * Logging to /log/cron_motiomera.log
 *
 * The function is rewritten by krillo 2010-07-30 
 *
 * @return void
 * @author Aller Internet, Kristian Erendi
 */
  public static function sammanstallMedaljer($year=null, $week=null){
    if($year!=null && $week!=null){
      $weekArray = JDate::getDateFromWeek($year, $week);
    }else{
      $weekArray = JDate::addWeek(-1);
    }
    Misc::logMotiomera(date("Y-m-d H:i:s") . " INFO - Start medalj batch, ". $weekArray['year'].", week ". $weekArray['week_number'], 'cron_motiomera.log');
    $medalj = null;
    $i = 0;
    //$medlemmar = Medlem::listAll();
    $medlemmar = Medlem::loadById(6568);
    $medlemmar = array($medlemmar);
    //print_r($weekArray);
    foreach($medlemmar as $medlem) {
      $steg = $medlem->getStegTotal($weekArray['monday']  , $weekArray['sunday'] );
      if ($steg >= self::MEDALJ_GULD_NIVA){
        $medalj = self::M_GULD;
      }else{ 
        if ($steg >= self::MEDALJ_SILVER_NIVA){
          $medalj = self::M_SILVER;
         }
      }
      //echo '$steg: ' . $steg . "\n" .'$medalj: ' . $medalj . "\n" . '$medalj: ' . $medalj . "\n" . 'veckastart: ' . $weekArray['monday'] . "\n" . 'veckastop: ' . $weekArray['sunday'] . "\n";
      //echo 'ar: ' . $weekArray['year'] . "\n" . 'vecka: ' . $weekArray['week_number'] . "\n";
      if($medalj!=null){
        $i++;
        self::nyMedalj($medlem, $medalj, $weekArray['year'], $weekArray['week_number'], $steg, $i);
      }
      $medalj = null;
    }	
  }

	
	
  /**
   * This function inserts a medallion in the db
   * Checks if there allready is one for that week, in that case no insert is made
   * Logging to /log/cron_motiomera.log
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
      Misc::logMotiomera(date("Y-m-d H:i:s"). " OK - nbr $i New medalj for medlemId: ". $medlem->getId() .", $medalj, steg: $steg", 'cron_motiomera.log');
    }else {
      Misc::logMotiomera(date("Y-m-d H:i:s"). " ERROR - nbr $i Duplicate medalj for medlemId: ". $medlem->getId() .", $medalj, steg: $steg", 'cron_motiomera.log');
    }
  }
	
	
	private static function nyPokal(Medlem $medlem, $pokal, $datum, $steg)
	{
		global $db;
		$sql = "SELECT count(*) FROM " . self::POKAL_TABLE . " WHERE medlem_id = " . $medlem->getId() . " AND datum = '$datum'";
		
		if ($db->value($sql) == "0") {
			$sql = "INSERT INTO " . self::POKAL_TABLE . " values (null, " . $medlem->getId() . ", '$pokal', $steg, '$datum')";
			$db->nonquery($sql);
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
