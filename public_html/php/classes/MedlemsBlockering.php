<?php
/**
* Class and Function List:
* Function list:
* - blockeraMedlem()
* - clearBlocked()
* - verifyBlocked()
* - __construct()
* Classes list:
* - MedlemsBlockering
* - MedlemsBlockeringException extends Exception
*/
/**
 * Basic description of the class : Handles mailblocking for members
 *
 * @author Mattias Borén, The Farm
 * @version 1.0
 *
 *
 *
 * MedlemsBlockeringException codes
 *
 * 1	Unable to load Member - Medlem
 * 2	Unable to load Member - Target
 * 3	Target is in Members adressbook (remove before blocking)
 * 4	Member already blocked (attempting to re-block member)
 * 5	Attempting to send mail to a blocked user
 * 6	Attempting to send mail to a user who got the sender blocked
 *
 */

class MedlemsBlockering
{
	const TABLE = 'mm_blockeradmedlem';
	
	public static function blockeraMedlem($medlem, $target)
	{
		global $USER;

		//verify that member aint a friend
		$medlemObj = Medlem::loadById($medlem);
		$targetObj = Medlem::loadById($target);
		
		if (empty($medlemObj)) throw new MedlemsBlockeringException('Kunde ej ladda Medlem', 1);
		
		if (empty($targetObj)) throw new MedlemsBlockeringException('Kunde ej ladda Target', 2);
		Security::demand(USER, $medlemObj);
		
		if ($medlemObj->inAdressbok($targetObj)) throw new MedlemsBlockeringException('Kan ej blockera angiven medlem eftersom du har medlemmen som vänn', 3);
		
		if (self::verifyBlocked($medlem, $target)) throw new MedlemsBlockeringException('Du har redan blockerat medlemmen', 4);
		global $db;
		$sql = 'INSERT INTO ' . self::TABLE . ' (medlem_id, blockerad_medlem_id, date) VALUES (' . mysql_real_escape_string($medlem) . ',' . mysql_real_escape_string($target) . ',NOW())';
		return $db->nonquery($sql);
	}
	
	public static function clearBlocked($medlem, $target = null)
	{
		global $USER;
		Security::demand($USER->getId() , $medlem);
		global $db;
		$sql = 'DELETE FROM ' . self::TABLE . ' WHERE medlem_id = ' . mysql_real_escape_string($medlem) . ($target != null ? '' : ' AND blockerad_medlem_id = ' . mysql_real_escape_string($target));
		return $db->nonquery($sql);
	}
	/** returns true if blocked */
	
	public static function verifyBlocked($medlem, $target)
	{
		global $db;
		$sql = 'SELECT * FROM ' . self::TABLE . ' WHERE medlem_id = ' . mysql_real_escape_string($medlem) . ' AND blockerad_medlem_id = ' . mysql_real_escape_string($target) . ' LIMIT 1';
		return ($db->row($sql)) ? true : false;
	}
}

class MedlemsBlockeringException extends Exception
{
	
	public function __construct($msg, $code)
	{
		parent::__construct($msg, $code);
	}
}
?>
