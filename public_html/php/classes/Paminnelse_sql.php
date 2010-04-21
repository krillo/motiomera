<?php
/**
* Class and Function List:
* Function list:
* - __construct()
* - __getEmptyObject()
* - listAll()
* - listQueries()
* - loadById()
* - getIllegalWord()
* - uppdateraAktiva()
* - getAktivaIDn()
* - getAktivaByPaminnelse()
* - setId()
* - getId()
* - setNamn()
* - getNamn()
* - setDagarMellanUtskick()
* - getDagarMellanUtskick()
* - setQuery()
* - getQuery()
* - setTitel()
* - getTitel()
* - setInreMall()
* - getInreMall()
* - setMeddelandeId()
* - getMeddelandeId()
* Classes list:
* - Paminnelse_sql extends Mobject
* - Paminnelse_sqlException extends Exception
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

class Paminnelse_sql extends Mobject
{
	const QUERIES_TABLE = 'mm_paminnelse_sql';
	const TEMPLATES_TABLE = 'mm_paminnelse_meddelanden';
	const REMINDERS_TABLE = 'mm_paminnelse_aktiva';
	
	protected $illegalWords = array(
		'INSERT',
		'UPDATE',
		'CREATE',
		'ALTER',
		'MODIFY',
		'DROP',
		'DELETE',
		'RENAME',
		'TRUNCATE',
		'REPLACE',
		'LOAD',
		'HANDLER',
		'DO',
		'CALL'
	);
	
	protected $id; // int

	
	protected $namn; // string

	
	protected $dagar_mellan_utskick; // int

	
	protected $query; // string

	
	protected $titel; // string

	
	protected $inre_mall; // string

	
	protected $meddelande_id; // int

	
	protected $fields = array(
		'id' => 'int', // int

		'namn' => 'str', // string

		'dagar_mellan_utskick' => 'int', // int

		'query' => 'str', // string

		'titel' => 'str', // string

		'inre_mall' => 'str', // string

		'meddelande_id' => 'int', // int

		
	);
	function __construct($data = array() , $dummy_object = false)
	{
		
		if (!$dummy_object) {
			
			if (count($data)) {
				$this->setNamn($data['namn']);
				$this->setDagarMellanUtskick($data['dagar_mellan_utskick']);
				$this->setQuery($data['query']);
				$this->setTitel($data['titel']);
				$this->setInreMall($data['inre_mall']);
				$this->setMeddelandeId(isset($data['meddelande_id']) ? $data['meddelande_id'] : 0);
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
	/**
	 * Function listQueries
	 *
	 * Gets an array of all available queries
	 *
	 * Example:
	 *     array listQueries ()
	 */
	function listQueries()
	{
		global $db;
		return $db->allValuesAsArray('SELECT queries.*, COUNT(active.sql_id) as prenumeranter ' . 'FROM ' . self::QUERIES_TABLE . ' queries ' . 'LEFT JOIN ' . self::REMINDERS_TABLE . ' active ' . 'ON (queries.id = active.sql_id) ' . 'GROUP BY queries.id, active.sql_id');
	}
	/**
	 * Function getQueryById
	 *
	 * Fetches a single query from the database
	 *
	 * Example:
	 *     array getQueryById  ( int $queryId  )
	 */
	
	public static function loadById($queryId)
	{
		return parent::loadById($queryId, get_class());
	}
	/**
	 * Function checkQuery
	 *
	 * Check the SQL query for illegal satements like INSERT, ALTER, MODIFY, DELETE, DROP and so on
	 *
	 * Example:
	 *     bool checkQuery  ( string $query  )
	 */
	
	public function getIllegalWord($query)
	{
		$list = isset($this) ? $this->illegalWords : Paminnelse_sql::illegalWords;
		$query = strtoupper($query);
		$query = explode(' ', $query);
		foreach($query as $word) {
			
			if (in_array($word, $list)) {
				unset($query, $list);
				return $word;
			}
		}
		unset($query, $word, $list);
		return false;
	}
	/**
	 * uppdateraAktiva
	 *
	 * Updates the relation table REMINDERS_TABLE with subscription to reminders per user
	 *
	 * @param obj $user
	 * @param array $notifications
	 * @return bool
	 * @author Mikael Grön
	 */
	
	public function uppdateraAktiva(&$user, $onskadePaminnelser)
	{
		global $db;
		$aktivaPaminnelser = self::getAktivaIDn($user);
		$existerandePaminnelser = self::listAll();
		foreach($aktivaPaminnelser as $aktivPaminnelse) {
			
			if (!in_array($aktivPaminnelse, $onskadePaminnelser)) {

				// Ta bort aktiv påminnelse som inte längre ösnskas
				$db->nonquery('DELETE FROM ' . self::REMINDERS_TABLE . ' ' . 'WHERE sql_id = ' . $aktivPaminnelse . ' ' . 'AND   medlem_id = ' . $user->getId());
			}
		}
		foreach($onskadePaminnelser as $onskadPaminnelse) {
			
			if (!in_array($onskadPaminnelse, $aktivaPaminnelser)) {

				// Lägg till önskad påminnelse som inte är aktiv
				$db->nonquery('INSERT INTO ' . self::REMINDERS_TABLE . ' ' . '(sql_id, medlem_id, senaste_utskick) ' . 'VALUES (' . $onskadPaminnelse . ', ' . $user->getId() . ', "' . date('Y-m-d') . '")');
			}
		}
	}
	/**
	 * getAktivaIDn
	 *
	 * Returns an array with the user's active reminder subscriptions
	 *
	 * @param obj $user
	 * @return array
	 * @author Mikael Grön
	 */
	
	public function getAktivaIDn(&$user)
	{
		global $db;
		return $db->valuesAsArray('SELECT sql_id FROM ' . self::REMINDERS_TABLE . ' ' . 'WHERE medlem_id = ' . $user->getId());
	}
	
	public function getAktivaByPaminnelse($query)
	{
		$sql_id = $query->getId();
		$datumGrans = date('Y-m-d', strtotime('-' . $query->getDagarMellanUtskick() . ' days'));
		global $db;
		return $db->valuesAsArray('SELECT medlem_id FROM ' . self::REMINDERS_TABLE . ' ' . 'WHERE sql_id = ' . $sql_id . ' ' . 'AND senaste_utskick < "' . $datumGrans . '"');
	}

	// Setters & Getters
	
	public function setId($id)
	{
		$this->id = $id;
	}
	
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
	
	public function setDagarMellanUtskick($dagar_mellan_utskick)
	{
		$this->dagar_mellan_utskick = $dagar_mellan_utskick;
	}
	
	public function getDagarMellanUtskick()
	{
		return $this->dagar_mellan_utskick;
	}
	
	public function setQuery($query)
	{
		
		if (!$illegalWord = $this->getIllegalWord($query)) {
			$this->query = $query;
		} else {
			throw new UserException("SQL-frågan får inte innehålla $illegalWord", "");
		}
	}
	
	public function getQuery()
	{
		return stripslashes(html_entity_decode($this->query));
	}
	
	public function setTitel($titel)
	{
		$this->titel = $titel;
	}
	
	public function getTitel()
	{
		return $this->titel;
	}
	
	public function setInreMall($inre_mall)
	{
		$this->inre_mall = $inre_mall;
	}
	
	public function getInreMall()
	{
		return $this->inre_mall;
	}
	
	public function setMeddelandeId($meddelande_id)
	{
		$this->meddelande_id = $meddelande_id;
	}
	
	public function getMeddelandeId()
	{
		return $this->meddelande_id;
	}
}
/**
 * Paminnelse_sqlException
 */

class Paminnelse_sqlException extends Exception
{
}
?>
