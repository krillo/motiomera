<?php
/**
* Class and Function List:
* Function list:
* - getSajtDelar()
* - medlemHasAccess()
* - levelHasAccess()
* - giveAccess()
* - removeAccess()
* - getLinkArray()
* Classes list:
* - SajtDelar
*/
/**
 * SajtDelar Class
 *
 * This helper class handles access checking as well as static data defining which site parts that
 * exists and should be under access control
 * @version 1.0
 */

class SajtDelar
{
	/**
	 * This static array contains a list of strings defining site parts to be used with access
	 * control. Only add or remove data from this array if you know what you are doing!
	 * @see getSajtDelar()
	 * @var array
	 */
	static $sajtDelar = array(
		'minSidaCalories',
		'minSidaGmaps',
		'fastaRutter',
		'minaQuiz',
		'minaQuizVisa',
		'minaQuizAndra',
		'minaQuizSkapa',
		'ProQuiz',
	);
	/**
	 * This constant defines the table used for handling the relationship between levels and site parts
	 */
	const ACCESS_TABLE = "mm_level_sajtdelar";

	// STATIC FUNCTIONS //
	
	/**
	 * Returns the site parts
	 * @see $sajtDelar
	 * @return array
	 */
	
	public static function getSajtDelar()
	{
		return self::$sajtDelar;
	}
	/**
	 * This function checks if a user has access to a specific site part. It checks the users level
	 * and uses levelHasAccess() to determine access.
	 * @param Medlem $medlem This Medlem object is the member for whom access should be checked
	 * @param string $sajtdel This string should be a sajtdel for which access should be checked.
	 * @return bool True or false depending on access
	 * @see $sajtDelar
	 * @see levelHasAccess()
	 */
	
	public static function medlemHasAccess($medlem, $sajtdel)
	{
		
		if (!isset($medlem)) {
			return false;
		}
		$levelId = $medlem->getLevelId();
		
		if (!$levelId) {

			// no level chosen, use default
			$level = Level::getDefault();
		} else {

			// load the members levelid
			$level = Level::loadById($levelId);
		}
		return self::levelHasAccess($level, $sajtdel);
	}
	/**
	 * This function checks if a Level has access to a specific site part. It checks this using
	 * a SQL query on the relationship table (ACCESS_TABLE)
	 * @global DB $db The Database class
	 * @param Level $level This Level object is the Level for which access should be checked
	 * @param string $sajtdel This string should be a sajtdel for which access should be checked.
	 * @return bool True or false depending on access
	 * @see ACCESS_TABLE
	 * @see $sajtDelar
	 */
	
	public static function levelHasAccess($level, $sajtdel)
	{
		global $db;
		
		if ($db->row("SELECT * FROM " . self::ACCESS_TABLE . " WHERE sajtdel='" . $sajtdel . "' AND levelId=" . $level->getId())) {
			return true;
		} else {
			return false;
		}
	}
	
	public static function giveAccess($level, $sajtdel)
	{
		global $db;

		// only add if access doesn't already exists:
		
		if (!SajtDelar::levelHasAccess($level, $sajtdel)) {
			$db->query("INSERT INTO " . self::ACCESS_TABLE . "(sajtdel,levelId) VALUES('" . $sajtdel . "'," . $level->getId() . ")");
		}
	}
	
	public static function removeAccess($level, $sajtdel)
	{
		global $db;
		$db->query("DELETE FROM " . self::ACCESS_TABLE . " WHERE sajtdel='" . $sajtdel . "' AND levelId=" . $level->getId());
	}
	
	public static function getLinkArray($action, $sajtdel, $levelId)
	{
		return array(
			$action,
			$sajtdel,
			$levelId
		);
	}
}
?>
