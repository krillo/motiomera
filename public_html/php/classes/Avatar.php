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
* - Avatar extends Bild
* - AvatarException extends Exception
*/

class Avatar extends Bild
{
	const PREFIX = "avatar_";
	const STANDARD = "standard.gif";
	const WIDTH = 40;
	const HEIGHT = 40;
	const MAX_SIZE = 300000;

	// Felkoder
	// -1 Filen är för stor

	// -2 Båda argumenten får inte vara null

	
	public function __construct($source = null, $filename = null, $standard = false)
	{
	    	
		if ($source) {
			parent::__construct($source);
			
			if ($this->getStorlek() > self::MAX_SIZE) throw new AvatarException("Filen är för stor", -1);
			
			if ($this->getBredd() != self::WIDTH || $this->getHojd() != self::HEIGHT);
			$this->resize(self::WIDTH, self::HEIGHT);
			$this->approve(AVATAR_PATH . "/temp");
			$namn = "";
			
			if ($standard) {
				$namn = self::STANDARD;
			} else {
				do {
					$namn = $this->generateNamn();
				}
				while (file_exists($namn));
			}
			$this->setNamn(self::PREFIX . $namn);
		} else 
		if ($filename) {
			parent::__construct(null, AVATAR_PATH . "/" . $filename);
		} else {
			throw new AvatarException("Båda argumenten får inte vara null", -2);
		}
	}

	// STATIC FUNCTION ////////////////////////////////////////
	
	public static function loadByFilename($filename)
	{
		return new Avatar(null, $filename);
	}
	
	public static function loadStandard()
	{
		return new Avatar(null, self::PREFIX . self::STANDARD);
	}
	
	public static function listAll()
	{
		$results = array();
		try {
			$standard = self::loadStandard();
			$results[$standard->getNamn() ] = $standard;
		}
		catch(FilException $e) {
			
			if ($e->getCode() != - 1) throw $e;
		}
		$handler = opendir(AVATAR_PATH);
		while ($file = readdir($handler)) {
			
			if ($file != self::PREFIX . self::STANDARD && $file != '.' && $file != '..' && $file != '.svn') {
				$avatar = self::loadByFilename($file);
				$results[$avatar->getNamn() ] = $avatar;
			}
		}
		closedir($handler);
		return $results;
	}

	// PUBLIC FUNCTIONS ///////////////////////////////////////
	
	public function generateNamn()
	{
		$letters = "abcdefghijklmnopqrstuvxyz1234567890";
		$namn = "";
		for ($i = 0; $i < 5; $i++) {
			$namn.= $letters[mt_rand(0, strlen($letters) - 1) ];
		}
		return $namn . "." . $this->getExt();
	}
	
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

class AvatarException extends Exception
{
}
?>
