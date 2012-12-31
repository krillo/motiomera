<?php
/**
* Håller reda på vilken aktivitet som utförts och hur många steg detta motsvarar
*
* Felkoder
* -2 $medlem_id måste vara ett heltal
* -3 $aktivitet_id måste vara ett heltal
* -4 Felaktigt format på $datum
* -5 $antal måste vara ett heltal
* -6 Kan inte ta bort låst stegrapport
*
* Classes list:
* - Steg extends Mobject
* - StegException extends Exception
*/
class Steg extends Mobject{
	protected $medlem_id; //int
	protected $medlem; //object: Medlem
	protected $aktivitet_id; //int
	protected $aktivitet; //object: Aktivitet
	protected $datum; //date
	protected $last;
	protected $tid; //time
	protected $antal; //int
	protected $steg; //int
	protected $fields = array(
		"medlem_id" => "int",
		"aktivitet_id" => "int",
		"datum" => "str",
		"tid" => "str",
		"antal" => "int",
		"last" => "int",
		"steg" => "int"
	);
	const STEG_PER_KM = 1000;
	const TABLE = "mm_steg";
	const MAX_STEG_PER_RAPPORT = 100000;
	const VARNING_STEG_PER_RAPPORT = 30000;
	const MAX_STEG_PER_DAG = 100000;
	const MAX_STEG_PER_VECKA = 700000;
	
	public function __construct(Medlem $medlem, Aktivitet $aktivitet, $datum, $antal, $nykommun = false, $dummy_object = false)
	{
		
		if (!$dummy_object) {
			$this->setMedlem($medlem);
			$this->setAktivitet($aktivitet);
			$this->setDatum($datum);
			$this->setAntal((int)$antal);
			$this->setLast(false);
			$this->commit();
			$medlem->uppdateraRutt(&$nykommun);
			new FeedItem("stegrapport", $this->getSteg() , $medlem);
		}
	}
	
	public static function __getEmptyObject()
	{
		$class = get_class();
		return new $class(Medlem::__getEmptyObject() , Aktivitet::__getEmptyObject() , null, null, null, true);
	}

	// STATIC FUNCTIONS ///////////////////////////////////////
	
  
  /**
   * Delete a step row in the db
   * Krillo 13-01-01  
   */
  public static function deleteStepRow($row_id){
    global $db;
		//Security::demand(USER, $this->getMedlem());
    $sql = "DELETE FROM mm_steg WHERE id = $row_id ";
		return $db->query($sql);
  }  
  
	public static function listDatumByMedlem(Medlem $medlem){
		global $db;
		$sql = "SELECT datum, sum(steg) as steg FROM " . self::classToTable(get_class()) . " WHERE medlem_id = " . $medlem->getId() . " GROUP BY datum";
		$res = $db->query($sql);
		$datum = array();
		while ($data = mysql_fetch_assoc($res)) {
			$datumStr = substr($data["datum"], 0, 10);
			
			if (isset($datum[$datumStr])) $steg = $datum[$datumStr] + $data["steg"];
			else $steg = $data["steg"];
			$datum[$datumStr] = $steg;
		}
		return $datum;
	}
	
	public static function loadById($id)
	{
		return parent::loadById($id, get_class());
	}
	
	public static function listByMedlem(Medlem $medlem, $order = "datum DESC, tid DESC")
	{
		return parent::lister(get_class() , "medlem_id", $medlem->getId() , $order);
	}
	
	public static function listByDatum($datum, Medlem $medlem = null)
	{
		global $db;
		$sql = "SELECT id FROM " . self::TABLE . " WHERE datum = '$datum'";
		
		if ($medlem) $sql.= " AND medlem_id = " . $medlem->getId();
		return parent::listByIds(get_class() , $db->valuesAsArray($sql));
	}
	
	public static function listAll()
	{
		return parent::lister(get_class());
	}
	
	public static function listTopMedlemmar($limit = 10)
	{
		global $db;
		$sql = "SELECT medlem_id, sum(steg) FROM " . self::classToTable(get_class()) . " GROUP BY medlem_id LIMIT " . Security::secure_data($limit) . ";";
		$ids = $db->valuesAsArray($sql);
		return Medlem::listByIds($ids);
	}
	
	public static function stegToKm($steg)
	{
		return $steg / self::STEG_PER_KM;
	}
	
	public static function kmToSteg($km)
	{
		return $km * self::STEG_PER_KM;
	}
	
	public static function getTotalSteg($start = null, $stop = null)
	{
		global $db;
		$sql = "SELECT sum(steg) FROM " . self::classToTable(get_class());
		
		if ($start || $stop) {
			
			if ($stop == null) $stop = date("Y-m-d H:i:s");
			
			if ($start == null) $start = "2000-01-01 00:00:00";
			$sql.= " WHERE datum >= '$start' AND datum <= '$stop'";
		}
		return $db->value($sql);
	}
	
	public static function lasSteg(Medlem $medlem)
	{
		global $db;
		$sql = "UPDATE " . self::classToTable(get_class()) . " SET last = 1 WHERE medlem_id = " . $medlem->getId();
		$db->nonquery($sql);
	}

	// PUBLIC FUNCTIONS ///////////////////////////////////////
  

	
	public function delete()
	{
		Security::demand(USER, $this->getMedlem());
		new FeedItem("stegrapport", $this->getAntal() * $this->getAktivitet()->getVarde() * (-1) , $this->getMedlem());

		/*if($this->getLast())
		throw new StegException("Kan inte ta bort låst stegrapport", -6);
		else*/
		parent::delete();
	}
	
	public function getTotalStegByDay($day = 0, Medlem $medlem)
	{
		global $db;
		$date = strtotime((($day < 0) ? "-" : "+") . abs($day) . " day");
		$sql = "SELECT SUM(steg) AS totalSteg FROM " . self::classToTable(get_class()) . " WHERE datum LIKE '" . date("Y-m-d", $date) . "%' AND medlem_id = " . $medlem->getId();
		return $db->value($sql);
	}
	
	public static function getStegTotal(Medlem $medlem, $start = null, $stop = null)
	{
		global $db;
		$sql = "SELECT SUM(steg) AS totalSteg FROM " . self::classToTable(get_class()) . " WHERE medlem_id = " . $medlem->getId() . " ";
		
		if ($start) $sql.= " AND datum >= '$start'";
		
		if ($stop) $sql.= " AND datum <= '$stop'";
		$sum = $db->value($sql);
		return ($sum == "") ? 0 : $sum;
	}
	
	// Only used by company_contest.php and tavlingsresultat.php
	public static function getStegTotalForMedlemId($medlem_id, $start, $stop)
	{
		global $db, $medlem_stegtotal_cache;
		
		if(!isset($medlem_stegtotal_cache)) {
			$medlem_stegtotal_cache = array();
		}
		
		if(isset($medlem_stegtotal_cache[$start . "-" . $stop])) {
			if(isset($medlem_stegtotal_cache[$start . "-" . $stop][$medlem_id])) {
				$sum = $medlem_stegtotal_cache[$start . "-" . $stop][$medlem_id];
			}
			else {
				$sum = 0;
			}
		}
		else {
		
			$sql = "SELECT medlem_id, SUM(steg) AS totalSteg FROM " . self::classToTable(get_class()) . " WHERE datum >= '$start' AND datum <= '$stop' GROUP BY medlem_id";
			
			$res = $db->query($sql);
			
			
			$medlem_stegtotal_cache[$start . "-" . $stop] = array();

			while($r = mysql_fetch_array($res)) {
				$medlem_stegtotal_cache[$start . "-" . $stop][$r["medlem_id"]] = $r["totalSteg"];
			}
			
			unset($res);

			$sum = $medlem_stegtotal_cache[$start . "-" . $stop][$medlem_id];
		}
		
		return ($sum == "") ? 0 : $sum;
	}
	
	public function getSvarghetsGradForAktivitet($aktivitet, $json = false) 
	{
		global $db;
		$sql = "SELECT namn, svarighetsgrad, id, varde FROM ". Aktivitet::TABLE ."
			WHERE namn = '". $aktivitet . "' 
			AND borttagen = 'nej' 
			GROUP BY svarighetsgrad";
			
			$data = $db->allValuesAsArray($sql);
			if ($json) {
				$ret = array();
				foreach ($data as $id => $object) {
					$ret['objects'][$id] = array('namn' => $object['namn'], 'value' => $object['varde'], 'grade' => $object['svarighetsgrad'], 'id' => $object['id']); 
				}
			} else {
				$ret = $data;
			}
			//print_r($data);
			return $ret;
	}
	
	public function getStegTotalGrupp(Grupp $grupp, Medlem $medlem = null)
	{
		global $db;
		$sql = "SELECT sum(steg.steg) as medlem_id FROM " . Steg::TABLE . " steg," . Grupp::RELATION_TABLE . " grupp  
				WHERE steg.medlem_id=grupp.medlem_id AND grupp.grupp_id = " . $grupp->getId() . " AND grupp.godkannd_medlem = 1 AND grupp.godkannd_skapare = 1 AND steg.datum >= '" . $grupp->getStart() . "'";

		/*$sql = "SELECT sum(steg) FROM " . Steg::TABLE . " WHERE medlem_id IN (SELECT medlem_id FROM " . Grupp::RELATION_TABLE . " WHERE grupp_id = " . $grupp->getId() . " AND godkannd_medlem = 1 AND godkannd_skapare = 1) AND datum >= '" . $grupp->getStart() . "'";*/
		
		if ($medlem == null) {
			
			if ($grupp->getStart()) {
				global $db;
				return $db->value($sql);
			} else {
				return 0;
			}
		} else {
			$sql.= " AND steg.medlem_id = " . $medlem->getId();
			return $db->value($sql);
		}
	}
	
	public function getStegTotalLag(Lag $lag, Medlem $medlem)
	{
		global $db;
		$sql = "
			SELECT sum(steg.steg) as steg 
			FROM " . Steg::TABLE . " steg 
			WHERE steg.medlem_id = " . $medlem->getId() . "
			AND steg.datum >= '" . $lag->getStart() . "'
			AND steg.datum <= '" . $lag->getSlut() . "'
		";
		return $db->value($sql);
	}

	// SETTERS & GETTERS ///////////////////////////////////////////////////////
	
	public function setMedlem(Medlem $medlem)
	{
		$this->medelm = $medlem;
		$this->medlem_id = $medlem->getId();
	}
	
	public function setMedlemId($medlem_id)
	{
		
		if (!Misc::isInt($medlem_id)) throw new StegException('$medlem_id måste vara ett heltal', -2);
		$this->medlem_id = $medlem_id;
		unset($this->medlem);
	}
	
	public function setAktivitet(Aktivitet $aktivitet)
	{
		$this->aktivitet = $aktivitet;
		$this->aktivitet_id = $aktivitet->getId();
	}
	
	public function setAktivitetId($aktivitet_id)
	{
		
		if (!Misc::isInt($aktivitet_id)) throw new StegException('$aktivitet_id måste vara ett heltal', -3);
		$this->aktivitet_id = $aktivitet_id;
		unset($this->aktivitet);
	}
	
	public function setDatum($datum)
	{
		
		if (!Misc::isDate($datum, "Y-m-d H:i:s")) throw new StegException('Felaktigt format på $datum', -4);
		$this->datum = date("Y-m-d", strtotime($datum));
		$this->tid = date("H:i:s", strtotime($datum));
	}
	
	public function setLast($state)
	{
		$this->last = ($state) ? 1 : 0;
	}
	
	public function setAntal($antal)
	{

		// TODO: ska antal konverteras till steg?
		
		if (!Misc::isInt($antal)) throw new StegException('$antal måste vara ett heltal', -5);
		$this->antal = $antal;
		$this->setSteg($antal * $this->getAktivitet()->getVarde());
	}
	
	public function setSteg($steg)
	{
		$this->steg = $steg;
	}
	
	public function getAntal()
	{
		return $this->antal;
	}
	
	public function getMedlem()
	{
		
		if (!isset($this->medlem)) {
			$this->medlem = Medlem::loadById($this->medlem_id);
		}
		return $this->medlem;
	}
	
	public function getMedlemId()
	{
		return $this->medlem_id;
	}
	
	public function getAktivitet()
	{
		
		if (!isset($this->aktivitet)) {
			$this->aktivitet = Aktivitet::loadById($this->aktivitet_id);
		}
		return $this->aktivitet;
	}
	
	public function getAktivitetId()
	{
		return $this->aktivitet_id;
	}
	
	public function getSteg()
	{
		return $this->antal * $this->getAktivitet()->getVarde();
	}
	
	public function getDatum()
	{
		return $this->datum . " " . $this->tid;
	}
	
	public function getLast()
	{
		return ($this->last != "1") ? false : true;
	}
}

class StegException extends Exception
{
}
?>
