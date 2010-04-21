<?php

/**
* Class and Function List:
* Function list:
* - __construct()
* - __getEmptyObject()
* - listAll()
* - loadById()
* - listByIds()
* - listNotIds()
* - skapa()
* - setObjektTable()
* - getObjektTable()
* - setObjektId()
* - getObjektId()
* - setTagTable()
* - getTagTable()
* - setTagId()
* - getTagId()
* - setMedlemId()
* - getMedlemId()
* - setSkapad()
* - getSkapad()
* Classes list:
* - Tagg extends Mobject
*/
/*
Beskrivning
*/

class Tagg extends Mobject
{

	protected $id; // int
	protected $objekt_table; // str
	protected $objekt_id; // int
	protected $tag_table; // str
	protected $tag_id; // int
	protected $medlem_id; // int
	protected $skapad; // str
	protected $fields = array(
		'id' => 'int',
		'objekt_table' => 'str',
		'objekt_id' => 'int',
		'tag_table' => 'str',
		'tag_id' => 'int',
		'medlem_id' => 'int',
		'skapad' => 'str',
	);
	const TABLE = 'mm_tagg';

	// Felmeddelanden:
	// -1  addTag kräver två objekt.

	// -2  [Felmeddelande]

	
	public function __construct($data = array() , $dummy_object = false)
	{
		
		if (!$dummy_object) {
			
			if (count($data)) {
				$this->setObjektTable($data['objekt_table']);
				$this->setObjektId($data['objekt_id']);
				$this->setTagTable($data['tag_table']);
				$this->setTagId($data['tag_id']);
				$this->setMedlemId($data['medlem_id']);
				$this->setSkapad(time());
				$this->commit();
			}
		}
	}

	// MObject functions:
	
	public static function __getEmptyObject()
	{
		$class = get_class();
		return new $class(null, true);
	}
	
	public static function listAll()
	{
		$arr = parent::lister(get_class() , null, null);
		$ret = array();
		foreach($arr as $item) {
			
			if (!empty($item)) {
				$ret[] = $item;
			}
		}
		return $ret;
	}
	
	public static function loadById($id)
	{
		return parent::loadById($id, get_class());
	}
	
	public static function listByIds($ids)
	{
		$ids = parent::listByIds(get_class() , $ids);
		if (is_array($ids)) {
			return $ids;
		} else {
			return array();
		}
	}
	
	public static function listNotIds($ids)
	{
		return parent::listByIds(get_class() , $ids, true);
	}

	// End of MObject functions.
	// Custom functions:
	
	public static function listByTagId($id,$table)
	{
		global $db;
		$sql = "SELECT id FROM ". self::TABLE ." 
			WHERE tag_id = ". $id ." 
			AND tag_table = '". $table ."'";
		$ids = $db->valuesAsArray($sql);
		//echo $sql;
		//print_r($ids);
		if (count($ids)>1) {
		// print_r($ids);
			return self::listByIds($ids);
		} elseif(count($ids)>0) {
			return array(self::loadById($ids[0]));
		} else {
			return false;
		}
	}
	
	public function loadByObjectId($objekt_id, $objekt_table)
	{
		global $db;
			$sql = "SELECT id FROM ". self::TABLE ." 
			WHERE objekt_id =  ". $objekt_id ." 
			AND objekt_table = '". $objekt_table ."'";
		$id = $db->value($sql);
		// die($id);
		if (isset($id)) {
			return self::loadById($id);
		} else {
			return false;
		}
	}
	
	
	public static function skapa($from, $to)
	{
		global $USER;
		$medlem_id = isset($USER) ? $USER->getId() : 0;
		
		if (is_object($from) && is_object($to)) {
			$data['objekt_table'] = $from->getTable();
			$data['objekt_id'] = $from->getId();
			$data['tag_table'] = $to->getTable();
			$data['tag_id'] = $to->getId();
			$data['medlem_id'] = $medlem_id;
			return new self($data);
		} else {
			throw new TaggException("Ett fel uppstod", -1);
		}
	}

	// End of Custom functions.
	// Setters and Getters:

	// objekt_table - The table where the tagged object resides

	
	public function setObjektTable($objekt_table)
	{
		$this->objekt_table = $objekt_table;
	}
	
	public function getObjektTable()
	{
		return $this->objekt_table;
	}

	// objekt_id - The ID of the tagged object
	
	public function setObjektId($objekt_id)
	{
		$this->objekt_id = $objekt_id;
	}
	
	public function getObjektId()
	{
		return $this->objekt_id;
	}

	// tag_table - The table where the object the tagged object is tagged to resides. (pffhaha!)
	
	public function setTagTable($tag_table)
	{
		$this->tag_table = $tag_table;
	}
	
	public function getTagTable()
	{
		return $this->tag_table;
	}

	// tag_id - The ID of the object the tagged object is tagged to. ... (phew)
	
	public function setTagId($tag_id)
	{
		$this->tag_id = $tag_id;
	}
	
	public function getTagId()
	{
		return $this->tag_id;
	}

	// medlem_id - The member that created the tag. Don't specify a number or member ID for automatic and admin tags.
	
	public function setMedlemId($medlem_id = 0)
	{
		$this->medlem_id = $medlem_id;
	}
	
	public function getMedlemId()
	{
		return $this->medlem_id;
	}

	// skapad - Timestamp of creation.
	
	public function setSkapad($skapad)
	{
		$this->skapad = $skapad;
	}
	
	public function getSkapad()
	{
		return date('Y-m-d H:i:s', $this->skapad);
	}

	// End of Setters and Getters.
	
}

