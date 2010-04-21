<?php
/**
* Class and Function List:
* Function list:
* - __construct()
* - loadByFilename()
* - loadByMedlem()
* - listAll()
* - listUnapproved()
* - listApproved()
* - delete()
* - isApproved()
* - approveVisningsbild()
* - getMedlemId()
* - getMedlem()
* - setMedlemId()
* - setMedlem()
* Classes list:
* - CustomVisningsbild extends Bild
* - CustomVisningsbildException extends Exception
*/

class CustomVisningsbild extends Bild
{
	
	protected $medlem;
	const PREFIX = "visningsbild_";
	const UNAPPROVED_PREFIX = "unapproved_";
	const WIDTH = 80;
	const HEIGHT = 100;
	const MAX_SIZE = 1000000;

	// Felkoder
	// -1 Filen är för stor

	// -2 Båda argumenten får inte vara null

	// -3 Ingen CustomVisningsbild kopplad till $medlem
	
	// -4 Fel filformat (endast gif, jpg, png tillåts)

	
	public function __construct($source = null, $filename = null)
	{
		global $USER;
		
		if ($source) {
			parent::__construct($source);
			
			// Only allow gif, jpg or png files:
			if($this->getExt() != "gif" && $this->getExt() != "jpg" && $this->getExt() != "png") {
				throw new CustomVisningsbildException("Fel filformat", -4);
			}
			
			if ($this->getStorlek() > self::MAX_SIZE) throw new CustomVisningsbildException("Filen är för stor", -1);
			
			if ($this->getBredd() != self::WIDTH || $this->getHojd() != self::HEIGHT);
			$this->resize(self::WIDTH, self::HEIGHT);
			$this->approve(CUSTOM_VISNINGSBILD_PATH . "/temp");
			$namn = "";
			do {
				$namn = self::UNAPPROVED_PREFIX . self::PREFIX . $USER->getId();
			}
			while (file_exists($namn));
			$this->setNamn($namn . "." . $this->getExt());
		} else 
		if ($filename) {
			parent::__construct(null, CUSTOM_VISNINGSBILD_PATH . "/" . $filename);
		} else {
			throw new CustomVisningsbildException("Båda argumenten får inte vara null", -2);
		}
	}

	// STATIC FUNCTION ////////////////////////////////////////
	
	public static function loadByFilename($filename)
	{
		return new CustomVisningsbild(null, $filename);
	}
	
	public static function loadByMedlem(Medlem $medlem, $approved = true)
	{
		$filename = self::PREFIX . $medlem->getId() . ".jpg";
		$filename = ($approved) ? $filename : self::UNAPPROVED_PREFIX . $filename;
		try {
			$visningsbild = new CustomVisningsbild(null, $filename);
		}
		catch(FilException $e) {
			
			if ($e->getCode() == - 1) {
				throw new CustomVisningsbildException('Ingen CustomVisningsbild kopplad till $medlem', -3);
			}
		}
		return $visningsbild;
	}
	
	public static function listAll()
	{
		$results = array();
		$handler = opendir(CUSTOM_VISNINGSBILD_PATH);
		while ($file = readdir($handler)) {
			
			if ($file != '.' && $file != '..') {
				$visningsbild = self::loadByFilename($file);
				$results[$visningsbild->getNamn() ] = $visningsbild;
			}
		}
		closedir($handler);
		return $results;
	}
	
	public static function listUnapproved()
	{
		$visningsbilder = self::listAll();
		$result = array();
		foreach($visningsbilder as $visningsbild) {
			
			if (!$visningsbild->isApproved()) $result[$visningsbild->getNamn() ] = $visningsbild;
		}
		unset($visningsbilder);
		return $result;
	}
	
	public static function listApproved()
	{
		$visningsbilder = self::listAll();
		$result = array();
		foreach($visningsbilder as $visningsbild) {
			
			if ($visningsbild->isApproved()) $result[$visningsbild->getNamn() ] = $visningsbild;
		}
		unset($visningsbilder);
		return $result;
	}

	// PUBLIC FUNCTIONS ///////////////////////////////////////
	
	public function delete()
	{
		Security::demand(USER, $this->getMedlem());
		parent::delete();
	}
	
	public function isApproved()
	{
		
		if (substr($this->getNamn() , 0, strlen(self::UNAPPROVED_PREFIX)) == self::UNAPPROVED_PREFIX) return false;
		else return true;
	}
	
	public function approveVisningsbild()
	{
		Security::demand(ADMIN);
		
		if (!$this->isApproved()) {
			$this->setNamn(self::PREFIX . $this->getMedlem()->getId() . ".jpg");
		}
	}

	// SETTERS & GETTERS //////////////////////////////////////
	
	public function getMedlemId()
	{
		
		if ($this->isApproved()) {
			return substr($this->getNamn() , strlen(self::PREFIX) , -4);
		} else {
			return substr($this->getNamn() , strlen(self::PREFIX . self::UNAPPROVED_PREFIX) , -4);
		}
	}
	
	public function getMedlem()
	{
		
		if (!$this->medlem) $this->medlem = Medlem::loadById($this->getMedlemId());
		return $this->medlem;
	}
	
	public function setMedlemId($id)
	{
		$this->medlem_id = $id;
		$this->medlem = null;
	}
	
	public function setMedlem(Medlem $medlem)
	{
		$this->medlem = $medlem;
		$this->medlem_id = $medlem->getId();
	}
}

class CustomVisningsbildException extends Exception
{
}
?>
