<?php
/**
 * 
 */
class Topplista
{	
	protected $params = array();	
	protected $topplista;	
	protected $topplista_typ;
	
	protected $objId;
	const TOPPLISTA_STEG = "steg";
	const TOPPLISTA_QUIZ = "quiz";
	const TOPPLISTA_KOMMUNER = "kommuner";
	const TOPPLISTA_LAG = "lag";
	const PARAM_KON = "kon";
	const PARAM_FODELSEAR = "fodelsear";
	const PARAM_KOMMUN = "kommun";
	const PARAM_PROFILINFO = "profildata";
	const PARAM_GRUPP = "grupp";
	const PARAM_LAG = "lag";
	const PARAM_FORETAG = "foretag";
	const PARAM_LAN = "lan";
	const PARAM_START = "start";
	const PARAM_STOP = "stop";
	const PARAM_QUIZ_START = "quizstart";
	const PARAM_QUIZ_STOP = "quizstop";
	const PARAM_KOMMUNER_START = "kommunerstart";
	const PARAM_KOMMUNER_STOP = "kommunerstop";
	const PARAM_DONTSHOWMEMBER = "visaejmedlem";
	
	public function __construct($topplista_typ = self::TOPPLISTA_STEG, $objId = null){
		$this->topplista_typ = $topplista_typ;
		$this->objId = $objId;
	}

	/**
	 * added to prevent nested sql-querys on group requests
	 *
	 * @param unknown_type $antal
	 * @return unknown
	 */
	private function makeSQL($antal = null){		
		$extraTableRow = '';		
		if ($this->getParams()) {
			foreach($this->getParams() as $param => $varde) {
				
				switch ($param) {
				case self::PARAM_GRUPP:
					$extraTableRow = "JOIN " . Grupp::RELATION_TABLE . " ON " . Medlem::TABLE . ".id = " . Grupp::RELATION_TABLE . ".medlem_id";
					break;
				}
			}
		}
		
		switch ($this->topplista_typ) {
		case self::TOPPLISTA_STEG:
			$sql = "
					SELECT " . Medlem::TABLE . ".id as medlem_id, sum(steg) as steg
					FROM " . Medlem::TABLE . "
					JOIN " . Steg::TABLE . "
					ON " . Medlem::TABLE . ".id = " . Steg::TABLE . ".medlem_id 
					" . ($extraTableRow != '' ? $extraTableRow : '') . "
				";
			break;

		case self::TOPPLISTA_QUIZ:
			$sql = "
					SELECT " . Medlem::TABLE . ".id as medlem_id, count(" . Quiz::TABLE . ".id) AS quiz_antal
					FROM " . Medlem::TABLE . "
					JOIN " . Quiz::TABLE . "
					ON " . Medlem::TABLE . ".id = " . Quiz::TABLE . ".medlem_id 
					" . ($extraTableRow != '' ? $extraTableRow : '') . "
				";
			break;

		case self::TOPPLISTA_KOMMUNER:
			$sql = "
					SELECT " . Medlem::TABLE . ".id as medlem_id, count(" . Feed::RELATION_TABLE . ".id) AS kommuner_antal
					FROM " . Medlem::TABLE . "
					JOIN " . Feed::RELATION_TABLE . "
					ON " . Medlem::TABLE . ".id = " . Feed::RELATION_TABLE . ".medlem_id
					" . ($extraTableRow != '' ? $extraTableRow : '') . "
				";
			break;
		}
		
		if ($this->getParams()) {
			$sql.= " WHERE ";
			$first = true;
			foreach($this->getParams() as $param => $varde) {
				
				if ($first) $first = false;
				else $sql.= " AND ";
				$sql.= "(";
				
				switch ($param) {
				case self::PARAM_FODELSEAR:
					$sql.= !empty($varde[0]) ? "fodelsear >= " . $varde[0] : '';
					$sql.= (!empty($varde[0]) && !empty($varde[1])) ? ' && ' : '';
					$sql.= !empty($varde[1]) ? "fodelsear <= " . $varde[1] : '';
					break;

				case self::PARAM_KON:
					$sql.= "kon = '" . $varde . "'";
					break;

				case self::PARAM_DONTSHOWMEMBER:
					$sql.= "medlem_id != '" . $varde . "'";
					break;

				case self::PARAM_LAN:
					$sql.= Medlem::TABLE . ".kommun_id IN (SELECT id FROM " . Kommun::TABLE . " WHERE lan LIKE '" . $varde . "%')";
					break;

				case self::PARAM_KOMMUN:
					$sql.= Medlem::TABLE . ".kommun_id = " . $varde->getId();
					break;

				case self::PARAM_PROFILINFO:
					$sql.= Medlem::TABLE . ".id IN (SELECT medlem_id FROM " . ProfilData::RELATION_TABLE . " WHERE profilData_id = " . $varde[0] . " AND profilDataVal_id = " . $varde[1] . ")";
					break;

				case self::PARAM_GRUPP:
					/*$sql .= Medlem::TABLE . ".id IN (SELECT medlem_id FROM " . Grupp::RELATION_TABLE . " WHERE grupp_id = " . $varde->getId() . " AND godkannd_medlem = 1 AND godkannd_skapare = 1) ";*/
					$sql.= Grupp::RELATION_TABLE . ".grupp_id = " . $varde->getId() . " AND " . Grupp::RELATION_TABLE . ".godkannd_medlem = 1 AND " . Grupp::RELATION_TABLE . ".godkannd_skapare = 1 ";
					break;

				case self::PARAM_FORETAG:
					$sql.= Medlem::TABLE . ".id IN (SELECT medlem_id FROM " . Foretag::KEY_TABLE . " WHERE foretag_id = " . $varde->getId() . ")";
					break;

				case self::PARAM_LAG:
					$sql.= Medlem::TABLE . ".id IN (SELECT medlem_id FROM " . Foretag::KEY_TABLE . " WHERE lag_id = " . $varde->getId() . ")";
					break;

				case self::PARAM_START:
					$sql.= Steg::TABLE . ".datum >= '" . $varde . "'";
					break;

				case self::PARAM_STOP:
					$sql.= Steg::TABLE . ".datum <= '" . $varde . "'";
					break;

				case self::PARAM_QUIZ_START:
					$sql.= Quiz::TABLE . ".quiz_date >= '" . $varde . "'";
					break;

				case self::PARAM_QUIZ_STOP:
					$sql.= quiz::TABLE . ".quiz_date <= '" . $varde . "'";
					break;

				case self::PARAM_KOMMUNER_START:
					$sql.= Feed::RELATION_TABLE . ".datum >= '" . $varde . "'";
					break;

				case self::PARAM_KOMMUNER_STOP:
					$sql.= Feed::RELATION_TABLE . ".datum <= '" . $varde . "'";
					break;
				}
				$sql.= ")";
			}
		}
		
		switch ($this->topplista_typ) {
		case self::TOPPLISTA_LAG:
			$sql.= "
					GROUP BY lag_id
					ORDER BY steg DESC
				";
			break;

		case self::TOPPLISTA_STEG:
			$sql.= "
					GROUP BY medlem_id
					ORDER BY steg DESC
				";
			break;

		case self::TOPPLISTA_QUIZ:
			$sql.= "
					GROUP BY medlem_id
					ORDER BY quiz_antal DESC
				";
			break;

		case self::TOPPLISTA_KOMMUNER:
			
			if (!($this->getParams())) {
				$sql.= "
						WHERE ";
			} else {
				$sql.= "
						AND ";
			}
			$sql.= "typ='komframtillkommun'
					GROUP BY medlem_id
					ORDER BY kommuner_antal DESC
				";
			break;
		}
		
		if ($antal) $sql.= "LIMIT $antal";
		return $sql;
	}

	// PUBLIC FUNCTIONS ///////////////////////////////////////
	
	public function addParameter($param, $varde){
		$this->params[$param] = $varde;
	}
	
/**
 * undocumented function
 *
 * @param string $antal 
 * @param string $inkludera_medlem 
 * @return void
 */
	public function getTopplista($antal = null, $inkludera_medlem = null){		
		if (!$this->topplista) {
			global $db;
			$medlem_inkluderad = false;
			$sql = $this->makeSQL();
			//echo 'sql: '. $sql . "<br/>";
			$res = $db->query($sql);
			$result = array();
			$result_temp = array();
			$placering = 0;
			$medlem_placering = 0;

			$resArray = array();
			$ids = array();
			while ($data = mysql_fetch_assoc($res)) {
				$ids[] = $data["medlem_id"];
				$resArray[] = $data;
			}
			foreach($resArray as $key => $data) {
				$data["quiz_antal"] = isset($data["quiz_antal"]) ? $data["quiz_antal"] : 0;
				$data["kommuner_antal"] = isset($data["kommuner_antal"]) ? $data["kommuner_antal"] : 0;
				
				if ($placering < $antal || !$antal) {
					$result[] = array(
						"placering" => ++$placering,
						"medlem_id" => $data["medlem_id"],
						"steg" => $data["steg"],
						"quiz_antal" => $data["quiz_antal"],
						"kommuner_antal" => $data["kommuner_antal"]
					);
				} else {
					$result_temp[] = array(
						"placering" => ++$placering,
						"medlem_id" => $data["medlem_id"],
						"steg" => $data["steg"],
						"quiz_antal" => $data["quiz_antal"],
						"kommuner_antal" => $data["kommuner_antal"]
					);
				}
				
					
				if ($inkludera_medlem && $inkludera_medlem->getId() == $data["medlem_id"] && $antal != null) {
					$medlem_placering = $placering;
					$medlem_inkluderad = true;
				}
				
				if ($antal && $placering >= $antal && (!$inkludera_medlem || ($medlem_inkluderad && $placering >= $medlem_placering + 2))) {
					break;
				}
			}
			
			if ($inkludera_medlem && $medlem_inkluderad && $medlem_placering > $antal) {
				$medlem_placering_mod = $medlem_placering - $antal - 1;
				
				if ($medlem_placering_mod - 2 >= 0 && isset($result_temp[$medlem_placering_mod - 2])) {
					$result[] = $result_temp[$medlem_placering_mod - 2];
				}
				
				if ($medlem_placering_mod - 1 >= 0 && isset($result_temp[$medlem_placering_mod - 1])) {
					$result[] = $result_temp[$medlem_placering_mod - 1];
				}
				$result[] = $result_temp[$medlem_placering_mod];
				
				if (sizeof($result_temp) >= $medlem_placering_mod + 2 && isset($result_temp[$medlem_placering_mod + 1])) {
					$result[] = $result_temp[$medlem_placering_mod + 1];
				}
				
				if (sizeof($result_temp) >= $medlem_placering_mod + 3 && isset($result_temp[$medlem_placering_mod + 2])) {
					$result[] = $result_temp[$medlem_placering_mod + 2];
				}
			}
			
			// load all needed members at once
			foreach($result as $key=>$resultrow) {
				$ids[] = $result[$key]["medlem_id"];
			}
			$medlemArray = Medlem::listByIds($ids);
			
			// populate the array with member objects
			foreach($result as $key=>$resultrow) {
				$result[$key]["medlem"] = Medlem::loadById($result[$key]["medlem_id"]);
				
			}
			
			$this->topplista = $result;
		}
		return $this->topplista;
	}

	// SETTERS AND GETTERS ////////////////////////////////////
	
	public function getParams(){
		return $this->params;
	}
}
?>
