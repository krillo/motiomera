<?php
/**
* Class and Function List:
* Function list:
* - __construct()
* - __getEmptyObject()
* - loadById()
* - listByIds()
* - listAll()
* - listNamn()
* - listByMedlem()
* - getId()
* - getNamn()
* - getKommunId()
* - getKommun()
* - getAvstand()
* - setNamn()
* - setKommunId()
* - setKommun()
* - setAvstand()
* Classes list:
* - Mal extends Mobject
* - MalException extends Exception
*/

class Mal extends Mobject
{
	
	protected $id; // int

	
	protected $namn; // string

	
	protected $kommun_id; // int

	
	protected $kommun; // object: Kommun

	
	protected $avstand; // int

	
	protected $fields = array(
		"id" => "int",
		"namn" => "str",
		"kommun_id" => "int",
		"avstand" => "int",
	);
	const RELATION_TABLE = "mm_malMedlem";

	// Felkoder
	// -1 Namn får inte vara tomt

	// -2 $avstand måste vara ett heltal

	
	public function __construct($namn, Kommun $kommun, $avstand, $dummy_object = false)
	{
		
		if (!$dummy_object) {
			Security::demand(ADMIN);
			$this->setNamn($namn);
			$this->setKommun($kommun);
			$this->setAvstand($avstand);
			$this->commit();
		}
	}
	
	public static function __getEmptyObject()
	{
		$class = get_class();
		return new $class(null, Kommun::__getEmptyObject() , null, true);
	}

	// STATIC FUNCTION ////////////////////////////////////////
	
	public static function loadById($id)
	{
		return parent::loadById($id, get_class());
	}
	
	public static function listByIds($ids, $notin = false, $order = null)
	{
		return parent::listByIds(get_class() , $ids, $notin, $order);
	}
	
	public static function listAll()
	{
		return parent::lister(get_class());
	}
	
	public static function listNamn($visaKm = true)
	{
		global $db;
		$sql = "SELECT id, namn FROM " . self::classToTable(get_class());
		$res = $db->query($sql);
		$result = array();
		while ($data = mysql_fetch_assoc($res)) {
			$result[$data["id"]] = $data["namn"];
			
			if ($visaKm) $result[$data["id"]].= " (" . $data["avstand"] . " km)";
		}
		return $result;
	}
	
	public static function listByMedlem(Medlem $medlem)
	{
		global $db;
		$sql = "SELECT mal_id FROM " . self::RELATION_TABLE . " WHERE medlem_id = " . $medlem->getId();
		$ids = $db->valuesAsArray($sql);
		return self::listByIds($ids, false, "id DESC");
	}

	// SETTERS & GETTERS //////////////////////////////////////
	
	public function getId()
	{
		return $this->id;
	}
	
	public function getNamn()
	{
		return $this->namn;
	}
	
	public function getKommunId()
	{
		return $this->kommun_id;
	}
	
	public function getKommun()
	{
		
		if (!$this->kommun) $this->setKommun(Kommun::loadById($this->getKommunId()));
		return $this->kommun;
	}
	
	public function getAvstand()
	{
		return $this->avstand;
	}
	
	public function setNamn($namn)
	{
		Security::demand(ADMIN);
		
		if ($namn == "") throw new MalException("Namn får inte vara tomt", -1);
		$this->namn = $namn;
	}
	
	public function setKommunId($id)
	{
		Security::demand(ADMIN);
		$this->kommun_id = $id;
		$this->kommun = null;
	}
	
	public function setKommun(Kommun $kommun)
	{
		Security::demand(ADMIN);
		$this->kommun = $kommun;
		$this->kommun_id = $kommun->getId();
	}
	
	public function setAvstand($avstand)
	{
		Security::demand(ADMIN);
		
		if (!Misc::isInt($avstand)) throw new MalException('$avstand måste vara ett heltal', -2);
		$this->avstand = $avstand;
	}
}

class MalException extends Exception
{
}
?>
