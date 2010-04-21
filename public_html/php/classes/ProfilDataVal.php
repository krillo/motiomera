<?php

/**
* Class and Function List:
* Function list:
* - __construct()
* - __getEmptyObject()
* - listAll()
* - listByprofilData()
* - getProfilDataObject()
* - loadById()
* - getVarde()
* - getProfilDataId()
* - setVarde()
* - setProfilDataId()
* - delete()
* Classes list:
* - ProfilDataVal extends Mobject
*/
/*
Medlemsklassen sköter allt som har ankytning till webbplatsens medlemmar.
*/

class ProfilDataVal extends Mobject
{
	
	protected $id; // int

	
	protected $profilDataId; // int

	
	protected $varde; // string

	
	protected $profilData; // Object: ProfilData

	
	protected $fields = array(
		"varde" => "str",
		"profilDataId" => "int"
	);
	const MIN_LENGTH_NAMN = 1;

	// Felmeddelanden:
	// -1  Vardeet är för kort

	
	public function __construct($varde, $profilDataId, $dummy_object = false)
	{
		
		if (!$dummy_object) {
			$this->setVarde($varde);
			$this->setProfilDataId($profilDataId);
			$this->commit();
		}
	}
	
	public static function __getEmptyObject()
	{
		$class = get_class();
		return new $class(null, null, true);
	}

	// STATIC FUNCTIONS
	
	public static function listAll()
	{
		return parent::lister(get_class());
	}
	
	public static function listByprofilData($profilData)
	{
		global $db;
		$sql = "SELECT id FROM " . self::classToTable(get_class()) . " WHERE profilDataId='" . SECURITY::secure_postdata($profilData->getId()) . "'";
		return self::listByIds(get_class() , $db->valuesAsArray($sql));
	}
	
	public function getProfilDataObject()
	{
		return ProfilData::loadById($this->profilDataId);
	}
	
	public static function loadById($id)
	{
		return parent::loadById($id, get_class());
	}

	// SETTERS & GETTERS
	
	public function getVarde()
	{
		return $this->varde;
	}
	
	public function getProfilDataId()
	{
		return $this->profilDataId;
	}
	
	public function setVarde($varde)
	{
		
		if (strlen($varde) < self::MIN_LENGTH_NAMN) {
			throw new MedlemException("Vardeet är för kort: $varde", -1);
		}
		$this->varde = $varde;
	}
	
	public function setProfilDataId($profilDataId)
	{
		$this->profilDataId = $profilDataId;
	}
	
	public function delete()
	{
		Security::demand(SUPERADMIN);
		parent::delete();
	}
}
?>
