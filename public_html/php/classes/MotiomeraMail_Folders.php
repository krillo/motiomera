<?php
/**
* Class and Function List:
* Function list:
* - __construct()
* - __getEmptyObject()
* - listAll()
* - loadById()
* - getFolders()
* - getMedlemFolders()
* - deleteMemberFolders()
* - createFolder()
* - getMedlemId()
* - setMedlemId()
* - getFolderName()
* - setFolderName()
* - getId()
* Classes list:
* - MotiomeraMail_Folders extends Mobject
* - MotiomeraMail_FoldersException extends Exception
*/

class MotiomeraMail_Folders extends Mobject
{
	
	protected $id; // int

	
	protected $folder_name; // string

	
	protected $medlem_id; // int

	
	protected $fields = array(
		"id" => "int",
		"folder_name" => "str",
		"medlem_id" => "int",
	);
	
	public function __construct($medlem_id, $dummy_object = false)
	{
		
		if (!$dummy_object) {
			$this->setMedlemId($medlem_id);
		}
	}
	
	public static function __getEmptyObject()
	{
		$class = get_class();
		return new $class(null, true);
	}

	// STATIC FUNCTION ////////////////////////////////////////
	
	public static function listAll()
	{
		return parent::lister(get_class());
	}
	
	public static function loadById($id)
	{
		return parent::loadById($id, get_class());
	}

	// PUBLIC FUNCTION ////////////////////////////////////////
	
	public function getFolders()
	{
		Security::demand(USER);
		global $db;
		$sql = "SELECT * FROM mm_motiomeramail_folders WHERE medlem_id = " . $this->getMedlemId() . " ORDER BY folder_name";
		$result = $db->query($sql);
		$folders = array();
		while ($row = mysql_fetch_array($result, MYSQL_ASSOC)) {
			$folders[] = $row;
		}
		return $folders;
	}
	
	public static function getMedlemFolders($medlem)
	{
		return parent::lister(get_class() , "medlem_id", $medlem->getId() , "folder_name");
	}
	
	public static function deleteMemberFolders(Medlem $medlem)
	{
		
		if ((Security::authorized(ADMIN)) or (Security::authorized(USER, $medlem))) {
			$folders = self::getMedlemFolders($medlem);
			foreach($folders as $folder) $folder->delete();
		}
	}
	
	public function createFolder($folder_name)
	{
		Security::demand(USER);
		$this->setFolderName($folder_name);
		global $db;
		$sql = "SELECT COUNT(*) AS tot FROM mm_motiomeramail_folders WHERE medlem_id = " . $this->getMedlemId() . " AND folder_name = '" . $this->getFolderName() . "'";
		$result = $db->query($sql);
		$row = mysql_fetch_array($result);
		
		if ($row['tot'] == 0) {
			$this->commit();
			return true;
		}
		return false;
	}

	// SETTERS & GETTERS //////////////////////////////////////
	
	public function getMedlemId()
	{
		return $this->medlem_id;
	}
	
	public function setMedlemId($medlem_id)
	{
		$this->medlem_id = $medlem_id;
	}
	
	public function getFolderName()
	{
		return $this->folder_name;
	}
	
	public function setFolderName($folder_name)
	{
		$this->folder_name = $folder_name;
	}
	
	public function getId()
	{
		return $this->id;
	}
}

class MotiomeraMail_FoldersException extends Exception
{
}
?>
