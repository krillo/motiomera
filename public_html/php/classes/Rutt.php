<?php
/**
* Class and Function List:
* Function list:
* - __construct()
* - getAvstand()
* - getMedlem()
* - listStrackor()
* - getTotalKm()
* - getRutt()
* - getIndexOfLastNonTemp()
* - isLastOnStatic()
* - getLastKommunOnStaticId()
* - getCurrentKommun()
* - getStartKommun()
* - getTotalKmKvar()
* - getCurrentIndex()
* - getKmTillNasta()
* - setMedlem()
* - addFastRutt()
* - deleteFastRutt()
* - getAllFastaUtmaningar()
* - setStaticRouteDoneForUser()
* Classes list:
* - Rutt
*/
/** Hjälpklass */

class Rutt
{
	
	protected $medlem;
	/** objekt: Medlem */
	
	protected $strackor = array();
	/** objects: Stracka */
	
	protected $totalKm;
	/** int */
	
	protected $totalKmKvar;
	/** int */
	
	protected $kmTillNasta;
	/** int */
	
	protected $avstand = array();
	/** avstand */
	
	protected $currentIndex;
	/** int */
	
	protected $nextKommun;
	/** object: Kommun */
	
	protected $rutt = array();
	/** array */
	const TABLE = "mm_stracka";
	const FASTA_RUTTER_TABLE = "mm_fastautmaningar";
	const FASTA_RUTTER_DONE_TABLE = "mm_fastautmaningar_avklarade";
	
	public function __construct($medlem)
	{
		global $db;
		$this->setMedlem($medlem);
		$this->strackor = Stracka::listByMedlem($this->getMedlem());
		$medlemTotalSteg = $medlem->getStegTotal();
		$medlemTotalKm = Steg::stegToKm($medlemTotalSteg);
		$sql = "SELECT DISTINCT kommunTill_id FROM " . self::TABLE . " WHERE medlem_id = " . $this->getMedlem()->getId();
		$kommunIds = $db->valuesAsArray($sql);
		$kommuner = Kommun::listByIds($kommunIds);
		$this->avstand = Kommun::listAvstandByIds($kommunIds);
		$totalKm = 0;
		$i = 0;
		$static;
		$lastStracka = null;
		foreach($this->listStrackor() as $stracka) {
			$thisKommun = $kommuner[$stracka->getKommunTillId() ];
			$thisKm = 0;
			
			if ($lastStracka != null) {
				$thisKm = $this->getAvstand($thisKommun, $kommuner[$lastStracka->getKommunTillId() ]);
			}
			$totalKm+= $thisKm;
			
			if ($stracka->getStatic() == 1) {
				$static = true;
			} else {
				$static = false;
			}
			
			if ($totalKm <= $medlemTotalKm) $this->currentIndex = count($this->rutt);
			$this->rutt[] = array(
				"Kommun" => $thisKommun,
				"TotalKm" => $totalKm,
				"ThisKm" => $thisKm,
				"fastRutt" => $static,
				"id" => $stracka->getId() ,
				"temp" => $stracka->getTempStatus()
			);
			$lastStracka = $stracka;
		}
		
		if (isset($this->rutt[$this->currentIndex + 1])) $this->kmTillNasta = round($this->rutt[$this->currentIndex + 1]["TotalKm"] - Steg::stegToKm($medlem->getStegTotal()));
		else $this->kmTillNasta = 0;
	}

	/*****************************************************
	*
	* PUBLIC FUNCTION
	*
	*****************************************************/
	
	public function getAvstand(Kommun $kommun1, Kommun $kommun2)
	{
		
		if (isset($this->avstand[$kommun1->getId() ][$kommun2->getId() ])) {
			return $this->avstand[$kommun1->getId() ][$kommun2->getId() ];
		} elseif (isset($this->avstand[$kommun2->getId() ][$kommun1->getId() ])) {
			return $this->avstand[$kommun2->getId() ][$kommun1->getId() ];
		}
	}

	/************************************************
	*
	* SETTERS & GETTERS
	*
	************************************************/
	
	public function getMedlem()
	{
		return $this->medlem;
	}
	
	public function listStrackor()
	{
		return $this->strackor;
	}
	
	public function getTotalKm()
	{
		return $this->totalKm;
	}
	
	public function getRutt()
	{
		return $this->rutt;
	}
	
	public function getIndexOfLastNonTemp()
	{
		$this->getRutt();
		reset($this->rutt);
		foreach($this->rutt as $index => $stracka) {
			
			if ($stracka["temp"] == 1) {
				return $index;
				break;
			}
		}
		return false;
	}
	
	public static function isLastOnStatic($currentKommun_id, $fastrutt_id)
	{
		$lastKommunOnStatic = null;
		if (isset($fastrutt_id)) {
			$lastKommunOnStatic = Rutt::getLastKommunOnStaticId($fastrutt_id);
			// echo "kollar";
		}

		//echo $lastKommunOnStatic."!".$currentKommun_id;
		
		if ($lastKommunOnStatic == $currentKommun_id) {
			return true;
		} else {
			return false;
		}
	}
	
	public static function getLastKommunOnStaticId($id)
	{
		global $db;
		$sql = "SELECT kommunTill_id FROM " . self::TABLE . " 
		WHERE fastrutt_id = " . $id . " 
		ORDER BY id DESC LIMIT 1";
		return $db->value($sql);
	}
	
	public function getCurrentKommun($setKommun = false)
	{

		/*//defaultvärde
		if (!isset($this->currentIndex)) {
		global $USER;
		return $USER->getKommun();
		}*/
		
		if (isset($this->currentIndex)) {
			$kommun = $this->rutt[$this->currentIndex]["Kommun"];
		}
		
		if (!isset($kommun)) {
			
			if ($setKommun == true) {
				new Stracka(Kommun::loadById($this->getMedlem()->getJustNuKommunId()) , $this->getMedlem());
				$kommun = $this->getMedlem()->getKommun();
			} else {
				new Stracka($this->getMedlem()->getKommun() , $this->getMedlem());
				$kommun = $this->getMedlem()->getKommun();
			}
		}
		return $kommun;
	}
	
	public function getStartKommun()
	{
		
		if (isset($this->rutt[0]["Kommun"])) return $this->rutt[0]["Kommun"];
		else {
			/** defaultvärde */
			return $this->medlem->getKommun();
		}
	}
	
	public function getTotalKmKvar()
	{
		return $this->totalKmKvar;
	}
	
	public function getCurrentIndex()
	{
		return $this->currentIndex;
	}
	
	public function getKmTillNasta()
	{
		return $this->kmTillNasta;
	}
	
	public function setMedlem(Medlem $medlem)
	{
		$this->medlem = $medlem;
	}

	/***************************************************
	*
	*@inline STATIC FUNCTIONS
	*
	***************************************************/
	
	public static function addFastRutt($namn, $kommunIdArray, $abroad)
	{
		global $db;

		//echo "!". $namn. " ! ";
		//echo count($kommunIdArray);

		
		if (strlen($namn) > 3 && count($kommunIdArray) > 1) {
			$sql = "INSERT INTO " . self::FASTA_RUTTER_TABLE . " SET namn = '" . Security::escape($namn) . "'";
			
			if ($abroad) {
				$sql.= ", abroad = 'true'";
			}
			$db->query($sql);
			$id = mysql_insert_id();
		} else {
			throw new userException("Fel i inmatning", "Antingen var det bara 1 kommun angiven eller så var inte namnet satt");
		}
		
		if ($id) {
			foreach($kommunIdArray as $kommunTill_id) {
				
				if (is_numeric($kommunTill_id)) {
					$sql = "INSERT INTO " . self::TABLE . " SET fastRutt_id =$id, kommunTill_id=$kommunTill_id";
					$db->query($sql);
				}
			}
			return $id;
		}
	}
	
	public static function deleteFastRutt($id)
	{
		Security::demand(ADMIN);
		Global $db;
		
		if (!empty($id)) {
			$sql = "DELETE FROM " . self::FASTA_RUTTER_TABLE . " WHERE id=" . $id;
			
			if ($db->query($sql)) {
				return true;
			} else {
				return false;
			}
		}
	}
	
	public static function getAllFastaUtmaningar()
	{
		global $db;
		$sql = "SELECT * FROM " . self::FASTA_RUTTER_TABLE;
		$arr = $db->allValuesAsArray($sql);
		$rutter = array();
		foreach($arr as $row) {
			$rutter[] = array(
				"id" => $row['id'],
				"namn" => $row['namn']
			);
		}
		return $rutter;
	}
	
	public function setStaticRouteDoneForUser($medlem, $id)
	{
		global $db;
		$sql = "INSERT INTO " . self::FASTA_RUTTER_DONE_TABLE . " 
		SET medlem_id = " . $medlem->getId() . ",
		fastrutt_id = " . $id;
		$db->query($sql);

		//die($sql);
		
	}
	
	public static function getStaticRoutesDoneForUser($medlem_id)
	{
		global $db;
		$sql = "SELECT * FROM " . self::FASTA_RUTTER_DONE_TABLE . " a, ".self::FASTA_RUTTER_TABLE ." b  
			WHERE a.medlem_id = " . $medlem_id ." 
			AND a.fastrutt_id = b.id
			GROUP BY a.fastrutt_id";
			//echo $sql;
		return $db->allValuesAsArray($sql);
	}
}
?>
