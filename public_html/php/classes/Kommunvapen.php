<?php
/**
* Class and Function List:
* Function list:
* - __construct()
* - loadByKommun()
* - listAll()
* - generateNamn()
* - delete()
* - kommunToPath()
* - kommunToNamn()
* - kommunToThumbPath()
* - kommunToThumbNamn()
* - getKommun()
* - setKommun()
* - getThumb()
* Classes list:
* - Kommunvapen extends Bild
* - KommunvapenException extends Exception
*/

class Kommunvapen extends Bild
{
	
	protected $kommun; // object: Kommun

	const PREFIX = "vapen_";
	const MAX_SIZE = 600000;
	const WIDTH = 250;
	const HEIGHT = 300;
	const THUMB_CACHE = "thumb_";
	const THUMB_WIDTH = 45;
	const THUMB_HEIGHT = 45;

	// Felkoder
	// -1 Filen är för stor

	// -2 Kommunen har inget vapen

	
	public function __construct($source = null, Kommun $kommun)
	{
		$this->setKommun($kommun);
		
		if ($source) {
			Security::demand(EDITOR);
			parent::__construct($source);
			
			if ($this->getStorlek() > self::MAX_SIZE) throw new KommunvapenException("Filen är för stor", -1);
			$this->approve(KOMMUN_IMAGES_PATH . "/temp");
			$this->setNamn($this->kommunToNamn());
			$this->resize(self::WIDTH, self::HEIGHT);
		} else {
			try {
				parent::__construct(null, $this->kommunToPath());
			}
			catch(FilException $e) {
				
				if ($e->getCode() == - 1) {
					throw new KommunvapenException("Kommunen har inget vapen", -2);
				}
			}
		}
	}

	// STATIC FUNCTION ////////////////////////////////////////
	
	public function loadByKommun(Kommun $kommun)
	{
		try {
			return new Kommunvapen(null, $kommun);
		}
		catch(KommunvapenException $e) {
			
			if ($e->getCode() == - 2) return null;
			else throw $e;
		}
	}
	
	public static function listAll()
	{
		$results = array();
		$handler = opendir(AVATAR_PATH);
		while ($file = readdir($handler)) {
			
			if ($file != '.' && $file != '..') {
				$avatar = self::loadByFilename($file);
				$results[$avatar->getNamn() ] = $avatar;
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
		Security::demand(EDITOR);
		parent::delete();
	}
	
	public function kommunToPath()
	{
		return KOMMUN_IMAGES_PATH . "/" . $this->kommunToNamn();
	}
	
	public function kommunToNamn()
	{
		return self::PREFIX . strtolower($this->getKommun()->getId()) . ".jpg";
	}
	
	public function kommunToThumbPath()
	{
		return KOMMUN_IMAGES_PATH . "/" . $this->kommunToThumbNamn();
	}
	
	public function kommunToThumbNamn()
	{
		return self::THUMB_CACHE . self::PREFIX . strtolower($this->getKommun()->getId()) . ".jpg";
	}

	// SETTERS & GETTERS //////////////////////////////////////
	
	public function getKommun()
	{
		return $this->kommun;
	}
	
	public function setKommun(Kommun $kommun)
	{
		$this->kommun = $kommun;
	}
	
	public function getThumb()
	{
		parent::getThumb();
		
		if (@!file($this->kommunToThumbPath())) {
			$this->resizeNyFil($this->kommunToThumbPath() , self::THUMB_WIDTH, self::THUMB_HEIGHT);
		}
		return $this->kommunToThumbNamn();
	}
}

class KommunvapenException extends Exception
{
}
?>
