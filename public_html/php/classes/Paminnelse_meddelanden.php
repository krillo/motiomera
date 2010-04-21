<?php
/**
* Class and Function List:
* Function list:
* - __construct()
* - __getEmptyObject()
* - listAll()
* - loadById()
* - listMeddelanden()
* - getId()
* - setNamn()
* - getNamn()
* - setMall()
* - getMall()
* Classes list:
* - Paminnelse_meddelanden extends Mobject
* - Paminnelse_meddelandenException extends Exception
*/
/**
 * Reminders
 *
 * Users can choose to get reminded if they haven't added steps in a while, amongst other things.
 * Admins can add reminders.
 *
 * @package MotioMera Steg 2
 * @author Mikael Grön
 */

class Paminnelse_meddelanden extends Mobject
{
	const QUERIES_TABLE = 'mm_paminnelse_sql';
	const TEMPLATES_TABLE = 'mm_paminnelse_meddelanden';
	const REMINDERS_TABLE = 'mm_paminnelse_aktiva';
	
	protected $id; // int

	
	protected $namn; // string

	
	protected $mall; // string

	
	protected $fields = array(
		'id' => 'int', // int

		'namn' => 'str', // string

		'mall' => 'str', // string

		
	);
	function __construct($data = array() , $dummy_object = false)
	{
		
		if (!$dummy_object) {
			
			if (count($data)) {
				$this->setNamn($data['namn']);
				$this->setMall($data['mall']);
				$this->commit();
				unset($data);
			}
		}
	}
	
	public static function __getEmptyObject()
	{
		$class = get_class();
		return new $class(null, null, true);
	}
	
	public static function listAll()
	{
		return parent::lister(get_class());
	}
	
	public static function loadById($meddelandeId)
	{
		return parent::loadById($meddelandeId, get_class());
	}
	
	public function listMeddelanden()
	{
		global $db;
		return $db->allValuesAsArray('SELECT meddelanden.*, COUNT(queries.meddelande_id) as queries ' . 'FROM ' . self::TEMPLATES_TABLE . ' meddelanden ' . 'LEFT JOIN ' . self::QUERIES_TABLE . ' queries ' . 'ON (queries.meddelande_id = meddelanden.id) ' . 'GROUP BY meddelanden.id, queries.meddelande_id');
	}

	// Setters & Getters
	
	public function getId()
	{
		return $this->id;
	}
	
	public function setNamn($namn)
	{
		$this->namn = $namn;
	}
	
	public function getNamn()
	{
		return $this->namn;
	}
	
	public function setMall($mall)
	{
		$this->mall = $mall;
	}
	
	public function getMall()
	{
		return stripslashes($this->mall);
	}
}
/**
 * Paminnelse_meddelandenException
 */

class Paminnelse_meddelandenException extends Exception
{
}
?>
