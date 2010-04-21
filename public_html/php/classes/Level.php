<?php
/**
* Class and Function List:
* Function list:
* - __construct()
* - __getEmptyObject()
* - getNamn()
* - setNamn()
* - makeDefault()
* - setNonDefault()
* - listAll()
* - loadById()
* - getDefault()
* Classes list:
* - Level extends Mobject
* - LevelException extends Exception
*/

class Level extends Mobject
{
	
	protected $id; // int

	
	protected $namn; // string

	
	protected $isDefault; // int
	
	static $theDefault; // Level

	
	protected $fields = array(
		"namn" => "str",
		"isDefault" => "int"
	);
	const TABLE = "mm_level";
	
	public function __construct($namn, $dummy_object = false)
	{
		
		if (!$dummy_object) {
			$this->setNamn($namn);
			$this->setNonDefault();
			$this->commit();
		}
	}
	
	public static function __getEmptyObject()
	{
		$class = get_class();
		return $newObj = new $class(null, true);
	}

	// PUBLIC FUNCTIONS //
	
	public function getNamn()
	{
		return $this->namn;
	}
	
	public function setNamn($namn)
	{
		Security::demand(ADMIN); // admins only

		$namn = trim($namn); // remove whitespace

		$this->namn = $namn;
	}
	
	public function makeDefault()
	{
		global $db,$Memcache;
		self::$theDefault = $this;
		$this->isDefault = 1;
		$this->commit();

		// make all others non-default:
		$sql = "UPDATE " . self::TABLE . " SET isDefault=0 WHERE id<>" . $this->id;
		$db->query($sql);
		
		$Memcache->flush();
	}
	
	public function setNonDefault()
	{
		$this->isDefault = 0;
	}

	// STATIC FUNCTIONS //
	
	public static function listAll()
	{
		return parent::lister(get_class() , null, null, "namn");
	}
	
	public static function loadById($id)
	{
		return parent::loadById($id, get_class());
	}
	
	public static function getDefault()
	{
		if(!self::$theDefault) {
			// we use loadById to load the object with an "isDefault" value of 1
			try {
				self::$theDefault = parent::loadById(1, get_class() , "isDefault");
				return self::$theDefault;
			}
			catch(Exception $e) {

				// no default found, return false instead
				return false;
			}
		}
		else {
			
			return self::$theDefault;
		}
	}
}

class LevelException extends Exception
{
}
?>
