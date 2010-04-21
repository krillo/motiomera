<?php
/**
* Class and Function List:
* Function list:
* - __construct()
* - __getEmptyObject()
* - loadById()
* - listByKommun()
* - listAll()
* - getImgPath()
* - getMiniImgPath()
* - delete()
* - skapaFramsidebild()
* - kommunToThumbPath()
* - kommunToThumbNamn()
* - getThumb()
* - kommunToMiddlePath()
* - kommunToMiddleNamn()
* - getMiddle()
* - getId()
* - getTyp()
* - getKommunId()
* - getKommun()
* - getNamn()
* - getBeskrivning()
* - getBild()
* - getFramsidebild()
* - getBildUrl()
* - setKommunId()
* - setKommun()
* - setNamn()
* - setBeskrivning()
* - setBild()
* - setFramsidebild()
* Classes list:
* - Kommunbild extends Mobject
*/

class Kommunbild extends Mobject
{
	
	protected $id; // string

	
	protected $kommun_id; // int

	
	protected $kommun; // object: Kommun

	
	protected $namn; // string

	
	protected $beskrivning; // string

	
	protected $bild; // object: Bild

	
	protected $framsidebild; // object: Bild

	
	protected $fields = array(
		"kommun_id" => "int",
		"namn" => "str",
		"beskrivning" => "str"
	);
	const PREFIX = "kommunbild_";
	const FRAMSIDA_PREFIX = "fb_";
	const FB_FULLBREDD = 400;
	const FB_HALVBREDD = 195;
	const FB_TREDELBREDD = 128;
	const THUMB_CACHE = "thumb_";
	const THUMB_WIDTH = 45;
	const THUMB_HEIGHT = 45;
	const MIDDLE_CACHE = "middle_";
	const MIDDLE_WIDTH = 560;
	const MIDDLE_HEIGHT = 200;

	// Felkoder
	// -1 $storlek har ett felaktigt värde

	
	public function __construct($bild, Kommun $kommun, $namn, $beskrivning, $dummy_object = false)
	{
		
		if (!$dummy_object) {
			Security::demand(KOMMUN, $kommun);
			$this->setKommun($kommun);
			$this->setNamn($namn);
			$this->setBeskrivning($beskrivning);
			$bild->approve($this->getImgPath());
		}
	}
	
	public static function __getEmptyObject()
	{
		$class = get_class();
		return new $class(null, Kommun::__getEmptyObject() , null, null, true);
	}

	// STATIC FUNCTIONS ///////////////////////////////////////
	
	public static function loadById($id)
	{
		return parent::loadById($id, get_class());
	}
	
	public static function listByKommun(Kommun $kommun)
	{
		return parent::lister(get_class() , "kommun_id", $kommun->getId() , "id");
	}
	
	public static function listAll()
	{
		global $db;
		$sql = "SELECT id FROM " . self::classToTable();
		$ids = $db->valuesAsArray($sql);
		return self::loadByIds($ids);
	}

	// PUBLIC FUNCTIONS ///////////////////////////////////////
	
	public function getImgPath()
	{
		
		if (!$this->getId()) $this->commit();
		return KOMMUN_IMAGES_PATH . "/" . self::PREFIX . $this->getId() . ".jpg";
	}
	
	public function getMiniImgPath()
	{
		return KOMMUN_IMAGES_PATH . "/" . self::FRAMSIDA_PREFIX . self::PREFIX . $this->getId() . ".jpg";
	}
	
	public function delete()
	{
		Security::demand(KOMMUN, $this->getKommun());
		$this->getBild()->delete();
		
		if ($this->getFramsidebild()) $this->getFramsidebild()->delete();
		parent::delete();
		$this->getKommun()->justeraFramsidebilder();
	}
	
	public function skapaFramsidebild($storlek, $hojd)
	{
		$framsidebild = $this->getBild()->copy(self::FRAMSIDA_PREFIX . $this->getBild()->getNamn());
		
		if ($storlek == null && $hojd == null) {
			$bredd = self::FB_FULLBREDD;
		} else {
			
			switch ($storlek) {
			case "full":
				$bredd = self::FB_FULLBREDD;
				break;

			case "halv":
				$bredd = self::FB_HALVBREDD;
				break;

			case "tredel":
				$bredd = self::FB_TREDELBREDD;
				break;

			default:
				throw new KommunbildException('$storlek har ett felaktigt värde', -1);
			}
		}
		$framsidebild->resize($bredd, $hojd);
		$this->setFramsidebild($framsidebild);
		
		if ($storlek == null && $hojd == null) $this->getKommun()->justeraFramsidebilder();
		return $framsidebild;
	}

	// SETTERS & GETTERS //////////////////////////////////////
	
	public function kommunToThumbPath()
	{
		return KOMMUN_IMAGES_PATH . "/" . $this->kommunToThumbNamn();
	}
	
	public function kommunToThumbNamn()
	{
		return self::THUMB_CACHE . self::PREFIX . strtolower($this->getId()) . ".jpg";
	}
	
	public function getThumb()
	{
		$thumb = new Bild(null, $this->getImgPath());
		
		if (@!file($this->kommunToThumbPath())) {
			$thumb->resizeNyFil($this->kommunToThumbPath() , self::THUMB_WIDTH, self::THUMB_HEIGHT);
		}
		return $this->kommunToThumbNamn();
	}
	
	public function kommunToMiddlePath()
	{
		return KOMMUN_IMAGES_PATH . "/" . $this->kommunToMiddleNamn();
	}
	
	public function kommunToMiddleNamn()
	{
		return self::MIDDLE_CACHE . self::PREFIX . strtolower($this->getId()) . ".jpg";
	}
	
	public function getMiddle()
	{
		$middle = new Bild(null, $this->getImgPath());
		
		if (@!file($this->kommunToMiddlePath())) {
			$middle->resizeNyFil($this->kommunToMiddlePath() , self::MIDDLE_WIDTH, self::MIDDLE_HEIGHT);
		}
		return $this->kommunToMiddleNamn();
	}
	
	public function getId()
	{
		return $this->id;
	}
	
	public function getTyp()
	{
		return $this->typ;
	}
	
	public function getKommunId()
	{
		return $this->kommun_id;
	}
	
	public function getKommun()
	{
		
		if (!$this->kommun) $this->kommun = Kommun::loadById($this->getKommunId());
		return $this->kommun;
	}
	
	public function getNamn()
	{
		return $this->namn;
	}
	
	public function getBeskrivning()
	{
		return $this->beskrivning;
	}
	
	public function getBild()
	{
		
		if (!$this->bild) $this->bild = new Bild(null, $this->getImgPath());
		return $this->bild;
	}
	
	public function getFramsidebild()
	{
		
		if (!$this->framsidebild && $this->framsidebild !== false) {
			try {
				$this->framsidebild = new Bild(null, $this->getMiniImgPath());
			}
			catch(FilException $e) {
				
				if ($e->getCode() == - 1) {
					$this->framsidebild = false;
				} else throw $e;
			}
		}
		return $this->framsidebild;
	}
	
	public function getBildUrl()
	{
		
		if (!$this->bild) $this->bild = new Bild(null, $this->getImgPath());
		return $this->bild->getUrl();
	}
	
	public function setKommunId($id)
	{
		$kommun = Kommun::loadById($id);
		Security::demand(KOMMUN, $kommun);
		$this->kommun_id = $id;
		$this->kommun = null;
	}
	
	public function setKommun(Kommun $kommun)
	{
		Security::demand(KOMMUN, $kommun);
		$this->kommun = $kommun;
		$this->kommun_id = $kommun->getId();
	}
	
	public function setNamn($namn)
	{
		Security::demand(KOMMUN, $this->getKommun());
		$this->namn = $namn;
	}
	
	public function setBeskrivning($beskrivning)
	{
		Security::demand(KOMMUN, $this->getKommun());
		$this->beskrivning = $beskrivning;
	}
	
	public function setBild(Bild $bild)
	{
		Security::demand(KOMMUN, $this->getKommun());
		$this->bild = $bild;
		$bild->approve($this->getImgPath());
	}
	
	public function setFramsidebild(Bild $framsidebild)
	{
		$this->framsidebild = $framsidebild;
	}
}
?>
