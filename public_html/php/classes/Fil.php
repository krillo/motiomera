<?php
/**
* Class and Function List:
* Function list:
* - __construct()
* - updateFileInfo()
* - setExt()
* - copy()
* - delete()
* - approve()
* - getNamn()
* - getSokvag()
* - getMapp()
* - getStorlek()
* - getUrl()
* - getExt()
* - setNamn()
* Classes list:
* - Fil
* - FilException extends Exception
*/

class Fil
{
	
	protected $sokvag;
	
	protected $ext;
	
	protected $mapp;
	
	protected $namn;
	
	protected $storlek;
	
	protected $url;

	// Felkoder
	// -1 Filen finns inte

	// -2 Kunde inte byta namn på fil

	// -3 Kunde inte flytta fil

	// -4 Kunde inte flytta uppladdad fil

	// -5 Filen är inte uppladdad

	
	public function __construct($source = null, $path = null)
	{
		
		if ($source) {
			$sokvag = $source["tmp_name"];
			$this->ext = substr($source["name"], strrpos($source["name"], ".") + 1);
		} else {
			$sokvag = $path;
			$this->ext = substr($path, strrpos($path, ".") + 1);
		}
		
		if (!file_exists($sokvag) || !is_file($sokvag))  throw new FilException("Filen finns inte", -1);
		$this->sokvag = $sokvag;
		$this->mapp = dirname($sokvag);
		$this->namn = basename($sokvag);
		$this->storlek = @filesize($sokvag);
		$this->updateFileInfo($sokvag);
	}

	// PRIVATE FUNTIONS ///////////////////////////////////////
	
	private function updateFileInfo()
	{
		$this->sokvag = $this->getMapp() . "/" . $this->getNamn();
	}
	
	protected function setExt($ext)
	{
		$this->ext = $ext;
	}

	// PUBLIC FUNCTIONS ///////////////////////////////////////
	
	public function copy($newname, $returnFile = true)
	{
		$path = dirname($this->getSokvag()) . "/" . $newname;
		@copy($this->getSokvag() , $path);
		
		if ($returnFile) return new Fil(null, $path);
		else return $path;
	}
	
	public function delete()
	{
		return unlink($this->getSokvag());
	}
	
	public function approve($target)
	{
		
		if (is_uploaded_file($this->getSokvag())) {
			
			if (!move_uploaded_file($this->getSokvag() , $target)) {
				throw new FilException("Kunde inte flytta uppladdad fil", -4);
			}
		} else throw new FilException("Filen är inte uppladdad", -5);
		$this->mapp = dirname($target);
		$this->namn = basename($target);
		$this->updateFileInfo();
	}

	// SETTERS & GETTERS //////////////////////////////////////
	
	public function getNamn()
	{
		return $this->namn;
	}
	
	public function getSokvag()
	{
		return $this->sokvag;
	}
	
	public function getMapp()
	{
		return $this->mapp;
	}
	
	public function getStorlek()
	{
		return $this->storlek;
	}
	
	public function getUrl()
	{
		
		if (!$this->url) $this->url = substr($this->getSokvag() , strlen($_SERVER["DOCUMENT_ROOT"]));
		return $this->url;
	}
	
	public function getExt()
	{
		return $this->ext;
	}
	
	public function setNamn($namn)
	{
		
		if (!rename($this->getSokvag() , $this->getMapp() . "/" . $namn)) throw new FilException("Kunde inte byta namn på fil", -2);
		$this->namn = $namn;
		$this->updateFileInfo();
	}
}

class FilException extends Exception
{
}
?>
