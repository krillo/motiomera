<?php
/*
Helpklassen sköter de hjälprutor som kan dyka upp på sajten
*/
class Help extends TextEditor {

	// PUBLIC FUNCTIONS
	
	protected $auto;					// int
	protected $page;					// str
	protected $sizex;					// str
	protected $sizey;					// str
	
	protected $fields = array(
		"namn"=>					"str", 
		"tema"=>					"str",
		"texten"=>					"str",
		"page"=>					"str",
		"sizex"=>					"int",
		"sizey"=>					"int",
		"auto"=>					"int"
		);
	
	const RELATION_TABLE = "mm_help_medlem_noshow";
	
	public function __construct($namn,$tema,$page,$auto,$sizeX,$sizeY, $dummy_object = false) {

		if(!$dummy_object) {			
			$this->setPage($page);
			$this->setAuto($auto);
			$this->setSizeX($sizeX);
			$this->setSizeY($sizeY);
		
			parent::__construct($namn,$tema);
		}
	}

	public static function __getEmptyObject() {
		$class = get_class();
		return new $class(null, null, null, null, null, null, true);
	}	
	public static function listAll() {
		return parent::lister(get_class());
	}
	
	public static function loadById($id){
		return parent::loadById($id, get_class());
	}
	
	public static function loadByNamn($namn) {
	
		global $db;
		
		$sql = "SELECT id FROM " . self::classToTable(get_class()) . " WHERE namn='" . SECURITY::secure_postdata($namn) . "'";

		$id = $db->value($sql);
		
		if($id) {

			return parent::loadById($id, get_class());
			
		}
		else {
		
			return false;
		}

	}
	
	public function getAuto() {
		return $this->auto;
	}
	
	public function getPage() {
		return $this->page;
	}
	
	public function getSizeX() {
		return $this->sizex;
	}
	
	public function getSizeY() {
		return $this->sizey;
	}
	
	
	
	public function setAuto($auto) {
	
		$this->auto = $auto;
	}
	
	public function setPage($page) {
	
		$this->page = $page;
	}
	
	public function setSizeX($sizeX) {
	
		if($sizeX < 1) {
			$sizeX = 480;
		}
		
		$this->sizex = $sizeX;
	}
	
	public function setSizeY($sizeY) {
		
		if($sizeY < 1) {
			$sizeY = 200;
		}
	
		$this->sizey = $sizeY;
	}
	
	
	
	public static function listByPage($page) {
	
		$helpers = parent::lister(get_class(), "page", $page, "`namn`");
		
		return $helpers;

	}
	
	
	
	// Metoder relaterat till avfärdande av hjälprutan
	
	public function is_avfardad($medlem_id) {
		global $db;
		
		$sql = "SELECT * FROM " . self::RELATION_TABLE . " WHERE medlem_id = $medlem_id AND help_id=" . $this->getId();
		if($db->row($sql)) {
		
			return true;
		}
		else {
		
			return false;
		}
	}
	
	public function set_avfardad($medlem_id) {
		global $db;
	
		$sql = "INSERT INTO " . self::RELATION_TABLE . "(medlem_id,help_id) VALUES($medlem_id," . $this->getId() . ")";

		$db->nonquery($sql);
	}

	public static function removeAllMedlemAvfardade(Medlem $medlem) {

		if((Security::authorized(ADMIN)) or (Security::authorized(USER, $medlem))){
			global $db;
			$sql = "DELETE FROM " . self::RELATION_TABLE . " WHERE medlem_id = " . $medlem->getId();
			$db->nonquery($sql);
		}

	}
		
}

?>
