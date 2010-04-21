<?php
/**
* Class and Function List:
* Function list:
* - __construct()
* - __getEmptyObject()
* - loadById()
* - listAll()
* - listByMedlem()
* - listPath()
* - getLatestKommunByMedlem()
* - getCurrentKommunByMedlem()
* - cleanTempStrackor()
* - approveTempStrackor()
* - deleteUserStrackor()
* - getMedlemId()
* - getMedlem()
* - getKommunTillId()
* - getKommunTill()
* - getKlarDatum()
* - setMedlemId()
* - setMedlem()
* - setKommunTillId()
* - setKommunTill()
* - setKlarDatum()
* - setTempStatus()
* - getTempStatus()
* Classes list:
* - Stracka extends Mobject
* - StrackaException extends Exception
*/

class Stracka extends Mobject
{
	
	protected $id; // int

	
	protected $medlem_id; // int

	
	protected $medlem; // object: Medlem

	
	protected $kommunTill_id; // int

	
	protected $kommunTill; // object: Kommun

	
	protected $klarDatum; // datetime

	
	protected $temp; // ej bekräftad sträcka

	
	protected $fields = array(
		"medlem_id" => "int",
		"kommunTill_id" => "int",
		"klarDatum" => "str",
		"temp" => "int",
		'static' => 'int',
	);

	// Felkoder:
	// -1 Felaktigt format

	
	public function __construct(Kommun $kommunTill, $medlem = null, $static = 0, $dummy_object = false)
	{
		
		if (!$dummy_object) {
			
			if ($medlem == null) {
				global $USER;
				$medlem = $USER;
			}
			$this->setMedlem($medlem);
			$this->setKommunTill($kommunTill);
			$this->setStatic($static);

			// En sträcka är alltid temp innan den godkänns:
			$this->setTempStatus(1);
			$this->commit();
			$medlem->uppdateraRutt();
		}
	}
	
	public static function __getEmptyObject()
	{
		$class = get_class();
		return new $class(Kommun::__getEmptyObject() , null, null, true);
	}

	// STATIC FUNCTIONS
	
	public static function loadById($id)
	{
		return parent::loadById($id, get_class());
	}
	
	public static function listAll()
	{
		return parent::lister(get_class());
	}
	
	public static function listByMedlem(Medlem $medlem)
	{
		return parent::lister(get_class() , "medlem_id", $medlem->getId() , "id");
	}
	
	public static function listPath($medlem)
	{
		global $db;
		$sql = "SELECT kommunTill_id FROM " . self::classToTable(get_class()) . " WHERE medlem_id = " . $medlem->getId();
		
		if ($mod == "left") $sql.= " WHERE klarDatum = '0000-00-00 00:00:00'";
		$sql.= " ORDER BY id DESC";
		$ids = $db->valuesAsArray($sql);
		return Kommun::loadByIds($ids);
	}
	
	public static function getLatestKommunByMedlem(Medlem $medlem)
	{
		global $db;
		$sql = "SELECT kommunTill_id FROM " . self::classToTable(get_class()) . " WHERE medlem_id = " . $medlem->getId() . " ORDER BY id DESC LIMIT 1";
		$res = $db->query($sql);
		
		if (mysql_num_rows($res) == 0) {
			return null;
		} else {
			$data = mysql_fetch_assoc($res);
			return Kommun::loadById($data["kommunTill_id"]);
		}
	}

	// deprecated:
	
	public static function getCurrentKommunByMedlem(Medlem $medlem)
	{
		global $db;
		$sql = "SELECT kommunTill_id FROM " . self::classToTable(get_class()) . " WHERE medlem_id = " . $medlem->getId();
		$res = $db->query($sql);
		
		if (mysql_num_rows($res) == 0) {
			return null;
		} else {
			$data = mysql_fetch_assoc($res);
			return Kommun::loadById($data["kommunTill_id"]);
		}
	}
	
	public static function cleanTempStrackor(Medlem $medlem)
	{
		$strackaList = parent::multiLister(get_class() , array(
			"medlem_id" => $medlem->getID() ,
			"temp" => "1"
		));
		foreach($strackaList as $stracka) {
			$stracka->delete();
		}
	}
	
	public static function approveTempStrackor(Medlem $medlem)
	{
		$strackaList = parent::multiLister(get_class() , array(
			"medlem_id" => $medlem->getID() ,
			"temp" => "1"
		));
		foreach($strackaList as $stracka) {
			$stracka->setTempStatus(0);
			$stracka->commit();
		}
	}
	
	public static function deleteUserStrackor(Medlem $medlem, $cleared = true)
	{
		
		if ((Security::authorized(ADMIN)) or (Security::authorized(USER, $medlem))) {
			
			if ($cleared) {
				$strackor = parent::lister(get_class() , "medlem_id", $medlem->getId());
			} else {
				$strackor;
			}
			foreach($strackor as $stracka) {
				$stracka->delete();
			}
		}
	}

	// SETTERS & GETTERS //////////////////////////////////////
	
	public function getMedlemId()
	{
		return $this->medlem_id;
	}
	
	public function getMedlem()
	{
		
		if (!$this->medlem) $this->medlem = Medlem::loadById($this->getMedlemId());
		return $this->medlem;
	}
	
	public function getKommunTillId()
	{
		return $this->kommunTill_id;
	}
	
	public function getKommunTill()
	{
		
		if (!$this->kommunTill) $this->kommunTill = Kommun::loadById($this->getKommunTillId());
		return $this->kommunTill;
	}
	
	public function getKlarDatum()
	{
		return $this->klarDatum;
	}
	
	public function setMedlemId($medlem_id)
	{
		$this->medlem_id = $medlem_id;
		unset($this->medlem);
	}
	
	public function setMedlem(Medlem $medlem)
	{
		$this->medlem = $medlem;
		$this->medlem_id = $medlem->getId();
	}
	
	public function setKommunTillId($kommunTill_id)
	{
		$this->kommunTill_id = $kommunTill_id;
		unset($this->kommunTill);
	}
	
	public function setKommunTill(Kommun $kommunTill)
	{
		$this->kommunTill = $kommunTill;
		$this->kommunTill_id = $kommunTill->getId();
	}
	
	public function setKlarDatum($datum)
	{

		//		if(!Misc::isDate($datum, "Y-m-d H:i:s"));
		//			throw new StrackaException("Felaktigt format", -1);

		$this->klarDatum = $datum;
	}
	
	public function setTempStatus($i)
	{
		$this->temp = $i;
	}
	
	public function getTempStatus()
	{
		return $this->temp;
	}
	
	/**
	 * Function getStatic
	 * 
	 * Gets the value set to static
	 *
	 * Example:
	 *      getStatic  (  )
	 */
	public function getStatic()
	{
		return isset($this->static) ? $this->static : false;
	}
	
	/**
	 * Function setStatic
	 * 
	 * Sets the value to static 
	 *
	 * Example:
	 *      setStatic  ( 1 )
	 */
	public function setStatic($set)
	{
		$this->static = $set;
	}
}

class StrackaException extends Exception
{
}
?>
