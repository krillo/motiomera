<?php
/**
* Class and Function List:
* Function list:
* - __construct()
* - loadByFilename()
* - loadStandard()
* - listAll()
* - generateNamn()
* - delete()
* - isStandard()
* - getMedlemId()
* - getMedlem()
* - setMedlemId()
* - setMedlem()
* Classes list:
* - Visningsbild extends Bild
* - VisningsbildException extends Exception
*/

class Visningsbild extends Bild
{
	const PREFIX = "visningsbild_";
	const WIDTH = 80;
	const HEIGHT = 100;
	const MAX_SIZE = 3000000;
	const STANDARD = "standard.jpg";
	const STANDARD_MALE = "standard_male.jpg";
	const STANDARD_FEMALE = "standard_female.jpg";

	// Felkoder
	// -1 Filen är för stor

	// -2 Båda argumenten får inte vara null

	
	public function __construct($source = null, $filename = null)
	{
		
		if ($source) {
			parent::__construct($source);
			
			if ($this->getStorlek() > self::MAX_SIZE) throw new VisningsbildException("Filen är för stor", -1);
			
			if ($this->getBredd() != self::WIDTH || $this->getHojd() != self::HEIGHT);
			$this->resize(self::WIDTH, self::HEIGHT);
			$this->approve(VISNINGSBILD_PATH . "/temp");
			$namn = "";
			do {
				$namn = self::generateNamn();
			}
			while (file_exists($namn));
			$this->setNamn(self::PREFIX . $namn . "." . $this->getExt());
		} else 
		if ($filename) {
			parent::__construct(null, VISNINGSBILD_PATH . "/" . $filename);
		} else {
			throw new VisningsbildException("Båda argumenten får inte vara null", -2);
		}
	}

	// STATIC FUNCTION ////////////////////////////////////////
	
	public function loadByFilename($filename)
	{
		return new Visningsbild(null, $filename);
	}
	
	public function loadStandard($kon)
	{
		
		if ($kon == "kvinna") {
			return new Visningsbild(null, self::STANDARD_FEMALE);
		} else 
		if ($kon == "man") {
			return new Visningsbild(null, self::STANDARD_MALE);
		} else {
			return new Visningsbild(null, self::STANDARD);
		}
	}
	
	public static function listAll()
	{
		$results = array();
		$handler = opendir(VISNINGSBILD_PATH);
		while ($file = readdir($handler)) {
			
			if ($file != '.' && $file != '..') {
				$visningsbild = self::loadByFilename($file);
				$results[$visningsbild->getNamn() ] = $visningsbild;
			}
		}
		closedir($handler);
		return $results;
	}
	
	public static function generateNamn()
	{
		$letters = "abcdefghijklmnopqrstuvxyz1234567890";
		$namn = "";
		for ($i = 0; $i < 5; $i++) {
			$namn.= $letters[mt_rand(0, strlen($letters) - 1) ];
		}
		return $namn;
	}

	// PUBLIC FUNCTIONS ///////////////////////////////////////
	
	public function delete()
	{
		Security::demand(ADMIN);
		parent::delete();
	}
	
	public function isStandard()
	{
		
		if ($this->getNamn() == self::STANDARD) return true;
		else return false;
	}

	// SETTERS & GETTERS //////////////////////////////////////
	
	public function getMedlemId()
	{
		return $this->medlem_id;
	}
	
	public function getMedlem()
	{
		
		if (!$this->medlem) $this->medlem = Medlem::loadById($this->medlem_id);
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

class VisningsbildException extends Exception
{
}
?>
