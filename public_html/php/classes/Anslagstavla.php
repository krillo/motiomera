<?php
/**
* Class and Function List:
* Function list:
* - __construct()
* - __getEmptyObject()
* - loadById()
* - listByIds()
* - listAll()
* - loadByGroupId()
* - deleteAllMemberPosts()
* - deleteAllGruppPosts()
* - delete()
* - getGruppId()
* - getForetagId()
* - getLagId()
* - getAntalRader()
* - getAllaRader()
* - setGruppId()
* - setLagId()
* - setForetagId()
* - addRad()
* Classes list:
* - Anslagstavla extends Mobject
* - AnslagstavlaException extends Exception
*/

class Anslagstavla extends Mobject
{
	
	protected $id; // int

	
	protected $lag_id; // int

	
	protected $lag; // Grupp

	
	protected $grupp_id; // int

	
	protected $grupp; // Grupp

	
	protected $foretag_id; // int, ej aktiverad ännu

	
	protected $foretag; // Foretag, ej aktiverad ännu

	
	protected $rader = array(); // Array: AnslagstavlaRad

	
	protected $antalRader; // int

	
	protected $fields = array(
		"grupp_id" => "int",
		"lag_id" => "int",
		"foretag_id" => "int"
	);

	// Felkoder
	// -1 försök att byta grupp eller företag (går bara när anslagstavlan skapas)

	// -2 försök att ge en tavla BÅDE en grupp OCH ett företag

	// -3 försök att skapa en tavla utan varken grupp eller företag

	// -4 heltalsfel

	// -7 Inga rättigheter

	
	public function __construct($grupp_id, $foretag_id, $lag_id = 0, $dummy_object = false)
	{
		
		if (!$dummy_object) {
			$this->setLagId($lag_id);
			$this->setGruppId($grupp_id);
			$this->setForetagId($foretag_id);
			
			if ($grupp_id == 0 && $foretag_id == 0 && $lag_id == 0) {
				throw new AnslagstavlaException('En anslagstavla måste tillhöra en grupp eller ett företag', -3);
			}
			$this->commit();
		}
	}
	
	public static function __getEmptyObject()
	{
		$class = get_class();
		return new $class(null, null, null, true);
	}
	
	public static function loadById($id)
	{
		return parent::loadById($id, get_class());
	}
	
	public static function listByIds($ids)
	{
		return parent::listByIds(get_class() , $ids);
	}
	
	public static function listAll()
	{
		return parent::lister(get_class());
	}
	
	public static function loadByGroupId($gid)
	{
		return parent::lister(get_class() , "grupp_id", $gid);
	}
	
	public static function deleteAllMemberPosts(Medlem $medlem)
	{
		return AnslagstavlaRad::removeAllMedlemRader($medlem);
	}
	
	public static function deleteAllGruppPosts(Grupp $grupp)
	{
		$anslagstavla = self::loadByGroupId($grupp->getId());
		foreach($anslagstavla as $tavla) $tavla->delete();
	}
	
	public function delete()
	{
		global $db;
		$sql = "DELETE FROM mm_anslagstavlarad WHERE anslagstavla_id = " . $this->getId();
		$db->query($sql);
		parent::delete();
	}

	// SETTERS & GETTERS ////////////////////////////////////////
	
	public function getGruppId()
	{
		return $this->grupp_id;
	}
	
	public function getForetagId()
	{
		return $this->foretag_id;
	}
	
	public function getLagId()
	{
		return $this->lag_id;
	}
	
	public function getAntalRader()
	{
		
		if (empty($this->antalRader)) {
			global $db;
			$sql = "SELECT count(*) FROM mm_anslagstavlarad WHERE anslagstavla_id = " . $this->getId();
			$this->antalRader = $db->value($sql);
		}
		return $this->antalRader;
	}
	
    /**
     * List all posts on anslagstavlan for this instance.
     * One sql, memcached and descending order.
     * 
     * 090511  krillo
     */
	public function getAnslagstavalaLista()
	{
		global $db,$Memcache;
		$cache_anslagstavla = 10;
		
		if (MEMCACHE) {
			$anslagstavla = $Memcache->getClassic("anslagstavla_".$this->getId());
			/*if(!$anslagstavla){
				echo "<br> Memchache: anslagstavla_" . $this->getId() ." - miss";
			} else {
				echo "<br> Memchache: anslagstavla_" . $this->getId() ." - hit";
			}*/
		}
		
		if(!$anslagstavla){
	    	$sql = "SELECT a.id, a.medlem_id, m.anamn, FROM_UNIXTIME(a.ts) ts , a.text  FROM mm_anslagstavlarad a, mm_medlem m where anslagstavla_id = " . $this->getId(). " and a.medlem_id = m.id order by ts desc";				
			$anslagstavla = $db->allValuesAsArray($sql);
		}
				
		if (MEMCACHE) {
			$Memcache->setClassic("anslagstavla_".$this->getId(), $anslagstavla, false, $cache_anslagstavla);
		}				

		return $anslagstavla;		
	}
		
	
	public function getAllaRader()
	{
		
		if ($this->rader == null) {
			global $db;
			$sql = "SELECT id FROM mm_anslagstavlarad WHERE anslagstavla_id = " . $this->getId();
			$ids = $db->valuesAsArray($sql);
			$this->rader = AnslagstavlaRad::listByIds($ids);

			// vänd på listan
			
			if (sizeof($this->rader) > 0) {
				$this->rader = array_reverse($this->rader);
			}
		}
		return $this->rader;
	}


	
	
	
	public function setGruppId($id)
	{
		
		if (!Misc::isInt($id)) {
			throw new AnslagstavlaException('$id måste vara ett heltal', -4);
		}
		
		if ($this->grupp_id) {
			throw new AnslagstavlaException('En anslagstavla kan inte byta grupp', -1);
		}
		
		if (($this->lag_id != 0 || $this->foretag_id != 0) && $id != 0) {
			$this->grupp_id = 0;
			throw new AnslagstavlaException('En anslagstavla kan bara tillhöra en grupp ELLER ett företag ELLER ett lag', -2);
		}
		$this->grupp_id = $id;
	}
	
	public function setLagId($id)
	{
		
		if (!Misc::isInt($id)) {
			throw new AnslagstavlaException('$id måste vara ett heltal', -4);
		}
		
		if ($this->lag_id) {
			throw new AnslagstavlaException('En anslagstavla kan inte byta lag', -1);
		}
		
		if (($this->grupp_id != 0 || $this->foretag_id != 0) && $id != 0) {
			$this->lag_id = 0;
			throw new AnslagstavlaException('En anslagstavla kan bara tillhöra en grupp ELLER ett företag ELLER ett lag', -2);
		}
		$this->lag_id = $id;
	}
	
	public function setForetagId($id)
	{
		
		if (!Misc::isInt($id)) {
			throw new AnslagstavlaException('$id måste vara ett heltal', -4);
		}
		
		if ($this->foretag_id) {
			throw new AnslagstavlaException('En anslagstavla kan inte byta företag', -1);
		}
		
		if (($this->lag_id != 0 || $this->grupp_id != 0) && $id != 0) {
			$this->foretag_id = 0;
			throw new AnslagstavlaException('En anslagstavla kan bara tillhöra en grupp ELLER ett företag ELLER ett lag', -2);
		}
		$this->foretag_id = $id;
	}
	
	public function addRad($text)
	{
		global $USER;
		
		if($this->getForetagId()) {
			
			$foretag = Foretag::loadById($this->getForetag());
			
			if(!isset($USER) || !$foretag->arMedI($USER)) {
				throw new UserException("Ej medlem", "Du är ej medlem i denna klubb och kan därför inte skriva på dess anslagstavla.");
			}
		}
		else {
			if($this->getGruppId() > 0) {
				$grupp = Grupp::loadById($this->getGruppId());
			}
			else {
				$grupp = Lag::loadById($this->getLagId());
			}
			
			if(!isset($USER) || !$grupp->isMember($USER)) {
				throw new UserException("Ej medlem", "Du är ej medlem i denna klubb och kan därför inte skriva på dess anslagstavla.");
			}
		}

		new AnslagstavlaRad($this->id, $USER->getId() , $text);
	}
}

class AnslagstavlaException extends Exception
{
}
?>
