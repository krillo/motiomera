<?php
/**
* Class and Function List:
* Function list:
* - __construct()
* - updateMal()
* - listAvalible()
* - listAvalibleNamn()
* - harMal()
* - addMal()
* - removeMal()
* - removeAllMedlemMal()
* - getCurrentMal()
* - getStegToNextMal()
* - getKmToNextMal()
* - getUsedSteg()
* - setMedlemId()
* - setMedlem()
* - setMal()
* - getMedlemId()
* - getMedlem()
* - listMal()
* Classes list:
* - MalManager
* - MalManagerException extends Exception
*/

class MalManager
{
	
	protected $medlem_id;
	
	protected $medlem;
	
	protected $mal = array();
	
	protected $stegToNext;
	const RELATION_TABLE = "mm_malMedlem";

	// Felkoder
	// -1 Den här medlemmen har redan ett oavslutat mål

	
	public function __construct(Medlem $medlem)
	{
		$this->setMal(Mal::listByMedlem($medlem));
		$this->setMedlem($medlem);
	}

	// PUBLIC FUNCTIONS ///////////////////////////////////////
	
	public function updateMal()
	{
		
		if ($this->harMal()) {
			
			if ($this->getStegToNextMal() == 0) {

				// markera som klart
				global $db;
				$sql = "UPDATE " . self::RELATION_TABLE . " SET klarDatum = '" . date("Y-m-d") . "' WHERE medlem_id = " . $this->getMedlemId() . " AND mal_id = " . $this->getCurrentMal()->getId();
				$db->nonquery($sql);
			}
		}
	}
	
	public function listAvalible()
	{
		global $db;
		$sql = "SELECT count(*) FROM " . self::RELATION_TABLE . " WHERE medlem_id = " . $this->getMedlemId();
		$count = $db->value($sql);
		
		if ($count == 0) {
			return Mal::listAll();
		} else {
			return Mal::listByIds(array_keys($this->listMal()) , true);
		}
	}
	
	public function listAvalibleNamn()
	{
		$namn = array();
		foreach($this->listAvalible() as $mal) {
			$namn[$mal->getId() ] = $mal->getNamn();
		}
		return $namn;
	}
	
	public function harMal()
	{
		global $db;
		$sql = "SELECT count(*) FROM " . self::RELATION_TABLE . " WHERE medlem_id = " . $this->getMedlemId() . " AND klarDatum is null";
		$count = $db->value($sql);
		
		if ($count != 0) return true;
		else return false;
	}
	
	public function addMal(Mal $mal)
	{
		
		if ($this->harMal()) throw new MalManagerException('Den här medlemmen har redan ett oavslutat mål', -1);
		global $db;
		$sql = "INSERT INTO " . self::RELATION_TABLE . " (medlem_id, mal_id) values (" . $this->getMedlemId() . ", " . $mal->getId() . ")";
		$db->nonquery($sql);
		$this->mal[] = $mal;
	}
	
	public function removeMal()
	{
		Security::demand(USER, $this->getMedlem());
		global $db;
		$sql = "DELETE FROM " . self::RELATION_TABLE . " WHERE medlem_id = " . $this->getMedlemId() . " AND mal_id = " . $this->getCurrentMal()->getId();
		$db->nonquery($sql);
		unset($this->mal[$this->getCurrentMal()->getId() ]);
	}
	
	public static function removeAllMedlemMal(Medlem $medlem)
	{
		Security::demand(ADMIN);
		global $db;
		$sql = "DELETE FROM " . self::RELATION_TABLE . " WHERE medlem_id = " . $medlem->getId();
		$db->nonquery($sql);
	}
	
	public function getCurrentMal()
	{
		return end($this->mal);
	}
	
	public function getStegToNextMal()
	{
		
		if (!$this->stegToNext) {
			$this->stegToNext = Steg::kmToSteg($this->getCurrentMal()->getAvstand()) - ($this->getMedlem()->getStegTotal() - $this->getUsedSteg());
			
			if ($this->stegToNext < 0) $this->stegToNext = 0;
		}
		return $this->stegToNext;
	}
	
	public function getKmToNextMal()
	{
		return Steg::stegToKm($this->getStegToNextMal());
	}
	
	public function getUsedSteg()
	{
		$km = 0;
		foreach($this->listMal() as $mal) {
			
			if ($mal->getId() != $this->getCurrentMal()->getId()) $km+= $mal->getAvstand();
		}
		return Steg::kmToSteg($km);
	}

	// PRIVATE FUNCTIONS //////////////////////////////////////
	
	private function setMedlemId($id)
	{
		$this->medlem_id = $id;
		$this->medlem = null;
	}
	
	private function setMedlem(Medlem $medlem)
	{
		$this->medlem = $medlem;
		$this->medlem_id = $medlem->getId();
	}
	
	private function setMal($mal)
	{
		$this->mal = $mal;
	}

	// SETTERS & GETTERS //////////////////////////////////////
	
	public function getMedlemId()
	{
		return $this->medlem_id;
	}
	
	public function getMedlem()
	{
		return $this->medlem;
	}
	
	public function listMal()
	{
		return $this->mal;
	}
}

class MalManagerException extends Exception
{
}
?>
