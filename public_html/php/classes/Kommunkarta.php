<?php
/**
* Class and Function List:
* Function list:
* - __construct()
* - loadByKommun()
* - listAll()
* - generateNamn()
* - kommunToPath()
* - kommunToNamn()
* - getKommun()
* - setKommun()
* Classes list:
* - Kommunkarta extends Bild
* - KommunkartaException extends Exception
*/

class Kommunkarta extends Bild
{
	
	protected $kommun; // object: Kommun

	const PREFIX = "karta_";
	const MAX_SIZE = 300000;

	// Felkoder
	// -1 Filen är för stor

	// -2 Kommunen har ingen karta

	
	public function __construct($source = null, Kommun $kommun)
	{
		$this->setKommun($kommun);
		
		if ($source) {
			Security::demand(EDITOR);
			parent::__construct($source);
			
			if ($this->getStorlek() > self::MAX_SIZE) throw new KommunkartaException("Filen är för stor", -1);
			$this->approve(KOMMUN_IMAGES_PATH . "/temp");
			$this->setNamn($this->kommunToNamn());
		} else {
			try {
				parent::__construct(null, $this->kommunToPath());
			}
			catch(FilException $e) {
				
				if ($e->getCode() == - 1) {
					throw new KommunkartaException("Kommunen har ingen karta", -2);
				}
			}
		}
	}

	// STATIC FUNCTION ////////////////////////////////////////
	
	public function loadByKommun(Kommun $kommun)
	{
		try {
			return new Kommunkarta(null, $kommun);
		}
		catch(KommunkartaException $e) {
			
			if ($e->getCode() == - 2) return null;
			else {
				throw $e;
				echo "dsjk";
			}
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
	
	public function kommunToPath()
	{
		return KOMMUN_IMAGES_PATH . "/" . $this->kommunToNamn();
	}
	
	public function kommunToNamn()
	{
		return self::PREFIX . $this->getKommun()->getId() . ".jpg";
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
}

class KommunkartaException extends Exception
{
}
?>
