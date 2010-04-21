<?php
/**
* Class and Function List:
* Function list:
* - __construct()
* - loadByFilename()
* - loadByForetag()
* - listAll()
* - listUnapproved()
* - listApproved()
* - delete()
* - isApproved()
* - approveForetagsbild()
* - getForetagsId()
* - getForetag()
* - setForetagsId()
* - setMedlem()
* - getImgUrls()
* - getImgUrlIfValidFile()
* - cleanImages()
* Classes list:
* - CustomForetagsbild extends Bild
* - CustomForetagsbildException extends Exception
*/

class CustomForetagsbild extends Bild
{
	const PREFIX = "Foretagsbild_";
	const UNAPPROVED_PREFIX = "unapproved_";
	const WIDTH = 50;
	const HEIGHT = 50;
	const MAX_SIZE = 50000;
	const CUSTOMFORETAGSBILD_URL_PATH = "/files/foretagsbilder";
	
	protected $foretag;

	// Felkoder
	// -1 Filen är för stor

	// -2 Samtliga argumenten får inte vara null

	// -3 Ingen CustomVisningsbild kopplad till $medlem

	// -4 Inget foretagsid

	// -5 Fel storlek på bild

	// -6 Fel format

	// -7 Fel filformat

	
	public function __construct($source = null, $filename = null, $fid = null)
	{
		
		if ($source) {
			global $FORETAG;
			
			if (!isset($FORETAG)) {
				
				if ($fid == null) throw new CustomForetagsbildException("Företag ej satt", -9);
				else $this->foretag = Foretag::loadById((int)$fid);
			} else {
				$this->foretag = Foretag::loadById($FORETAG->getId());
			}

			//uses the same allowed formats as CustomLagBild
			$allowed_formats = CustomLagbild::getAllowedFormats();
			
			if (!in_array(substr($source["name"], -3, 3) , $allowed_formats)) {
				throw new CustomForetagsbildException("Fel filtyp", -7);
			}
			parent::__construct($source);
			
			if ($this->getStorlek() > self::MAX_SIZE) throw new CustomForetagsbildException("Filen är för stor", -1);
			elseif ($this->getBredd() != self::WIDTH || $this->getHojd() != self::HEIGHT) throw new CustomForetagsbildException("Filen är ej 50x50 pixlar", -5);

			//remove other files
			self::cleanImages($this->foretag->getId());

			/*$this->resize(self::WIDTH, self::HEIGHT);*/
			$this->approve(FORETAGS_BILD_PATH . "/temp");
			$namn = "";
			do {
				$namn = self::UNAPPROVED_PREFIX . self::PREFIX . $this->foretag->getId();
			}
			while (file_exists($namn));
			$this->setNamn($namn . "." . $this->getExt());
			$this->approveForetagsbild();

			/*$this->foretag->setBildUrl($this->namn);
			$this->foretag->commit();*/
		} else 
		if ($filename) {
			parent::__construct(null, FORETAGS_BILD_PATH . "/" . $filename);
		} else {
			throw new CustomForetagsbildException("Samtliga argumenten får inte vara null", -2);
		}
	}

	// STATIC FUNCTION ////////////////////////////////////////
	
	public static function loadByFilename($filename)
	{
		return new CustomForetagsbild(null, $filename);
	}
	
	public static function loadByForetag(Foretag $foretag, $approved = true)
	{
		$filename = self::PREFIX . $foretag->getId() . ".jpg";
		$filename = ($approved) ? $filename : self::UNAPPROVED_PREFIX . $filename;
		try {
			$foretagsbild = new CustomForetagsbild(null, $filename);
		}
		catch(FilException $e) {
			
			if ($e->getCode() == - 1) {
				throw new CustomForetagsbildException('Ingen CustomVisningsbild kopplad till ' . $foretag, -3);
			}
		}
		return $foretagsbild;
	}
	
	public static function listAll()
	{
		$results = array();
		$handler = opendir(FORETAGS_BILD_PATH);
		while ($file = readdir($handler)) {
			
			if ($file != '.' && $file != '..') {
				$foretagsbild = self::loadByFilename($file);
				$results[$foretagsbild->getNamn() ] = $foretagsbild;
			}
		}
		closedir($handler);
		return $results;
	}
	
	public static function listUnapproved()
	{
		$foretagsbilder = self::listAll();
		$result = array();
		foreach($foretagsbilder as $foretagsbild) {
			
			if (!$foretagsbild->isApproved()) $result[$foretagsbild->getNamn() ] = $foretagsbild;
		}
		unset($foretagsbilder);
		return $result;
	}
	
	public static function listApproved()
	{
		$foretagsbilder = self::listAll();
		$result = array();
		foreach($foretagsbilder as $foretagsbild) {
			
			if ($foretagsbild->isApproved()) $result[$foretagsbild->getNamn() ] = $foretagsbild;
		}
		unset($foretagsbilder);
		return $result;
	}

	// PUBLIC FUNCTIONS ///////////////////////////////////////
	// !!!

	
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
	
	public function approveForetagsbild()
	{

		//Security::demand(ADMIN);
		
		if (!$this->isApproved()) {
			$this->setNamn(self::PREFIX . $this->foretag->getId() . "." . $this->getExt());
		}
	}

	// SETTERS & GETTERS //////////////////////////////////////
	
	public function getForetagsId()
	{
		
		if ($this->isApproved()) {
			return substr($this->getNamn() , strlen(self::PREFIX) , -4);
		} else {
			return substr($this->getNamn() , strlen(self::PREFIX . self::UNAPPROVED_PREFIX) , -4);
		}
	}
	
	public function getForetag()
	{
		
		if (!$this->foretag) $this->foretag = Foretag::loadById($this->getForetagId());
		return $this->foretag;
	}
	
	public function setForetagsId($id)
	{
		$this->foretags_id = $id;
		$this->foretag = null;
	}
	
	public function setMedlem(Foretag $foretag)
	{
		$this->foretag = $foretag;
		$this->foretag_id = $foretag->getId();
	}
	
	public static function getImgUrls($id)
	{
		$filenames = array();
		$filetypes = CustomLagbild::getAllowedFormats();
		foreach($filetypes as $filetype) {
			$filenames[] = self::PREFIX . $id . "." . $filetype;
		}
		return $filenames;
	}
	
	public static function getImgUrlIfValidFile($foretagsid)
	{
		$names = CustomForetagsbild::getImgUrls($foretagsid);
		foreach($names as $name) {
			$foretagsbildlink = FORETAGS_BILD_PATH . "/" . $name;
			
			if (is_file($foretagsbildlink)) {
				return CustomForetagsbild::CUSTOMFORETAGSBILD_URL_PATH . "/" . $name;
			}
		}
		return null;
	}
	
	public static function cleanImages($foretagsid)
	{
		Security::demand(FORETAG);
		$names = CustomForetagsbild::getImgUrls($foretagsid);
		foreach($names as $name) {
			
			if (is_file(FORETAGS_BILD_PATH . "/" . $name)) {
				unlink(FORETAGS_BILD_PATH . "/" . $name);
			}
		}
	}
}

class CustomForetagsbildException extends Exception
{
}
?>
