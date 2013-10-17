<?php
/**
* Class and Function List:
* Function list:
* - __construct()
* - __getEmptyObject()
* - listAll()
* - listAllRandom()
* - listUnused()
* - loadById()
* - listByIds()
* - getId()
* - getNamn()
* - getImg()
* - getImgPath()
* - getImgO()
* - getImgUrl()
* - setNamn()
* - setImg()
* - delete()
* Classes list:
* - LagNamn extends Mobject
* - LagNamnException extends Exception
*/

class LagNamn extends Mobject
{
	
	protected $id; // int

	
	protected $namn; // string

	
	protected $img; // string

	
	protected $fields = array(
		"namn" => "str",
		"img" => "str",
	);
	const PREFIX = "Lag_";
	const THUMB_WIDTH = 45;
	const THUMB_HEIGHT = 45;
	
	public function __construct($namn, $dummy_object = false)
	{
		
		if (!$dummy_object) {
			$this->setNamn($namn);
			$this->commit();
		}
	}
	
	public static function __getEmptyObject()
	{
		$class = get_class();
		return new $class(null, true);
	}
	const MIN_LENGTH_NAMN = 5;
	const TABLE = "mm_lagnamn";

	// STATIC FUNCTIONS ///////////////////////////////////////
	
	public static function listAll()
	{
		return parent::lister(get_class());
	}
	
	public static function listAllRandom()
	{
		$list = parent::lister(get_class());
		$randlist = shuffle($list);
		return $randlist;;
	}
	
	public function listUnused(Foretag $foretag)
	{
		global $db;
		$sql = "SELECT bildUrl FROM " . Lag::TABLE . " WHERE foretag_id = " . $foretag->getId();
		$res = $db->query($sql);
		$sql2 = "
			SELECT id 
			FROM " . self::TABLE . " 
			WHERE 1 = 1
		";
		$res = $db->query($sql);
		while ($data = mysql_fetch_assoc($res)) {
			$sql2.= " AND img NOT LIKE '%" . $data["bildUrl"] . "' ";
		}
		return self::listByIds($db->valuesAsArray($sql2));
	}
	
	public static function loadById($id)
	{
		return parent::loadById($id, get_class());
	}
	
	public static function listByIds($ids)
	{
		return parent::listByIds(get_class() , $ids);
	}

	// Setters & Getters //////////////////////////////////////
	
	public function getId()
	{
		return $this->id;
	}
	
	public function getNamn()
	{
		return $this->namn;
	}
	
	public function getImg()
	{
		return $this->img;
	}
	
	public function getImgPath()
	{
		
		if (!$this->getId()) {
			$this->commit();
		}
		return LAGNAMN_PATH . "/" . self::PREFIX . $this->getId() . ".png";
	}
	
	public function getImgO()
	{
		$bild = new Bild(null, $this->getImgPath());
		return $bild;
	}
	
	public function getImgUrl(){
		return substr($this->getImg() , strpos($this->getImg(), '/files/lagnamn/Lag'));;
	}
	
	public function setNamn($namn)
	{
		
		if ($this->namn) Security::demand(ADMIN);
		$this->namn = $namn;
	}
	
	public function setImg($img)
	{
		Security::demand(ADMIN);
		$this->img = $this->getImgPath();

		// Radera eventuell gammal bild på samma plats:
		
		if (file_exists($this->getImgPath())) {
			unlink($this->getImgPath());
		}
		$img->approve($this->getImgPath());
	}
	
	public function delete()
	{
		Security::demand(ADMIN);
		
		if (file_exists($this->getImgPath())) {
			unlink($this->getImgPath());
		}
		parent::delete();
	}
}

class LagNamnException extends Exception
{
}