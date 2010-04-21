<?php
/**
* Class and Function List:
* Function list:
* - __construct()
* - getMedlem()
* - listSteg()
* - listStegByDatum()
* - listDatum()
* - listDatumMedSteg()
* - setMedlem()
* Classes list:
* - MinaSteg
* - MinaStegException extends Exception
*/

class MinaSteg
{
	
	protected $medlem; // object: Medlem

	
	protected $steg = array(); // objects: Steg

	
	protected $datum = array(); // vilka datum som innehåller stegrapporter

	const STEG_TABLE = "mm_steg";

	// Felkoder
	// -1 $datum måste vara i formatet Y-m-d

	
	public function __construct(Medlem $medlem)
	{
		$this->setMedlem($medlem);
	}

	// SETTERS & GETTERS //////////////////////////////////////
	
	public function getMedlem()
	{
		return $this->medlem;
	}
	
	public function listSteg()
	{
		
		if (!$this->steg) {
			$this->steg = Steg::listByMedlem($this->getMedlem());
		}
		return $this->steg;
	}
	
	public function listStegByDatum($datum)
	{
		
		if (!Misc::isDate($datum, "Y-m-d")) throw new MinaStegException('$datum måste vara i formatet Y-m-d', -1);
		$result = array();
		foreach($this->listSteg() as $steg) {
			
			if (substr($steg->getDatum() , 0, 10) == $datum) {
				$result[$steg->getId() ] = $steg;
			}
		}
		return $result;
	}
	
	public function listDatum()
	{
		
		if (!$this->datum) {
			global $db;
			$sql = "SELECT DISTINCT datum FROM " . self::STEG_TABLE . " WHERE medlem_id = " . $this->getMedlem()->getId() . " ORDER BY datum DESC";
			$datum = $db->valuesAsArray($sql);
			$this->datum = $datum;
		}
		return $this->datum;
	}
	
	public function listDatumMedSteg()
	{
		$result = array();
		foreach($this->listDatum() as $datum) {
			$result[$datum] = $this->listStegByDatum($datum);
		}
		return $result;
	}
	
	public function setMedlem(Medlem $medlem)
	{
		$this->medlem = $medlem;
	}
}

class MinaStegException extends Exception
{
}
?>
