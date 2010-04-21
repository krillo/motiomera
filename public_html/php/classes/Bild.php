<?php
/**
* Class and Function List:
* Function list:
* - __construct()
* - updateDimensions()
* - copy()
* - resize()
* - resizeNyFil()
* - getBredd()
* - getHojd()
* - getFormat()
* - getThumb()
* Classes list:
* - Bild extends Fil
* - BildException extends Exception
*/

class Bild extends Fil
{
	
	protected $bredd;
	
	protected $hojd;
	
	protected $format;
	
	protected $thumb;
	
	protected $tempDir = "/files/temp";
	const MAX_BREDD = 600;
	const MAX_HOJD = 820;
	const THUMB_PREFIX = "thumb_";
	const THUMB_BREDD = 100;
	const THUMB_HOJD = 100;
	const MIDDLE_PREFIX = "middle_";
	const MIDDLE_BREDD = 560;
	const MIDDLE_HOJD = 200;

	// Felkoder
	// -1 Okänd filtyp. Måste vara jpg, png eller gif

	// -2 Kunde inte ändra storleken

	// -3 Kunde inte ersätta bild

	// -4 Både $width och $height kan inte vara null

	
	public function __construct($source = null, $filename = null)
	{
		parent::__construct($source, $filename);
		$this->updateDimensions();

		if ($this->getBredd() > self::MAX_BREDD) {
			$this->resize(self::MAX_BREDD, null);
		}
		
		if ($this->getHojd() > self::MAX_HOJD) {
			$this->resize(null, self::MAX_HOJD);
		}
	}

	// PRIVATE FUNCTIONS //////////////////////////////////////
	
	private function updateDimensions()
	{
		$imginfo = getimagesize($this->getSokvag());
		$this->bredd = $imginfo[0];
		$this->hojd = $imginfo[1];
		$this->format = $imginfo[2];
	}

	// PUBLIC FUNCTIONS ///////////////////////////////////////
	
	public function copy($newname)
	{
		return new Bild(null, parent::copy($newname, false));
	}
	
	public function resize($width = null, $height = null)
	{
		
		if ($width != null && $height != null) {
			$ratio = $width / $height;
			$imgRatio = $this->bredd / $this->hojd;
			
			if ($ratio < $imgRatio) {
				$cmd = "convert " . $this->getSokvag() . " -resize x" . $height . " " . $this->getSokvag();
				system($cmd);
			} else {
				$cmd = "convert " . $this->getSokvag() . " -resize " . $width . "x " . $this->getSokvag();
				system($cmd);
			}
			$this->updateDimensions();
			$x = round(($this->getBredd() - $width) / 2);
			$y = round(($this->getHojd() - $height) / 2);
			$cmd = "convert " . $this->getSokvag() . " -crop " . $width . "x" . $height . "+$x+$y " . $this->getSokvag();
			system($cmd);
		} else 
		if ($width != null && $width != "") {
			$ratio = $width / $this->getBredd();
			$height = $this->getBredd() * $ratio;
			$cmd = "convert " . $this->getSokvag() . " -resize " . $width . "x" . $height . " " . $this->getSokvag();
			system($cmd);
		} else 
		if ($height != null && $height != null) {
			$ratio = $height / $this->getHojd();
			$height = $this->getHojd() * $ratio;
			$cmd = "convert " . $this->getSokvag() . " -resize " . $width . "x" . $height . " " . $this->getSokvag();
			system($cmd);
		} else {
			throw new BildException('Både $width och $height kan inte vara null', -4);
		}
	}

	// Ändra storlek på bilden och spara till ny fil
	
	public function resizeNyFil($destination_filename, $width, $height)
	{
		
		if ($this->bredd < $width && $this->hojd < $height && $this->getFormat() == IMAGETYPE_JPEG) {

			// bilden är redan mindre än storleken vi vill ha OCH i jpg-format
			$cmd = "cp " . $this->getSokvag() . " " . $destination_filename;
		} else {

			// resize'a bilden
			$destination_ratio = $width / $height;
			$source_ratio = $this->bredd / $this->hojd;
			
			if ($destination_ratio > $source_ratio) {
				$final_height = $height;
				$final_width = ceil($height * $source_ratio);
			} else {
				$final_width = $width;
				$final_height = ceil($width / $source_ratio);
			}
			$cmd = "convert " . $this->getSokvag() . " -resize " . $final_width . "x" . $final_height . " " . $destination_filename;
		}
		system($cmd);
	}

	// SETTERS & GETTERS //////////////////////////////////////
	
	public function getBredd()
	{
		return $this->bredd;
	}
	
	public function getHojd()
	{
		return $this->hojd;
	}
	
	public function getFormat()
	{
		return $this->format;
	}
	
	public function getThumb()
	{
		
		if (!$this->thumb) {

			//	echo $this->getSokvag();
			//	$thumb = new Bild(null);

			
		}
		return $thumb;
	}
}

class BildException extends Exception
{
};
?>
