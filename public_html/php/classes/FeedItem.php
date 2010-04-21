<?php
/**
 * Class and Function List:
 * Function list:
 * - __construct()
 * - __getEmptyObject()
 * - getForetagFeeds()
 * - getLagFeeds()
 * - loadById()
 * - listByIds()
 * - listAll()
 * - setId()
 * - isGrupp()
 * - addParam()
 * - getText()
 * - deleteAllMedlemFeedItems()
 * - deleteAllGruppFeedItems()
 * - getDateArray()
 * - getLagItems()
 * - getForetagItems()
 * - getMedlem()
 * - getMedlemId()
 * - getGrupp()
 * - getGruppId()
 * - getKommun()
 * - getKommunId()
 * - getTyp()
 * - listParams()
 * - getParam()
 * - getMedlemAnamn()
 * - getDatum()
 * - setMedlem()
 * - setMedlemId()
 * - setGrupp()
 * - setGruppId()
 * - setKommun()
 * - setKommunId()
 * - setTyp()
 * - setParam()
 * - setParams()
 * - setDatum()
 * Classes list:
 * - FeedItem extends Mobject
 */

class FeedItem extends Mobject
{
	protected $id;
	protected $medlem;
	protected $medlem_id;
	protected $medlemAnamn;
	protected $grupp;
	protected $grupp_id;
	protected $kommun;
	protected $kommun_id;
	protected $typ;
	protected $params;
	protected $datum;
	protected $paramsArray;
	protected $typer = array(
		"gattmedigrupp" => array(
			"text" => '%m gick med i %g',
		) ,
		"lamnatgrupp" => array(
			"text" => '%m lämnade %g',
		) ,
		"stegrapport" => array(
			"text" => '%m rapporterade in %0 steg',
		) ,
		"komframtillkommun" => array(
			"text" => '%m kom fram till %k',
		) ,
		"lagttilliadressbok" => array(
			"text" => '%m har lagt till dig som vän',
		) ,
		"valkommen" => array(
			"text" => 'Välkommen till Motiomera!',
		) ,
		"foretagssteg" => array(
			"text" => '%fn gick totalt %fs steg',
		) ,
		"lagsteg" => array(
			"text" => '%ln gick totalt %ls steg',
		) ,
		"uppdateratstatus" => array(
			"text" => '%m uppdaterade sin status till: %0',
		) ,
		"nyblogg" => array(
			"text" => '%m har updaterat sin blogg',
		) ,
	);
	protected $updatableTypes = array(
		"stegrapport",
	);
	protected $fields = array(
		"medlem_id" => "int",
		"grupp_id" => "int",
		"kommun_id" => "int",
		"typ" => "str",
		"params" => "str",
		"datum" => "str",
	);
	
	public function __construct($typ, $params, $medlem = null, $grupp = null, $kommun = null, $dummy_object = false)
	{
		
		if (!$dummy_object) {
			global $USER, $db, $urlHandler;
			
			if ($medlem == null) {
				Security::demand($USER);
				$medlem = $USER;
			}
			$this->setMedlem($medlem);
			
			if ($grupp) $this->setGrupp($grupp);
			
			if ($kommun) $this->setKommun($kommun);
			$this->setTyp($typ);
			$this->setDatum();
			$updatable = false;
			
			if (in_array($typ, $this->updatableTypes)) { // kolla om det finns en feedrad av samma typ som kan uppdateras

				$sql = "SELECT id, params FROM " . self::classToTable(get_class()) . " WHERE medlem_id = " . $USER->getId() . " AND typ = '$typ' AND datum LIKE '" . date("Y-m-d") . "%'";
				$row = $db->row($sql);
				$updatable = true;
				$oldParams = explode("|", $row["params"]);
				$this->setId($row["id"]);
			}
			$skipcommit = false;
			
			switch ($typ) {
			case "foretagssteg":
				$this->addParam($params);
				$skipcommit = true;
				break;

			case "lagsteg":
				$this->addParam($params);
				$skipcommit = true;
				break;

			case "stegrapport":
				
				if ($updatable) $this->setParam(0, $oldParams[0] + $params);
				else $this->addParam($params);
				break;

			case "lagttilliadressbok":
				$this->setParams($params);
				break;

			case "uppdateratstatus":
				$this->setParam(0, $params);
				break;
			}
			
			if (!$skipcommit) $this->commit();
		}
	}
	
	public static function __getEmptyObject()
	{
		$class = get_class();
		
		return new $class(null, null, null, null, null, true);
	}

	// STATIC FUNCTIONS ///////////////////////////////////////
	
	public static function getForetagFeeds()
	{
	}
	
	public static function getLagFeeds()
	{
	}
	
	public static function loadById($id)
	{
		
		return parent::loadById($id, get_class());
	}
	
	public static function listByIds($ids)
	{
		
		return parent::listByIds(get_class() , $ids, false, "datum DESC");
	}
	
	public static function listAll()
	{
	}

	// PRIVATE FUNCTIONS //////////////////////////////////////
	
	private function setId($id)
	{
		$this->id = $id;
	}

	// PUBLIC FUNCTION ////////////////////////////////////////
	
	public function isGrupp()
	{
		
		return false;
	}
	
	public function addParam($param)
	{
		
		if ($this->params && $this->params != "") {
			$this->params.= "|" . $param;
		} else {
			$this->params = $param;
		}
		$this->paramsArray = null;
	}
	
	public function getText()
	{
		
		return Feed::makeText($this->typer[$this->getTyp() ], $this, $this->paramsArray);
	}
	
	public static function deleteAllMedlemFeedItems(Medlem $medlem)
	{
		$items = FeedItem::lister(get_class() , "medlem_id", $medlem->getId());
		foreach($items as $item) {
			$item->delete();
		}
	}
	
	public static function deleteAllGruppFeedItems(Grupp $grupp)
	{
		$items = self::lister(get_class() , "grupp_id", $grupp->getId());
		foreach($items as $item) {
			$item->delete();
		}
	}
	
	public static function getDateArray($days, $offset = 1)
	{
		$date = array();
		$day = $offset;
		for (; $day <= ($days - 1); $day++) {
			$date[] = date('Y-m-d', strtotime((-1 * $day) . ' DAYS'));
		}
		
		return $date;
	}
	
	public static function getLagItems(Lag $lag, $days = 7)
	{
		if(defined('TRUNCATE_OLDER_THAN')){
			$days = TRUNCATE_OLDER_THAN;
		}		
		$feeds = array();
		$dates = self::getDateArray($days);
		foreach($dates as $key => $date) {
			global $USER;
			
			if ($lag->getStegTotal(false, $date) > 0) {
				$feeditem = new FeedItem("lagsteg", null, $USER);
				$feeditem->setDatum(strtotime($date));
				$feeds[] = $feeditem;
			}
		}
		
		return $feeds;
	}
	
	public static function getForetagItems(Foretag $foretag, $days = 7)
	{
		if(defined('TRUNCATE_OLDER_THAN')){
			$days = TRUNCATE_OLDER_THAN;
		}
		$feeds = array();
		$dates = self::getDateArray($days);
		foreach($dates as $key => $date) {
			global $USER;
			
			if ($foretag->getStegTotal($date) > 0) {
				$feeditem = new FeedItem("foretagssteg", null, $USER);
				$feeditem->setDatum(strtotime($date));
				$feeds[] = $feeditem;
			}
		}
		
		return $feeds;
	}

	// SETTERS & GETTERS //////////////////////////////////////
	
	public function getMedlem()
	{
		
		if (!$this->medlem) $this->medlem = Medlem::loadById($this->getMedlemId());
		
		return $this->medlem;
	}
	
	public function getMedlemId()
	{
		
		return $this->medlem_id;
	}
	
	public function getGrupp()
	{
		
		if (!$this->grupp) $this->grupp = Grupp::loadById($this->grupp_id);
		
		return $this->grupp;
	}
	
	public function getGruppId()
	{
		
		return $this->grupp_id;
	}
	
	public function getKommun()
	{
		
		if (!$this->kommun) $this->kommun = Kommun::loadById($this->getKommunId());
		
		return $this->kommun;
	}
	
	public function getKommunId()
	{
		
		return $this->kommun_id;
	}
	
	public function getTyp()
	{
		
		return $this->typ;
	}
	
	public function listParams()
	{
		
		if (!$this->paramsArray) $this->paramsArray = explode("|", $this->params);
		
		return $this->paramsArray;
	}
	
	public function getParam($index)
	{
		$arr = $this->listParams();
		
		return $arr[$index];
	}
	/**
	 * Function getAnamn
	 *
	 * Gets users username
	 *
	 * Example:
	 *      lowercase_function_name  (  )
	 */
	
	public function getMedlemAnamn()
	{
		
		return $this->medlemAnamn;
	}
	
	public function getDatum()
	{
		
		return $this->datum;
	}
	
	public function setMedlem(Medlem $medlem)
	{
		$this->medlem = $medlem;
		$this->medlem_id = $medlem->getId();
		$this->medlemAnamn = $medlem->getAnamn();
	}
	
	public function setMedlemId($id)
	{
		$this->medlem = null;
		$this->medlem_id = $id;
	}
	
	public function setGrupp(Grupp $grupp)
	{
		$this->grupp = $grupp;
		$this->grupp_id = $grupp->getId();
	}
	
	public function setGruppId($id)
	{
		$this->grupp = null;
		$this->grupp_id = $id;
	}
	
	public function setKommun(Kommun $kommun)
	{
		$this->kommun = $kommun;
		$this->kommun_id = $kommun->getId();
	}
	
	public function setKommunId($id)
	{
		$this->kommun = null;
		$this->kommun_id = $id;
	}
	
	public function setTyp($typ)
	{
		$this->typ = $typ;
	}
	
	public function setParam($index, $value)
	{
		$arr = $this->listParams();
		$arr[$index] = $value;
		$this->paramsArray = null;
		$this->params = implode("|", $arr);
	}
	
	public function setParams($params)
	{
		$this->paramsArray = $params;
		$this->params = implode("|", $params);
	}
	
	public function setDatum($utime = null)
	{
		
		if ($this->getTyp() == "stegrapport") {
			$this->datum = date("Y-m-d H:i:s", time() - 1);
		} else 
		if ($utime != null) {
			$this->datum = date("Y-m-d H:i:s", $utime);
		} else {
			$this->datum = date("Y-m-d H:i:s");
		}
	}
}
?>
