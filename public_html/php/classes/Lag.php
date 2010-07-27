<?php
/**
* Class and Function List:
* Function list:
* - __construct()
* - __getEmptyObject()
* - loadById()
* - loadByIds()
* - listAll()
* - listByForetag()
* - loadByMedlem()
* - getMaxLag()
* - kanSkapaLag()
* - deleteLagWithNoForetag()
* - delete()
* - addMedlem()
* - removeMedlem()
* - isMember()
* - getStart()
* - getSlut()
* - getStegIndex()
* - getStegSnittByDay()
* - getStegTotal()
* - getId()
* - getNamn()
* - getBildUrl()
* - getBildFullUrl()
* - getDefaultBildFullUrl()
* - getBild()
* - getSkapad()
* - getForetagId()
* - getForetag()
* - getAnslagstavlaId()
* - getAnslagstavla()
* - listMedlemmar()
* - listInvitable()
* - getAntalMedlemmar()
* - setNamn()
* - setBildUrl()
* - setBild()
* - setForetagId()
* - setForetag()
* - setAnslagstavlaId()
* - setAnslagstavla()
* - setSkapad()
* Classes list:
* - Lag extends Mobject
* - LagException extends Exception
*/

class Lag extends Mobject
{
	
	protected $id; // int

	
	protected $foretag_id; // int

	
	protected $foretag; // object foretag

	
	protected $namn; // string

	
	protected $bildUrl; // string

	
	protected $bild;
	
	protected $skapad; // string

	
	protected $medlemmar = array(); // Array: Medlem

	
	protected $antalMedlemmar; // int

	
	protected $stegTotal;
	
	protected $anslagstavla_id; // int

	
	protected $anslagstavla; // Anslagstavla

	
	protected $fields = array(
		"foretag_id" => "int",
		"anslagstavla_id" => "int",
		"namn" => "str",
		"bildUrl" => "str",
		"skapad" => "str",
	);
	const TABLE = "mm_lag";
	const FORETAG_TABLE = "mm_foretag";
	const MIN_LENGTH_NAMN = 5;
	const BILD_PATH = "/files/lagnamn";
	const BILD_DEFAULT = "Lag_2.jpg";
	const MAX_LAG_NAMN_LENGTH = 40;

	// Felkoder
	// -1 Medlem är redan medlem i ett lag

	// -2 Felaktigt format på $datum

	// -3 $medlem tillhör inte lagets företag

	// -4 Max antal lag redan skapade

	// -5 $namn är för kort

	
	public function __construct(Foretag $foretag, $namn, $bild = null, $dummy_object = false)
	{
		
		if (!$dummy_object) {
			
			if ($namn == "") throw new LagException('$namn är för kort', -5);
			
			if (!self::kanSkapaLag($foretag)) throw new LagException("Max antal lag redan skapade", 4);
			$this->setForetag($foretag);
			$this->setNamn($namn);
			$this->setSkapad(date("Y-m-d H:i:s"));
			
			if (!$bild) {
				$lagnamnList = LagNamn::listUnused($this->getForetag());
				$bild = $lagnamnList[array_rand($lagnamnList, 1) ]->getImgO();
			}
			$this->setBild($bild);
			$this->anslagstavla_id = 0;
			$this->commit();
			$this->setAnslagstavla(new Anslagstavla(0, 0, $this->id));
			$this->commit();
		}
	}
	
	public static function __getEmptyObject()
	{
		$class = get_class();
		return new $class(Foretag::__getEmptyObject() , null, null, true);
	}

	// STATIC FUNCTIONS ///////////////////////////////////////
	
	public static function loadById($id)
	{
		return parent::loadById($id, get_class());
	}
	
	public static function loadByIds($ids)
	{
		return parent::loadByIds($ids, get_class());
	}
	
	public static function listAll()
	{
		return parent::lister(get_class());
	}
	
	public static function listByForetag(Foretag $foretag)
	{
		return parent::lister(get_class() , "foretag_id", $foretag->getId());
	}
	
	public static function loadByMedlem(Medlem $medlem)
	{
		global $db;
		$sql = "SELECT lag_id FROM " . Foretag::KEY_TABLE . " WHERE medlem_id = " . $medlem->getId() . " ORDER BY datum DESC";
		$id = $db->value($sql);
		
		if ($id) return self::loadById($id);
		else return null;
	}
	
	public static function getMaxLag()
	{
		global $db;
		return $db->value("SELECT count(*) FROM " . LagNamn::TABLE);
	}
	
	public static function kanSkapaLag(Foretag $foretag)
	{
		return ($foretag->getAntalLag() < self::getMaxLag()) ? true : false;
	}
	
	public static function deleteLagWithNoForetag()
	{
		global $db;
		$sql = "DELETE FROM " . self::TABLE . " WHERE foretag_id NOT IN(SELECT id FROM " . self::FORETAG_TABLE . ")";
		$db->query($sql);
	}

	// PUBLIC FUNCTIONS ///////////////////////////////////////
	
	public function delete()
	{
		Security::demand(FORETAG, $this->getForetag());
		global $db;
		$sql = "UPDATE " . Foretag::KEY_TABLE . " SET lag_id = null WHERE lag_id = " . $this->getId();
		$db->query($sql);
		parent::delete();
	}
	
	public function addMedlem(Medlem $medlem)
	{
		global $db;
		
		$foretagsnyckel = $medlem->getForetagsnyckel(true);
		
		if (!$this->getForetag()->isAnstalld($medlem)) throw new LagException('$medlem tillhör inte lagets företag', -3);
		$sql = "UPDATE " . Foretag::KEY_TABLE . " SET lag_id = " . $this->getId() . " WHERE medlem_id = " . $medlem->getId() . " AND nyckel='" . $foretagsnyckel . "'";
		$db->query($sql);
	}
	
	public function removeMedlem(Medlem $medlem)
	{
		Security::demand(FORETAG, $this->getForetag());
		global $db;
		$sql = "UPDATE " . Foretag::KEY_TABLE . " SET lag_id = null WHERE medlem_id = " . $medlem->getId() . " AND lag_id = " . $this->getId();
		$db->query($sql);
	}
	
	public function isMember(Medlem $medlem)
	{
		global $db;
		$sql = "SELECT count(*) FROM " . Foretag::KEY_TABLE . " WHERE medlem_id = " . $medlem->getId() . " AND lag_id = " . $this->getId();
		
		if ($db->value($sql) > 0) return true;
		else return false;
	}

	// SETTERS & GETTERS //////////////////////////////////////
	
	public function getStart()
	{
		return $this->getForetag()->getStartdatum();
	}
	
	public function getSlut()
	{
		return $this->getForetag()->getSlutdatum();
	}
	
	public function getStegIndex()
	{
		if($this->countMedlemmar() == 0) {
			return 0;
		}
		
		if ($this->getSlut() >= date("Y-m-d")) {
			$slut = date("Y-m-d");
		} else {
			$slut = $this->getSlut();
		}
		
		if ($slut == $this->getStart()) {
			$dagar = 1;
		} else {
			$dagar = (Misc::getDagarMellanTvaDatum($this->getStart() , $slut) + 1);
		}
		$return = "";
		
		if ($this->getStegTotal() != 0 && $dagar != 0) {
			$return = round($this->getStegTotal() / $this->countMedlemmar() / $dagar);
		}
		return $return;
	}
	
	public function getStegSnittByDay($day)
	{
		global $db;
		$tmstp = (time() - ($day * -86400));
		$datum = date("Y-m-d", $tmstp);
		
		if ($datum < $this->getStart()) {
			return 0;
			break;
		} else {
			$slut = $datum;
		}
		$sql = "
				SELECT sum(steg) AS steg 
				FROM " . Steg::TABLE . " a, " . Foretag::KEY_TABLE . " b
				WHERE b.lag_id = " . $this->getId() . "
				AND a.medlem_id = b.medlem_id
				AND a.datum = '" . $slut . "'
				";

		//echo $sql;
		$steg = $db->value($sql);
		$medlemmar = $this->countMedlemmar();
		
		if ($steg != 0 && $medlemmar != 0) {
			return round($steg / $medlemmar);
		} else {
			return 0;
		}
	}
	
	public function getStegTotal($devidebymedlem = false, $datum = null, $recount = false)
	{
		global $db,$lag_stegtotal_cache;
		$steg = 0;
		if(!isset($lag_stegtotal_cache)) {
			$lag_stegtotal_cache = array();
		}


		$foretag = $this->getForetag();
		$fid = $foretag->getId();
		
		if ($datum != null || ((!$this->stegTotal) && (!empty($fid))) || $recount) {
			
			if ($devidebymedlem == true) {
				$medlemmar = $this->countMedlemmar();
			} else {
				$medlemmar = "";
			}
			
			if(!$datum && !$recount && isset($lag_stegtotal_cache[$foretag->getStartdatum() . "-" . $foretag->getSlutdatum()])) {
				if(isset($lag_stegtotal_cache[$foretag->getStartdatum() . "-" . $foretag->getSlutdatum()][$this->getId()])) {
					$steg = $lag_stegtotal_cache[$foretag->getStartdatum() . "-" . $foretag->getSlutdatum()][$this->getId()];
				}
				else {
					$steg = 0;
				}
			}
			else {
			
				$sql = "
					SELECT lag_id,sum(steg) AS steg 
					FROM " . Steg::TABLE . " a, " . Foretag::KEY_TABLE . " b
					WHERE a.medlem_id = b.medlem_id
					" . ($datum == null ? "
					AND a.datum >= '" . $foretag->getStartDatum() . "'
					AND a.datum <= '" . $foretag->getSlutDatum() . "' GROUP BY lag_id
					" : " AND a.datum = '" . $datum . "' GROUP BY lag_id");
					
				$res = $db->query($sql);

				if(!$datum) {
				
					$lag_stegtotal_cache[$foretag->getStartdatum() . "-" . $foretag->getSlutdatum()] = array();

					while($r = mysql_fetch_array($res)) {
						$lag_stegtotal_cache[$foretag->getStartdatum() . "-" . $foretag->getSlutdatum()][$r["lag_id"]] = $r["steg"];
					}

					return $lag_stegtotal_cache[$foretag->getStartdatum() . "-" . $foretag->getSlutdatum()][$this->getId()];
				}
				else {
					while($r = mysql_fetch_array($res)) {
						if($r["lag_id"] == $this->getId()) {
							return $r["steg"];
						}
					}
					
					unset($res);
				}
			}
			
			if ($devidebymedlem == true) {
				$this->stegTotal = ($steg / $medlemmar);
			} else {
				$this->stegTotal = $steg;
			}
		}
		return $this->stegTotal;
	}
	
	public function getId()
	{
		return $this->id;
	}
	
	public function getNamn()
	{
		return $this->namn;
	}
	
	public function getBildUrl()
	{
		return $this->bildUrl;
	}
	
	public function getBildFullUrl()
	{
		return self::BILD_PATH . "/" . $this->bildUrl;
	}
	
	public function getDefaultBildFullUrl()
	{
		return self::BILD_PATH . "/" . self::BILD_DEFAULT;
	}
	
	public function getBild()
	{
		
		if (!$this->bild) $this->bild = new Bild(null, ROOT . self::BILD_PATH . "/" . $this->getBildUrl());
		return $this->bild;
	}
	
	public function getSkapad()
	{
		return $this->skapad;
	}
	
	public function getForetagId()
	{
		return $this->foretag_id;
	}
	
	public function getForetag()
	{
		
		if (!$this->foretag) $this->foretag = Foretag::loadById($this->foretag_id);
		return $this->foretag;
	}
	
	public function getAnslagstavlaId()
	{
		
		if (!isset($this->anslagstavla_id) || $this->anslagstavla_id == 0) {
			$this->setAnslagstavla(new Anslagstavla(0, 0, $this->id));
			$this->commit();
		}
		return $this->anslagstavla_id;
	}
	
	public function getAnslagstavla()
	{
		
		if (empty($this->anslagstavla)) {
			
			if (!isset($this->anslagstavla_id) || $this->anslagstavla_id == 0) {
				$this->setAnslagstavla(new Anslagstavla(0, 0, $this->id));
				$this->commit();
			}
			$this->anslagstavla = Anslagstavla::loadById($this->getAnslagstavlaId());
		}
		return $this->anslagstavla;
	}
	
	public function listMedlemmar($sortby = null)
	{
		
		if (count($this->medlemmar) == 0) {
			global $db;
			
			if ($sortby == "steg") {
				$sql = "SELECT a.medlem_id, c.aNamn, sum(b.steg) AS steg FROM " . Foretag::KEY_TABLE . " a, " . Steg::TABLE . " b, " . Medlem::TABLE . " c
					WHERE a.lag_id = " . $this->getId() . " 
					AND a.medlem_id IS NOT NULL 
					AND a.medlem_id = b.medlem_id 
					AND a.medlem_id = c.id 
					AND b.datum >= '" . $this->getStart() . "'
					AND b.datum <= '" . $this->getSlut() . "' 
					GROUP BY a.medlem_id ORDER BY sum(b.steg) DESC";

				return $db->allValuesAsArray($sql);
			} else {
				$sql = "SELECT medlem_id FROM " . Foretag::KEY_TABLE . " WHERE lag_id = " . $this->getId() . " AND medlem_id IS NOT NULL";
				$ids = $db->valuesAsArray($sql);
				$this->medlemmar = Medlem::listByIds($ids);
			}
		}
		return $this->medlemmar;
	}
	
	public function countMedlemmar() {
		
		global $db,$lag_countmedlemmar_cache;
		
		if(!isset($lag_countmedlemmar_cache)) {
			$lag_countmedlemmar_cache = array();
		}
		elseif(isset($lag_countmedlemmar_cache[$this->getId()])) {
			return $lag_countmedlemmar_cache[$this->getId()];
		}
		else {
			return 0;
		}
		
		$sql = "SELECT lag_id, COUNT(medlem_id) AS antal FROM " . Foretag::KEY_TABLE . " WHERE medlem_id IS NOT NULL GROUP BY lag_id";
		$res = $db->query($sql);
		
		while($r = mysql_fetch_array($res)) {
			$lag_countmedlemmar_cache[$r["lag_id"]] = $r["antal"];
		}		
		
		return $lag_countmedlemmar_cache[$this->getId()];
		
	}
	
	public function listInvitable()
	{
		global $db;
		$sql = "
			SELECT id 
			FROM " . Medlem::TABLE . " 
			WHERE id IN (
				SELECT medlem_id 
				FROM " . Foretag::KEY_TABLE . "
				WHERE 
					foretag_id = " . $this->getForetag()->getId() . "
				AND
					lag_id IS NULL
			) 
		";
		$ids = $db->valuesAsArray($sql);
		return Medlem::listByIds($ids);
	}
	
	public function getAntalMedlemmar()
	{
		global $db;
		
		if ($this->medlemmar) return count($this->medlemmar);
		else {
			$sql = "SELECT count(*) FROM " . Foretag::KEY_TABLE . " WHERE lag_id = " . $this->getId() . " AND medlem_id IS NOT NULL";
			return $db->value($sql);
		}
	}
	
	public function setNamn($namn)
	{
		
		if (strlen($namn) > self::MAX_LAG_NAMN_LENGTH) substr($this->namn = $namn, 0, self::MAX_LAG_NAMN_LENGTH);
		else $this->namn = $namn;
	}
	
	public function setBildUrl($url)
	{
		$this->bildUrl = $url;
	}
	
	public function setBild(Bild $bild)
	{
		$this->bildUrl = $bild->getNamn();
	}
	
	public function setForetagId($id)
	{
		
		if ($this->foretag_id) Security::demand(ADMIN);
		$this->foretag_id = $id;
		$this->foretag = null;
	}
	
	public function setForetag(Foretag $foretag)
	{
		
		if ($this->foretag) Security::demand(ADMIN);
		$this->foretag = $foretag;
		$this->foretag_id = $foretag->getId();
	}
	
	public function setAnslagstavlaId($id)
	{
		
		if (!Misc::isInt($id)) throw new GruppException('$id måste vara ett heltal', -4);

		// TODO: lägg in kontroll så att man ej kan byta anslagstavla
		$this->anslagstavla_id = $id;
	}
	
	public function setAnslagstavla($anslagstavla)
	{

		// TODO: lägg in kontroll så att man ej kan byta anslagstavla om den redan finns
		$this->anslagstavla = $anslagstavla;
		$this->setAnslagstavlaId($this->anslagstavla->getId());
	}
	
	public function setSkapad($date)
	{
		
		if (!Misc::isDate($date, "Y-m-d H:i:s")) throw new LagException('Felaktigt format på $datum', -2);
		$this->skapad = $date;
	}
}

class LagException extends Exception
{
}

