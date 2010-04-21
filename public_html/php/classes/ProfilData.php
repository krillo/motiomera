<?php

/**
* Class and Function List:
* Function list:
* - __construct()
* - __getEmptyObject()
* - listAll()
* - loadById()
* - loadByNamn()
* - getMedlemmar()
* - getNamn()
* - getBeskrivning()
* - getProfilDataVals()
* - setNamn()
* - setBeskrivning()
* - delete()
* Classes list:
* - ProfilData extends Mobject
*/
/*
*/

class ProfilData extends Mobject
{
	
	protected $id; // int

	
	protected $namn; // string

	
	protected $beskrivning; // string

	
	protected $profilDataVals; // array av Object:ProfilDataVal

	
	protected $profilDataValIds; // array av int

	
	protected $fields = array(
		"namn" => "str",
		"beskrivning" => "str"
	);
	const MIN_LENGTH_NAMN = 3;
	const RELATION_TABLE = "mm_medlemprofildataval";

	// Felmeddelanden:
	// -1  Namnet är för kort

	
	public function __construct($namn, $beskrivning, $dummy_object = false)
	{
		
		if (!$dummy_object) {
			$this->setNamn($namn);
			$this->setBeskrivning($beskrivning);
			$this->commit();
		}
	}
	
	public static function __getEmptyObject()
	{
		$class = get_class();
		return new $class(null, null, true);
	}

	// PUBLIC FUNCTIONS
	
	public static function listAll()
	{
		return parent::lister(get_class());
	}
	
	public static function loadById($id)
	{
		return parent::loadById($id, get_class());
	}
	
	public static function loadByNamn($namn)
	{
		global $db;
		$sql = "SELECT id FROM " . self::classToTable(get_class()) . " WHERE namn='" . SECURITY::secure_postdata($namn) . "'";
		$id = $db->value($sql);
		
		if ($id) {
			return parent::loadById($id, get_class());
		} else {
			return false;
		}
	}

	/*
	* Returnerar medlemmar som har satt ett värde på detta attribut
	*/
	
	public function getMedlemmar()
	{
		global $db;
		$sql = "SELECT medlem_id FROM " . self::RELATION_TABLE . "
				WHERE
					profilData_id = '" . $this->getId() . "'";
		return $db->valuesAsArray($sql);
	}

	// SETTERS & GETTERS
	
	public function getNamn()
	{
		return $this->namn;
	}
	
	public function getBeskrivning()
	{
		return $this->beskrivning;
	}
	
	public function getProfilDataVals()
	{
		
		if (!$this->profilDataVals) {
			$this->profilDataVals = ProfilDataVal::listByprofilData($this);
		}
		return $this->profilDataVals;
	}
	
	public function setNamn($namn)
	{
		
		if (strlen($namn) < self::MIN_LENGTH_NAMN) {
			throw new MedlemException("Namnet är för kort: $namn", -1);
		}
		$this->namn = $namn;
	}
	
	public function setBeskrivning($beskrivning)
	{
		$this->beskrivning = $beskrivning;
	}
	
	public function delete()
	{
		Security::demand(SUPERADMIN);
		parent::delete();
	}
}
?>
