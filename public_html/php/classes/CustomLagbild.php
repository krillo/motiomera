<?php
/**
* Class and Function List:
* Function list:
* - __construct()
* - getAllowedFormats()
* - getAllowedFormatsString()
* - loadByFilename()
* - loadByLag()
* - listAll()
* - listUnapproved()
* - listApproved()
* - delete()
* - isApproved()
* - approveLagbild()
* - getLagId()
* - getLag()
* - setLagId()
* - setMedlem()
* Classes list:
* - CustomLagbild extends Bild
* - CustomLagbildException extends Exception
*/

class CustomLagbild extends Bild
{
	
	private $lag;
	const PREFIX = "Lagbild_";
	const UNAPPROVED_PREFIX = "unapproved_";
	const WIDTH = 50;
	const HEIGHT = 50;
	const MAX_SIZE = 50000;

	// Felkoder
	// -1 Filen är för stor

	// -2 Samtliga argumenten får inte vara null

	// -3 Ingen CustomVisningsbild kopplad till $medlem

	// -4 Inget lagid

	// -5 Fel storlek på bild

	// -6 Fel format

	// -7 Fel filformat

	
	public function __construct($source = null, $filename = null, $lagid = null)
	{
		$allowed_formats = self::getAllowedFormats();
		
		if ($source) {
			
			if (!isset($lagid)) throw new CustomLagbildException("Inget lagid angivet", -4);
			
			if (!in_array(substr($source["name"], -3, 3) , $allowed_formats)) {
				throw new CustomLagbildException("Fel filtyp", -7);
			}
			parent::__construct($source);
			
			if ($this->getStorlek() > self::MAX_SIZE) throw new CustomLagbildException("Filen är för stor", -1);
			elseif ($this->getBredd() != self::WIDTH || $this->getHojd() != self::HEIGHT) throw new CustomLagbildException("Filen är ej 50x50 pixlar", -5);
			$this->lag = Lag::loadById($lagid);

			/*$this->resize(self::WIDTH, self::HEIGHT);*/
			$this->approve(LAG_BILD_PATH . "/temp");
			$namn = "";
			do {
				$namn = self::UNAPPROVED_PREFIX . self::PREFIX . $lagid;
			}
			while (file_exists($namn));
			$this->setNamn($namn . "." . $this->getExt());
			$this->approveLagbild();

			//throw new CustomLagbildException("!".$this->namn."!", -2);
			$this->lag->setBildUrl($this->namn);
			$this->lag->commit();
		} else 
		if ($filename) {
			parent::__construct(null, LAG_BILD_PATH . "/" . $filename);
		} else {
			throw new CustomLagbildException("Samtliga argumenten får inte vara null", -2);
		}
	}

	// STATIC FUNCTION ////////////////////////////////////////
	
	public static function getAllowedFormats()
	{
		$allowed_formats = array(
			"jpg",
			"gif",
			"png"
		);
		return $allowed_formats;
	}
	
	public static function getAllowedFormatsString()
	{
		$string = '';
		$first = true;
		foreach(self::getAllowedFormats() as $type) {
			$string.= (!$first ? ', ' : '') . $type;
			$first = false;
		}
		return $string;
	}
	
	public static function loadByFilename($filename)
	{
		return new CustomLagbild(null, $filename);
	}
	
	public static function loadByLag(Lag $lag, $approved = true)
	{
		$filename = self::PREFIX . $lag->getId() . ".jpg";
		$filename = ($approved) ? $filename : self::UNAPPROVED_PREFIX . $filename;
		try {
			$lagbild = new CustomLagbild(null, $filename);
		}
		catch(FilException $e) {
			
			if ($e->getCode() == - 1) {
				throw new CustomLagbildException('Ingen CustomVisningsbild kopplad till ' . $lag, -3);
			}
		}
		return $lagbild;
	}
	
	public static function listAll()
	{
		$results = array();
		$handler = opendir(LAG_BILD_PATH);
		while ($file = readdir($handler)) {
			
			if ($file != '.' && $file != '..' && $file != '.svn' && !is_dir(LAG_BILD_PATH . "/" . $file)) {

				//hide custom pictures from selection
				
				if (substr($file, 0, strlen(self::PREFIX)) != self::PREFIX) {
					$lagbild = self::loadByFilename($file);
					$results[$lagbild->getNamn() ] = $lagbild;
				}
			}
		}
		closedir($handler);
		return $results;
	}
	
	public static function listUnapproved()
	{
		$lagbilder = self::listAll();
		$result = array();
		foreach($lagbilder as $lagbild) {
			
			if (!$lagbild->isApproved()) $result[$lagbild->getNamn() ] = $lagbild;
		}
		unset($visningsbilder);
		return $result;
	}
	
	public static function listApproved()
	{
		$lagbilder = self::listAll();
		$result = array();
		foreach($lagbilder as $lagbild) {
			
			if ($lagbild->isApproved()) $result[$lagbild->getNamn() ] = $lagbild;
		}
		unset($lagbilder);
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
	
	public function approveLagbild()
	{

		//Security::demand(ADMIN);
		
		if (!$this->isApproved()) {
			$this->setNamn(self::PREFIX . $this->lag->getId() . "." . $this->getExt());
		}
	}

	// SETTERS & GETTERS //////////////////////////////////////
	
	public function getLagId()
	{
		
		if ($this->isApproved()) {
			return substr($this->getNamn() , strlen(self::PREFIX) , -4);
		} else {
			return substr($this->getNamn() , strlen(self::PREFIX . self::UNAPPROVED_PREFIX) , -4);
		}
	}
	
	public function getLag()
	{
		
		if (!$this->lag) $this->lag = Lag::loadById($this->getLagId());
		return $this->lag;
	}
	
	public function setLagId($id)
	{
		$this->lag_id = $id;
		$this->lag = null;
	}
	
	public function setMedlem(Lag $lag)
	{
		$this->lag = $lag;
		$this->lag_id = $lag->getId();
	}
}

class CustomLagbildException extends Exception
{
}
?>
