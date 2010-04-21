<?php
/**
* Class and Function List:
* Function list:
* - __construct()
* - loadById()
* - loadByIds()
* - listAll()
* - listByKommun()
* - listEjGodkanda()
* - listByKommunId()
* - __getEmptyObject()
* - getFilnamn()
* - getKommun()
* - getKon()
* - getAlder()
* - getUrl()
* - getMedlemId()
* - getMedlem()
* - getGodkand()
* - setKommun()
* - setKommunId()
* - setKon()
* - setAlder()
* - setUrl()
* - getHeaderInfo()
* - ljudfilExisterar()
* - setMedlem()
* - setMeldmemId()
* - setGodkand()
* Classes list:
* - Kommundialekt extends Mobject
* - KommundialektException extends Exception
*/

class Kommundialekt extends Mobject
{
	
	protected $kommun;
	
	protected $kommun_id;
	
	protected $kon;
	
	protected $alder;
	
	protected $url;
	
	protected $medlem;
	
	protected $medlem_id;
	
	protected $godkand;
	
	protected $fields = array(
		"kommun_id" => "int",
		"kon" => "str",
		"alder" => "str",
		"url" => "str",
		"medlem_id" => "int",
		"godkand" => "int",
	);
	
	protected $filtyper = array(
		".mp3" => "audio/mpeg",
	);
	const KON_MAN = 'man';
	const KON_KVINNA = 'kvinna';
	const ALDER_UNG = 'ung';
	const ALDER_GAMMAL = 'gammal';
	const AUTH_LEVEL = ADMIN;
	const MAX_FILE_SIZE = 1000000; // 1 mb

	// Felkoder

	// -1 Felaktigt värde

	// -2 Felaktigt värde

	// -3 Ogiltig URL

	// -4 Felaktig filtyp

	// -5 Filen är för stor

	// -6 Ljudfilen finns redan i databasen

	
	public function __construct(Kommun $kommun, $kon, $alder, $url, $dummy_object = false)
	{
		
		if (!$dummy_object) {
			$this->setKommun($kommun);
			$this->setKon($kon);
			$this->setAlder($alder);
			$this->setUrl($url);
			
			if (Security::authorized(self::AUTH_LEVEL)) {
				$this->setGodkand(true);
			} else {
				global $USER;
				$this->setMedlem($USER);
				$this->setGodkand(false);
			}
			$this->commit();
		}
	}

	// STATIC FUNCTIONS ///////////////////////////////////
	
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
	
	public static function listByKommun(Kommun $kommun, $alla = false)
	{
		global $db;
		$sql = "SELECT id FROM " . self::classToTable(get_class()) . " WHERE kommun_id = " . $kommun->getId();
		
		if (!$alla) $sql.= " AND godkand = 1";
		$ids = $db->valuesAsArray($sql);
		
		if (count($ids) == null) return null;
		else return self::loadByIds($ids);
	}
	
	public static function listEjGodkanda()
	{
		return parent::lister(get_class() , "godkand", 0);
	}
	
	public static function listByKommunId($id)
	{
		return parent::lister(get_class() , "kommun_id", $id);
	}
	
	public static function __getEmptyObject()
	{
		$class = get_class();
		return new $class(Kommun::__getEmptyObject() , null, null, null, true);
	}

	// PUBLIC FUNCTIONS ///////////////////////////////////
	
	public function getFilnamn()
	{
		$arr = explode("/", $this->getUrl());
		return $arr[count($arr) - 1];
	}

	// SETTERS & GETTERS //////////////////////////////////
	
	public function getKommun()
	{
		
		if (!$this->kommun) $this->kommun = Kommun::loadById($this->kommun_id);
		return $this->kommun;
	}
	
	public function getKon()
	{
		return $this->kon;
	}
	
	public function getAlder()
	{
		return $this->alder;
	}
	
	public function getUrl()
	{
		return $this->url;
	}
	
	public function getMedlemId()
	{
		return $this->medlem_id;
	}
	
	public function getMedlem()
	{
		
		if ($this->medlem_id == "") {
			return null;
		}
		
		if (!$this->medlem) $this->medlem = Medlem::loadById($this->medlem_id);
		return $this->medlem;
	}
	
	public function getGodkand()
	{
		return ($this->godkand == 1) ? true : false;
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
	
	public function setKon($kon)
	{
		
		if ($kon != self::KON_MAN && $kon != self::KON_KVINNA) throw new KommundialektException("Felaktigt värde", -1);
		$this->kon = $kon;
	}
	
	public function setAlder($alder)
	{
		
		if ($alder != self::ALDER_UNG && $alder != self::ALDER_GAMMAL) throw new KommundialektException("Felaktigt värde", -2);
		$this->alder = $alder;
	}
	
	public function setUrl($url)
	{
		if($url != $this->url) { // only change if change is needed
			$headers = Misc::curlGetHeaders($url);
		
			if ($url == "" || !$headers) throw new KommundialektException("Ogiltig URL", -3);
			$contentType = trim($this->getHeaderInfo($headers, "Content-Type"));
			$fileSize = trim($this->getHeaderInfo($headers, "Content-Length"));
		
			if ($fileSize == "" || $fileSize == 0) throw new KommundialektException("Ogiltig URL", -3);
		
			if (!in_array(substr($url, -4) , array_keys($this->filtyper)) || $contentType != $this->filtyper[substr($url, -4) ]) throw new KommundialektException("Felaktig filtyp", -4);
		
			if ($fileSize > self::MAX_FILE_SIZE) throw new KommundialektException("Filen är för stor", -5);
		
			if ($this->ljudfilExisterar($url)) throw new KommundialektException("Ljudfilen finns redan i databasen", -6);
			$this->url = $url;
		}
	}
	
	private function getHeaderInfo($headers, $type)
	{
		foreach($headers as $key => $header) {
			
			if (strtolower(substr($header, 0, strlen($type))) == strtolower($type)) {
				$tmp = explode(" ", $headers[$key]);
				return $tmp[1];
			}
		}
		return null;
	}
	
	private function ljudfilExisterar($url)
	{
		global $db;
		$sql = "SELECT count(*) FROM " . self::classToTable(get_class()) . " WHERE url = '" . Security::escape($url) . "'";
		
		if ($db->value($sql) != "0") return true;
		else return false;
	}
	
	public function setMedlem(Medlem $medlem)
	{
		$this->medlem = $medlem;
		$this->medlem_id = $medlem->getId();
	}
	
	public function setMeldmemId($id)
	{
		$this->medlem = null;
		$this->medlem_id = $id;
	}
	
	public function setGodkand($mod)
	{
		
		if ($this->getId()) Security::demand(self::AUTH_LEVEL);
		$this->godkand = ($mod == true) ? 1 : 0;
	}
}

class KommundialektException extends Exception
{
}
?>
