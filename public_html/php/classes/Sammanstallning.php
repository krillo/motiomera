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
	
	public static function sammanstallMedaljer($vecka = null, $ar = null)
	{
		
		if (!$vecka) {
			$vecka = date("W", time() - (60 * 60 * 25 * 7));
		}
		
		if (!$ar) {
			$ar = date("Y");
		}
		$offset = date("N", strtotime(date($ar . "-01-01"))) - 1;
		$veckaStart = strtotime($ar . "-01-01") + (60 * 60 * 24 * 7 * ($vecka - 1)) - (60 * 60 * 24 * $offset);
		$veckaStop = $veckaStart + (60 * 60 * 24 * 6);
		$medlemmar = Medlem::listAll();
		foreach($medlemmar as $medlem) {
			$steg = $medlem->getStegTotal(date("Y-m-d", $veckaStart) , date("Y-m-d", $veckaStop));
			$guldmedalj = false;
			$silvermedalj = false;
			
			if ($steg >= self::MEDALJ_GULD_NIVA) $guldmedalj = true;
			else 
			if ($steg >= self::MEDALJ_SILVER_NIVA) $silvermedalj = true;
			
			if ($guldmedalj || $silvermedalj) {
				$medalj = ($guldmedalj) ? self::M_GULD : self::M_SILVER;
				self::nyMedalj($medlem, $medalj, $ar, $vecka, $steg);
			}
		}
	}
	
	private static function nyMedalj(Medlem $medlem, $medalj, $ar, $vecka, $steg)
	{
		global $db;
		$sql = "SELECT count(*) FROM " . self::MEDALJ_TABLE . " WHERE medlem_id = " . $medlem->getId() . " AND ar = " . $ar . " AND vecka = " . $vecka;
		
		if ($db->value($sql) == "0") {
			$sql = "INSERT INTO " . self::MEDALJ_TABLE . " VALUES (null, " . $medlem->getId() . ", '$medalj', $steg, $vecka, $ar);";
			$db->nonquery($sql);
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
