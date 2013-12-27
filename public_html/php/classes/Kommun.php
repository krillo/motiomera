<?php
/**
* Class and Function List:
* Function list:
* - __construct()
* - __getEmptyObject()
* - justeraFramsidebilder()
* - delete()
* - listLan()
* - loadById()
* - loadByNamn()
* - getAngransandeKommun()
* - listAll()
* - listByLan()
* - listByIds()
* - listAvstandByIds()
* - listField()
* - listNamn()
* - getNamn()
* - getUrlNamn()
* - getOrt()
* - getAreal()
* - getFolkmangd()
* - getWebb()
* - getInfo()
* - getKod()
* - getLan()
* - getLanForLink()
* - getFramsidebildAuto()
* - getKommunvapen()
* - getKommunkarta()
* - listKommunbilder()
* - getMedlemmarIKommun()
* - setNamn()
* - setOrt()
* - setAreal()
* - setFolkmangd()
* - setWebb()
* - setInfo()
* - setKod()
* - setAbroad()
* - getAbroad()
* - setLan()
* - setFramsidebildAuto()
* - listAvstand()
* - addAvstand()
* - removeAvstand()
* - convertFromUrlNamn()
* - medlemKlarat()
* Classes list:
* - Kommun extends Mobject
* - KommunException extends Exception
*/

class Kommun extends Mobject
{
	static public $lanList;
	static public $kommunList;

	protected $id; // int
	protected $namn; // string
	protected $ort; // string
	protected $areal; // int
	protected $folkmangd; // int
	protected $webb; // string
	protected $info; // string
	protected $kod; // string
	protected $kommunvapen; // object: Kommunvapen
	protected $kommunkarta; // object: Kommunkarta
	protected $framsidebildAuto; // bool
	protected $lan;
	protected $abroad; //bool
	protected $googleName; //string
	
	protected $fields = array(
		"namn" => "str",
		"ort" => "str",
		"areal" => "int",
		"folkmangd" => "int",
		"webb" => "str",
		"info" => "str",
		"kod" => "str",
		"lan" => "str",
		"framsidebildAuto" => "int",
		"abroad" => "str",
		"googleName" => 'str'
	);
	const MIN_LENGTH_NAMN = 3;
	const RELATION_TABLE = "mm_kommunavstand";
	const TABLE = "mm_kommun";

	// Felkoder:
	// -1 $namn är för kort

	
	public function __construct($namn, $ort, $areal, $folkmangd, $webb, $info, $framsidebildAuto, $abroad = false, $googlename = false, $dummy_object = false)
	{

		//echo "BAJS";
		//die($dummy_object);

		
		if (!$dummy_object) {
			$this->setNamn($namn);
			$this->setOrt($ort);
			$this->setAreal($areal);
			$this->setFolkmangd($folkmangd);
			$this->setWebb($webb);
			$this->setAbroad($abroad);
			$this->setGoogleName($googlename);
			$this->setInfo($info);
			$this->setFramsidebildAuto($framsidebildAuto);
			$this->commit();
		}
	}
	
	public static function __getEmptyObject()
	{
		$class = get_class();
		return $newObj = new $class(null, null, null, null, null, null, null, null, null, true);
	}

	// PUBLIC FUNCTIONS ///////////////////////////////////////
	
	public function justeraFramsidebilder()
	{
		
		if ($this->getFramsidebildAuto()) {
			$kommunbilder = $this->listKommunbilder(true);
			
			switch (count($kommunbilder)) {
			case 0:
				break;

			case 1:
				$kommunbilder[0]->skapaFramsidebild("full", null);
				break;

			case 2:
				$kommunbilder[0]->skapaFramsidebild("halv", null);
				$kommunbilder[1]->skapaFramsidebild("halv", null);
				break;

			case 3:
				$kommunbilder[0]->skapaFramsidebild("full", null);
				$kommunbilder[1]->skapaFramsidebild("halv", null);
				$kommunbilder[2]->skapaFramsidebild("halv", null);
				break;

			case 4:
				$kommunbilder[0]->skapaFramsidebild("full", null);
				$kommunbilder[1]->skapaFramsidebild("tredel", null);
				$kommunbilder[2]->skapaFramsidebild("tredel", null);
				$kommunbilder[3]->skapaFramsidebild("tredel", null);
				break;

			case 5:
				$kommunbilder[0]->skapaFramsidebild("full", null);
				$kommunbilder[1]->skapaFramsidebild("halv", null);
				$kommunbilder[2]->skapaFramsidebild("halv", null);
				$kommunbilder[3]->skapaFramsidebild("halv", null);
				$kommunbilder[4]->skapaFramsidebild("halv", null);
				break;

			default:
				foreach($kommunbilder as $kommunbild) {
					$kommunbild->skapaFramsidebild("halv", null);
				}
				break;
			}
		}
	}
	
	public function delete()
	{
		Security::demand(ADMIN);
		$kommunvapen = $this->getKommunvapen();
		
		if ($kommunvapen) $kommunvapen->delete();
		$kommunkarta = $this->getKommunkarta();
		
		if ($kommunkarta) $kommunkarta->delete();
		$kommunbilder = $this->listKommunbilder();
		
		if ($kommunbilder) {
			foreach($kommunbilder as $kommunbild) {
				$kommunbild->delete();
			}
		}
		parent::delete();
	}
	
	public function listLan()
	{
		
		if (!self::$lanList) {
			global $db;
			$sql = "SELECT DISTINCT lan FROM " . self::classToTable(get_class()) . " ORDER BY lan";
			self::$lanList = $db->valuesAsArray($sql);
		}
		return self::$lanList;
	}

	// STATIC FUNCTIONS ///////////////////////////////////////
	
	public static function loadById($id)
	{
		
		if (!empty($id)) {
			return parent::loadById($id, get_class());
		} else {
			return;
		}
	}
	
	public static function loadByNamn($namn)
	{
		global $db;
		$sql = "SELECT id FROM " . self::classToTable(get_class()) . " WHERE namn LIKE '" . SECURITY::secure_postdata($namn) . "'";
		$id = $db->value($sql);
		
		if ($id) {
			return parent::loadById($id, get_class());
		} else {
			return false;
		}
	}
	
	public static function getAngransandeKommun($id, $json = false)
	{
		global $db;
		$sql = "SELECT DISTINCT(b.id), b.namn, b.abroad FROM " . self::RELATION_TABLE . " a, " . self::TABLE . " b 
		WHERE a.kommun2_id = $id " . "AND a.kommun1_id = b.id " . "OR a.kommun1_id = $id " . "AND a.kommun2_id = b.id " . "GROUP BY b.id";

		//echo $sql;
		$return = array();
		$i = 0;
		$arr = $db->allValuesAsArray($sql);
		
		if (!$json) {
			return $arr;
		} else {
			foreach($arr as $row) {
				$return["routes"]["route" . $i] = array(
					"id" => $row['id'],
					"name" => $row['namn'],
					'abroad' => $row['abroad']
				);
				$i++;
			}
			return $return;
		}
	}
	
  
  
  
  /**
   * Get all kommun names and info possibility to order them
   * 13-12-27 Kristian Erendi, Reptilo.se
   * 
   * @param type $orderedby
   */
  public static function listAllOrderBy($orderedby = 'lan'){
    switch ($orderedby) {
      case 'lan':
        $order = ' ORDER by lan ASC '; 
        break;
      case 'alpha':
        $order = ' ORDER by namn ASC'; 
        break;
      default:
        $order = ' '; 
        break;
    }
    global $db;
		$sql = 'SELECT id, namn, lan FROM mm_kommun ' . $order;
    $res = $db->allValuesAsArray($sql);
    return $res;
  }
	
          
          
  public static function listAll($ordered = true, $json = false){
		if (!self::$kommunList) {
			$kommuner = parent::lister(get_class() , null, null, "`namn`");
			self::$kommunList = $kommuner;
		} else {
			$kommuner = self::$kommunList;
		}
		
		if ($json == true) {
			$return = array();
			$i = 0;
			foreach($kommuner as $row) {
				$return["routes"]["route" . $i] = array(
					"id" => $row->getId() ,
					"name" => $row->getNamn() ,
					'abroad' => $row->getAbroad()
				);
				$i++;
			}
			return $return;
		} else {
			return $kommuner;
		}
	}
	
	public static function listByLan($lan)
	{
		$kommuner = parent::lister(get_class() , "lan", $lan, "`namn`");
		return $kommuner;
	}
	
	public static function listByIds($ids, $notin = false)
	{
		return parent::listByIds(get_class() , $ids, $notin);
	}
	
	public static function listAvstandByIds($ids)
	{
		global $db;
		
		if (sizeof($ids) == 0) {
			return null;
		}
		$sql = "SELECT * FROM " . self::RELATION_TABLE . " WHERE kommun1_id in (" . implode(",", $ids) . ") OR kommun2_id IN (" . implode(",", $ids) . ")";
		$res = $db->query($sql);
		$result = array();
		while ($data = mysql_fetch_assoc($res)) {
			$result[$data["kommun1_id"]][$data["kommun2_id"]] = $data["km"];
		}
		return $result;
	}
	
	public static function listField($field, $order = "namn")
	{
		return parent::listField($field, get_class() , $order);
	}
	
  /**
   * Get all kommun names
   * Krillo changed this 2012-11-07 - to receive dbObject as well since it was dergisteredin wp?! 
   * 
   * @global type $db
   * @param type $abroad
   * @param type $multi
   * @param type $dbObject
   * @return type 
   */
	public static function listNamn($abroad = false, $multi = false, $dbObject = null){    
    if($dbObject == null){
      global $db;
    } else {
      $db = $dbObject;
    }
		$sql = 'SELECT id, namn FROM ' . self::classToTable(get_class());
		
		if ($abroad == false) {
			$sql.= " WHERE abroad = 'false'";
		} elseif ($abroad == true) {
			$sql.= " WHERE abroad = 'true'";
		}
		$res = $db->query($sql);
		$result = array();
		while ($data = mysql_fetch_assoc($res)) {
			
			if ($multi == true) {
				$result = array(
					$data['id'],
					$data['namn']
				);
			} else {
				$result[$data["id"]] = $data["namn"];
			}
		}
		asort($result);
		return $result;
	}

	// SETTERS & GETTERS //////////////////////////////////////
	
	public function getNamn()
	{
		return $this->namn;
	}
	
	public function getUrlNamn()
	{
		$from = array(
			"å",
			"ä",
			"ö",
			"Å",
			"Ä",
			"Ö",
			" "
		);
		$to = array(
			"aa",
			"ae",
			"oe",
			"AA",
			"AE",
			"OE",
			"%20"
		);

		// return str_replace($from, $to, $this->namn);
		return $this->namn;
	}
	
	public function getOrt()
	{
		return $this->ort;
	}
	
	public function getAreal()
	{
		return $this->areal;
	}
	
	public function getFolkmangd()
	{
		return $this->folkmangd;
	}
	
	public function getWebb()
	{
		return $this->webb;
	}
	
	public function getInfo()
	{
		return $this->info;
	}
	
	public function getKod()
	{
		return $this->kod;
	}
	
	public function getLan()
	{
		return $this->lan;
	}
	
	public function getLanForLink()
	{
		$from = array(
			"å",
			"ä",
			"ö",
			"Å",
			"Ä",
			"Ö",
			" "
		);
		$to = array(
			"aa",
			"ae",
			"oe",
			"AA",
			"AE",
			"OE",
			"_"
		);

		//return str_replace($from, $to, $this->lan);
		return $this->lan;
	}
	
	public function getFramsidebildAuto()
	{
		return ($this->framsidebildAuto == 1) ? true : false;
	}
	
	public function getKommunvapen()
	{
		
		if (!$this->kommunvapen) $this->kommunvapen = Kommunvapen::loadByKommun($this);
		return $this->kommunvapen;
	}
	
	public function getKommunkarta()
	{
		
		if (!$this->kommunkarta) $this->kommunkarta = Kommunkarta::loadByKommun($this);
		return $this->kommunkarta;
	}
	
	public function listKommunbilder($endastFramsidebilder = false)
	{
		$kommunbilder = Kommunbild::listByKommun($this);
		
		if ($endastFramsidebilder) {
			$result = array();
			foreach($kommunbilder as $kommunbild) {
				
				if ($kommunbild->getFramsidebild()) $result[] = $kommunbild;
			}
			return $result;
		} else return $kommunbilder;
	}
	
	public function getMedlemmarIKommun()
	{

		// DEPRECATED
		return null;
	}
	
	public function setNamn($namn)
	{
		Security::demand(EDITOR);
		
		if (strlen($namn) < self::MIN_LENGTH_NAMN) throw new KommunException('$namn är för kort', -1);

		//ta bort inledande och avslutade mellanslag på strängen
		
		if (strcmp(" ", substr($namn, -1)) == 0) {
			$namn = substr($namn, 0, strlen($namn) - 1);
		}
		
		if (strcmp(" ", substr($namn, 0, 1)) == 0) {
			$namn = substr($namn, 1);
		}
		$this->namn = $namn;
	}
	
	public function setOrt($ort)
	{
		Security::demand(EDITOR);
		$this->ort = $ort;
	}
	
	public function setAreal($areal)
	{
		Security::demand(KOMMUN, $this);
		$this->areal = $areal;
	}
	
	public function setFolkmangd($folkmangd)
	{
		Security::demand(KOMMUN, $this);
		$this->folkmangd = $folkmangd;
	}
	
	public function setWebb($webb)
	{
		Security::demand(KOMMUN, $this);
		$this->webb = $webb;
	}
	
	public function setInfo($info)
	{
		Security::demand(KOMMUN, $this);
		$this->info = $info;
	}
	
	public function setKod($kod)
	{
		Security::demand(EDITOR);
		$this->kod = $kod;
	}
	/**
	 * Function setAbroad
	 *
	 * Sets abroad
	 *
	 * Example:
	 *     setAbroad  ( $bool )
	 */
	
	public function setAbroad($set)
	{
		$this->abroad = $set;
	}
	/**
	 * Function getAbroad
	 *
	 * Returns abroad set
	 *
	 * Example:
	 *      getAbroad  (   )
	 */
	
	public function getAbroad()
	{
		return $this->abroad;
	}
	
	public function setLan($lan)
	{
		Security::demand(EDITOR);
		$this->lan = $lan;
	}
	
	public function setFramsidebildAuto($varde)
	{
		Security::demand(KOMMUN, $this);
		$this->framsidebildAuto = ($varde === true) ? 1 : 0;
	}

	// LISTERS ////////////////////////////////////////////////
	
	public function listAvstand()
	{
		global $db;
		$sql = "SELECT kommun1_id, kommun2_id, km FROM " . self::RELATION_TABLE . " WHERE kommun1_id = " . $this->getId() . " OR kommun2_id = " . $this->getId();
		$res = $db->query($sql);
		$result = array();
		while ($data = mysql_fetch_assoc($res)) {
			
			if ($data["kommun1_id"] == $this->getId()) {
				$result[] = array(
					"id" => $data["kommun2_id"],
					"km" => $data["km"]
				);
			} else {
				$result[] = array(
					"id" => $data["kommun1_id"],
					"km" => $data["km"]
				);
			}
		}
		return $result;
	}
	
	/**
	 * Function setGoogleName
	 * 
	 * Sets google name
	 *
	 * Example:
	 *     setGoogleName( "köpenhamn" )
	 */
	public function setGoogleName($name)
	{
		$this->googleName = $name;
	}
	
	/**
	 * Function getGoogleName
	 * 
	 * Gets the google specifoic name
	 *
	 * Example:
	 *      getGoogleName  (  )
	 */
	public function getGoogleName()
	{
		return $this->googleName;
	}

	// OTHER METHODS //////////////////////////////////////////
	
	public function addAvstand($kommun, $km)
	{
		Security::demand(EDITOR);
		global $db;
		$sql = "INSERT INTO " . self::RELATION_TABLE . " (kommun1_id, kommun2_id, km) values(
			" . $this->getId() . ",
			" . $kommun->getId() . ",
			" . $km . "
		)";
		$db->query($sql);
	}
	
	public function removeAvstand($target)
	{
		Security::demand(EDITOR);
		global $db;
		$id1 = $this->getId();
		$id2 = $target->getId();
		$sql = "DELETE FROM mm_kommunavstand WHERE (kommun1_id = $id1 AND kommun2_id = $id2) OR (kommun1_id = $id2 AND kommun2_id = $id1)";
		$db->nonquery($sql);
	}
	
	
	/**
	 * Remove + signs in kommunnamn
	 * Changes by Krillo 100303
	 * 
	 * @param string $urlnamn
	 * @return string
	 */
	public static function convertFromUrlNamn($urlnamn){		
		/*   Legacy code commentet by krillo 100303
		$to = array(
			"å",
			"ä",
			"ö",
			"Å",
			"Ä",
			"Ö",
			" "
		);
		$from = array(
			"aa",
			"ae",
			"oe",
			"AA",
			"AE",
			"OE",
			"%20"
		);
    */
    $from = array(
      "+",
    );
    $to = array(
      " "
    );
		
		$urlnamn = str_replace($from, $to, $urlnamn);
		return $urlnamn;		
	}
	
	public function medlemKlarat($medlem)
	{
		return Quiz::medlemKlaratKommun($medlem, $this);
	}
}

class KommunException extends Exception
{
}
?>
