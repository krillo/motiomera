<?php
/**
 * Class and Function List:
 * Function list:
 * - __construct()
 * - trunicateFeedItems()
 * - sortFeeds()
 * - compareFeeds()
 * - loadByMedlem()
 * - makeText()
 * - listGroupedFeedItems()
 * - listSingleFeedItems()
 * - listRelevantFeedItems()
 * - listRows()
 * - getMedlem()
 * - listKontakter()
 * - listGrupper()
 * - setMedlem()
 * Classes list:
 * - Feed
 */

class Feed
{
	protected $medlem;
	protected $kontakter;
	protected $grupper;
	protected $relevantFeedItems;
	protected $groupedFeedItems;
	protected $rows;
	protected $groups;
	protected $grupperbara = array(
		"gattmedigrupp",
		"lamnatgrupp"
	);
	
	const MEDLEM_TABLE = "mm_medlem";
	
	const RELATION_TABLE = "mm_feeditem";
	
	const STEG_TABLE = "mm_steg";
	
	const ANTAL_DATUM_I_FEED = 3;
	
	public function __construct(Medlem $medlem, $count)
	{
		$this->setMedlem($medlem);
		$this->listRelevantFeedItems($count);
	}

	// STATIC FUNCTIONS ///////////////////////////////////////
	
	public static function trunicateFeedItems()
	{
		$days = 7;
		if(defined('TRUNCATE_OLDER_THAN')){
			$days = TRUNCATE_OLDER_THAN;
		}
		
		global $db;
		$sql = "DELETE FROM " . self::RELATION_TABLE . " 
		WHERE datum < '" . date("Y-m-d", strtotime("-". $days  ."days")) . "%'";  //string like "strtotime("-7 days"))" 
		$db->query($sql);
	}
	
	public static function sortFeeds($feeds)
	{
		$a = microtime();
		
		if (!function_exists("compareFeeds")) {
			function compareFeeds($a, $b)
			{
				
				if (strtotime($a->getDatum()) == strtotime($b->getDatum())) {
					return 0;
				} else {
					
					return strtotime($a->getDatum()) > strtotime($b->getDatum()) ? -1 : 1;
				}
			}
		}
		usort($feeds, 'compareFeeds');
		
		return $feeds;
	}
	
	public static function loadByMedlem(Medlem $medlem, $count = 10)
	{
		
		return new Feed($medlem, $count);
	}
	
	public function makeText($source, $item, $params)
	{
		global $urlHandler;
		global $USER;
		$text = $source["text"];
		
		if (strpos($text, "%m") !== false) $text = str_replace("%m", '<a href="' . $urlHandler->getUrl("Medlem", URL_VIEW, $item->getMedlemId()) . '">' . $item->getMedlem()->getANamn() . "</a>", $text);
		
		if (strpos($text, "%g") !== false) {
			$text = str_replace("%g", '<a href="' . $urlHandler->getUrl("Grupp", URL_VIEW, $item->getGruppId()) . '">' . $item->getGrupp()->getNamn() . "</a>", $text);
		}
		
		if (strpos($text, "%k") !== false) {
			$text = str_replace("%k", '<a href="' . $urlHandler->getUrl("Kommun", URL_VIEW, $item->getKommun()->getNamn()) . '">' . $item->getKommun()->getNamn() . "</a>", $text);
		}
		
		if (strpos($text, "%u") !== false) {
			$text = str_replace("%u", $urlHandler->getUrl($source["url"][0], $source["url"][1], $this->getParam(0)) , $text);
		}

		/* lag and foretags feeds */
		
		if (strpos($text, "%ln") !== false) {
			$lag = $USER->getLag();
			
			if (isset($lag)) {
				$text = str_replace("%ln", $lag->getNamn() , $text);
			}
		}
		
		if (strpos($text, "%ls") !== false) {
			$lag = $USER->getLag();
			
			if (isset($lag)) {
				$text = str_replace("%ls", $lag->getStegTotal(false, substr($item->getDatum() , 0, 10)) , $text);
			}
		}
		
		if (strpos($text, "%fn") !== false) {
			$foretag = $USER->getForetag();
			
			if (isset($foretag)) {
				$text = str_replace("%fn", $foretag->getNamn() , $text);
			}
		}
		
		if (strpos($text, "%fs") !== false) {
			$foretag = $USER->getForetag();
			
			if (isset($foretag)) {
				$text = str_replace("%fs", $foretag->getStegTotal(substr($item->getDatum() , 0, 10)) , $text);
			}
		}
		
		if (strpos($text, "%a") !== false) {
			$antal = count($item->listFeedItems());
			$antalText = $antal;
			$antalText.= ($antal > 1) ? " medlemmar" : " medlem";
			$text = str_replace("%a", $antalText, $text);

			if (strpos($text, "sammanlagt") !== false) {
				
				if ($antal == 1) {
					$text = str_replace("sammanlagt ", "", $text);
				}
			}
		}
		while (strpos($text, "%") !== false) {
			$pos = strpos($text, "%");
			$id = substr($text, $pos + 1, 1);
			$value = $this->getParam($id);
			$text = substr($text, 0, $pos) . $value . substr($text, $pos + 2);
		}
		
		return $text;
	}

	// PRIVATE FUNCTIONS //////////////////////////////////////
	
	private function listGroupedFeedItems()
	{ // skapar grupperad feed

		
		if (!$this->groups) {
			global $urlHandler, $USER;
			$feedgrupper = array(); // Alla FeedGroups för aktuell Feed

			$alla = array(); // Innehåller alla grupperbara FeedItems

			foreach($this->relevantFeedItems as $item) {
				
				if (in_array($item->getTyp() , $this->grupperbara)) {
					
					switch ($item->getTyp()) {
					case "gattmedigrupp":
						$alla[substr($item->getDatum() , 0, 10) ][$item->getTyp() ][$item->getGrupp()->getId() ][] = $item;
						break;

					case "lamnatgrupp":
						$alla[substr($item->getDatum() , 0, 10) ][$item->getTyp() ][$item->getGrupp()->getId() ][] = $item;
						break;
					}
				}
			}

			// Vilka datum har händelser
			$datum = array();
			foreach($this->relevantFeedItems as $item) {
				
				if (count($datum) == 0) {
					$datum[] = substr($item->getDatum() , 0, 10);
				} else 
				if (!in_array(substr($item->getDatum() , 0, 10) , $datum)) {
					$datum[] = substr($item->getDatum() , 0, 10);
					
					if (count($datum) >= self::ANTAL_DATUM_I_FEED) break;
				}
			}
			$grupper = Grupp::listByMedlem($this->getMedlem());
			$exclude = array(); // Innehåller id för alla FeedItems som är med i grupperingar och som därför inte ska visas separat

			foreach($datum as $idag) {
				$gruppsteg = array();
				foreach($this->relevantFeedItems as $item) {
					
					if (substr($item->getDatum() , 0, 10) == $idag) {
						
						if ($item->getTyp() == "stegrapport") {
							$grupper = Grupp::listByMedlem($this->getMedlem());
							foreach($grupper as $grupp) { // Lägger till stegrapporten hos alla berörda grupper

								
								if ($grupp->isMember($item->getMedlem())) {
									
									if (isset($gruppsteg[$grupp->getId() ]["steg"])) {
										$totalsteg = $gruppsteg[$grupp->getId() ]["steg"] + $item->getParam(0);
									} else {
										$totalsteg = $item->getParam(0);
									}
									$gruppsteg[$grupp->getId() ]["items"][] = $item;
									$gruppsteg[$grupp->getId() ]["steg"] = $totalsteg;
									
									if (!isset($gruppsteg[$grupp->getId() ]["namn"])) $gruppsteg[$grupp->getId() ]["namn"] = $grupp->getNamn();
									$exclude[] = $item->getId();
								}
							}
						}
					}
				}
				foreach($gruppsteg as $id => $gruppstegtemp) { // skapar FeedGroups av informationen

					$params = array(
						$id,
						$gruppstegtemp["namn"],
						$gruppstegtemp["steg"]
					);
					$feedgrupper[] = new FeedGroup("stegrapportgrupp", $gruppstegtemp["items"], $params);
				}
			}
			foreach($alla as $datum) {
				foreach($datum as $typ => $objs) {
					
					switch ($typ) {
					case "gattmedigrupp":
					case "lamnatgrupp":
						foreach($objs as $grupp => $items) {
							
							if (count($items) > 1) {
								$feedgrupper[] = new FeedGroup($typ, $items, null, Grupp::loadById($grupp));
								foreach($items as $item) {
									$exclude[] = $item->getId();
								}
							}
						}
						break;
					}

					/*							foreach($items as $item){
					if($USER->getId() != $item->getId() && !in_array($item->getId(), $exclude))
					$exclude[] = $item->getId();
					}*/
				}
			}
			$singleItems = $this->listSingleFeedItems($exclude); // Listar alla FeedItems som inte är med i någon gruppering

			$this->groups = array_merge($singleItems, $feedgrupper);
			$this->groups = $this->sortFeeds(array_merge($singleItems, $feedgrupper));
		}
		
		return $this->groups;
	}
	
	private function listSingleFeedItems($exclude)
	{
		$result = array();
		foreach($this->relevantFeedItems as $item) {
			$tmpStr = $item->getId();
			
			if ((empty($tmpStr) || !in_array($item->getId() , $exclude) || $this->getMedlem()->inAdressbok($item->getMedlem())) && ($item->getTyp() != "valkommen" || ($item->getMedlem()->getId() == $this->getMedlem()->getId())) && ($item->getTyp() != "lagttilliadressbok" || ($item->getParam(0) == $this->getMedlem()->getId())) && ($item->getDatum() >= $this->getMedlem()->getSkapad()) && ($item->getTyp() != "uppdateratstatus" || $item->getMedlem()->getId() != $this->getMedlem()->getId()) && ( /*$item->getMedlem()->getId() != $this->getMedlem()->getId() || $item->getTyp() == "valkommen"*/

			true)) {
				$result[] = $item;
			}
		}
		
		return $result;
	}
	
	private function listRelevantFeedItems($count = 100)
	{ // Hämtar alla FeedItems som berör en medlem

		
		if (empty($this->relevantFeedItems)) {
			global $db;
			$sql = "
			
SELECT 
	id 
FROM 
	" . self::RELATION_TABLE . " 
WHERE 
	medlem_id IN (
		SELECT id 
		FROM " . self::MEDLEM_TABLE . " 
		WHERE 
		
			id IN 
				( 
					SELECT kontakt_id 
					FROM " . Adressbok::RELATION_TABLE . "
					WHERE medlem_id = " . $this->getMedlem()->getId() . "

				) OR id IN (
					SELECT medlem_id 
					FROM " . Foretag::KEY_TABLE . " 
					WHERE lag_id in (
						SELECT lag_id 
						FROM " . Foretag::KEY_TABLE . " 
						WHERE medlem_id = " . $this->getMedlem()->getId() . "
					)		
				)
		
	)	
	OR
	grupp_id IN (
	
		SELECT grupp_id 
		FROM " . Grupp::RELATION_TABLE . " 
		WHERE medlem_id = " . $this->getMedlem()->getId() . "
	
	)

	" . /*

			
			($this->getMedlem()->getForetagsnyckel()!=null?'
			OR
			medlem_id IN (
			SELECT fn1.medlem_id
			FROM mm_foretagsnycklar fn1,mm_foretagsnycklar fn2
			WHERE fn1.foretag_id = fn2.foretag_id
			AND fn2.medlem_id = '.$this->getMedlem()->getId().'
			AND fn1.medlem_id IS NOT NULL
			)
			'
			:'')
			.*/
			"
	OR
	id IN (
	
		SELECT
			" . self::RELATION_TABLE . ".id
		FROM
			" . self::RELATION_TABLE . " JOIN " . Grupp::RELATION_TABLE . "
			ON " . self::RELATION_TABLE . ".medlem_id = " . Grupp::RELATION_TABLE . ".medlem_id
		WHERE 
			" . self::RELATION_TABLE . ".medlem_id = " . $this->getMedlem()->getId() . " AND
			" . self::RELATION_TABLE . ".datum >= " . Grupp::RELATION_TABLE . ".datum

	)
	OR
	(typ = 'lagttilliadressbok' AND params = '" . $this->getMedlem()->getId() . "|" . $this->getMedlem()->getANamn() . "')
	OR
	(typ = 'valkommen' AND medlem_id = " . $this->getMedlem()->getId() . ")
";
			$this->relevantFeedItems = FeedItem::listByIds($db->valuesAsArray($sql));

			/* Lag and Foretagsfeeds */
			global $USER;
			
			if (isset($USER)) {
				$foretag = $USER->getForetag();
				
				if (isset($foretag)) {
					$foretagFeeds = array();
					$foretagFeeds = FeedItem::getForetagItems($foretag);
					$this->relevantFeedItems = array_merge($this->relevantFeedItems, $foretagFeeds);
				}
				$lag = $USER->getLag();
				
				if (isset($lag)) {
					$lagFeeds = array();
					$lagFeeds = FeedItem::getLagItems($lag);
					$this->relevantFeedItems = array_merge($this->relevantFeedItems, $lagFeeds);
				}
				
				if (true || isset($lag) || isset($foretag)) { /* no need to sort if no feeds are added, handled thru mobject-fetch */

					$this->relevantFeedItems = self::sortFeeds($this->relevantFeedItems);
				}
			}
		}

		// print_r($this->relevantFeedItems);
		
		return $this->relevantFeedItems;
	}

	// PUBLIC FUNCTIONS ///////////////////////////////////////
	
	public function listRows($gruppera = false)
	{ // returnerar en lista med FeedItems/FeedGroups

		
		if ($gruppera) {
			
			return $this->listGroupedFeedItems();
		} else {
			
			return $this->relevantFeedItems;
		}
	}

	// SETTERS & GETTERS //////////////////////////////////////
	
	public function getMedlem()
	{
		
		return $this->medlem;
	}
	
	public function listKontakter()
	{
		
		if (!$this->kontakter) {
			$adressbok = Adressbok::loadByMedlem($medlem);
			$this->kontakter = $adressbok->listKontakter();
		}
		
		return $this->kontakter;
	}
	
	public function listGrupper()
	{
		
		if (!$this->grupper) {
			$this->grupper = Grupp::listByMedlem($this->getMedlem());
		}
		
		return $this->grupper;
	}
	
	public function setMedlem(Medlem $medlem)
	{
		$this->medlem = $medlem;
	}
}
?>
