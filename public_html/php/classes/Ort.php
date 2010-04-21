<?php
/**
* Class and Function List:
* Function list:
* - __construct()
* - __getEmptyObject()
* - loadById()
* - listAll()
* - listByKommun()
* - getNamn()
* - getKommunId()
* - getKommun()
* - getHuvudort()
* - setNamn()
* - setKommunId()
* - setKommun()
* - setHuvudort()
* Classes list:
* - Ort extends Mobject
* - OrtException extends Exception
*/

class Ort extends Mobject
{
	
	protected $id; // int

	
	protected $namn; // string

	
	protected $kommun_id; // int

	
	protected $kommun; // object: Kommun

	
	protected $fields = array(
		"kommun_id" => "int",
		"namn" => "str"
	);
	
	public function __construct($namn, Kommun $kommun, $huvudort, $dummy_object = false)
	{
		
		if (!$dummy_object) {
			$this->setNamn($namn);
			$this->setKommun($kommun);
			$this->setHuvudort($huvudort);
			$this->commit();
		}
	}
	
	public static function __getEmptyObject()
	{
		$class = get_class();
		return new $class(null, Kommun::__getEmptyObject() , null, true);
	}

	// Felkoder
	// -1 $huvudort måste vara 0 eller 1

	// STATIC METHODS /////////////////////////////////////////

	
	public static function loadById($id)
	{
		return parent::loadById($id, get_class());
	}
	
	public static function listAll()
	{
		return parent::lister(get_class());
	}
	
	public static function listByKommun(Kommun $kommun)
	{
		global $db;
		$sql = "SELECT id FROM " . self::classToTable(get_class()) . " WHERE kommun_id = " . $kommun->getId();
		$ids = $db->valuesAsArray($sql);
		return self::listByIds(get_class() , $ids);
	}

	// SETTERS & GETTERS //////////////////////////////////////
	
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
		
		if (!$this->kommun && $this->kommun_id != "") $this->kommun = Kommun::loadById($this->kommun_id);
		return $this->kommun;
	}
	
	public function getHuvudort()
	{
		return $this->huvudort;
	}
	
	public function setNamn($namn)
	{
		$this->namn = $namn;
	}
	
	public function setKommunId($kommun_id)
	{
		unset($this->kommun);
		$this->kommun_id($kommun_id);
	}
	
	public function setKommun(Kommun $kommun)
	{
		
		if ($this->getKommun() != null && $this->getKommun()->getId() != $kommun->getId()) {
			$this->getKommun()->setHuvudort(null);
			$this->getKommun()->commit();
		}
		$this->kommun = null;
		$this->kommun_id = $kommun->getId();
	}
	
	public function setHuvudort($huvudort)
	{
		$kommunHuvudort = $this->getKommun()->getHuvudort();
		
		if ($huvudort != 0 && $huvudort != 1) throw new OrtException('$huvudort måste vara 1 eller 0', -1);
		
		if ($huvudort == 0 && $kommunHuvudort != null && $kommunHuvudort->getId() == $this->getId()) {
			$this->getKommun()->setHuvudort(null);
			$this->getKommun()->commit();
		} else 
		if ($huvudort == 1) {
			$this->getKommun()->setHuvudort($this);
			$this->getKommun()->commit();
		}
	}
}

class OrtException extends Exception
{
}
?>
