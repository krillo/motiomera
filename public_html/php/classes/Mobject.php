<?php
/**
 * Mobject
 *
 * This class is the core of the framework used for Motiomera. All classes (except for helper classes and utils) should inherit from Mobject.
 * Mobject contains functionality that handles saving and loading of objects, gettings lists of objects based on certain requirements, and so on.
 * 
 * The functions specified here cannot be called directly, as this is an abstract class. They are always used by the subclassess instead, often using parent::function()
 *
 * @package default
 * @author Henrik Skotth
 */
abstract class Mobject
{
	/**
	 * The id of the object in the database
	 *
	 * @var int
	 */
	
	protected $id;
	
	/**
	 * The fields variable is an array consisting of all the fields this class uses that are to be stored in the database, along with the datatypes of these fields. Valid datatypes are int and str (this is used when handling saving etc). Example when used in a inheriting class:
	 * protected $fields = array(
	 *
	 *	"fNamn" => "str",
	 *	"eNamn" => "str",
	 *	"aNamn" => "str",
	 *	"kon" => "str",
	 *	"fodelsear" => "int",
	 *	"kommun_id" => "int",
	 * );
	 *
	 * @var array
	 */
	protected $fields = array();
	
	/**
	 * Constant that defines the prefix of the tables used by classes. Can be overridden by inheriting classes if needed.
	 *
	 */
	const TABLEPREFIX = "mm_";

	// Felkoder
	// -1 Ett eller flera argument saknas

	// -2 Kunde inte hitta id

	/**
	 * Makes sure that the object is really unset when the script is terminated. This might lessen the impact of any memory leaks.
	 *
	 * @return void
	 */
	public function __destruct()
	{
		unset($this);
	}
	
	/**
	 * Makes sure that all variables defined in $fields are saved when serializing
	 *
	 * @return void
	 */
	public function __sleep()
	{
		return array_merge(array(
			"id"
		) , array_keys($this->fields));
	}
	
	/**
	 * Runs through all str fields class to remove possible problems with linebreaks when used with SQL
	 *
	 * @uses Security::secure_data() to do the actual problem fixing
	 * @return void
	 */
	private function secureData()
	{

		foreach($this->fields as $field => $type) {
			
			if ($type == "str") {
				$this->{$field} = Security::secure_data($this->{$field});
			}
		}
	}
	
	/**
	 * Deletes the current object from the database. This is often overloaded by children in order to clean up related data
	 *
	 * @uses DB::nonquery() to run the sql query
	 * @uses Mem::delete() to remove any memcache entries
	 * @return void
	 */
	public function delete()
	{
		global $db;
		$sql  = "delete from " . $this->getTable();
		$sql .= " where id = " . (int)$this->getId();
		$db->nonquery($sql);
	}
	
	/**
	 * Saves the object to database. Automatically creates a new row in the database if the object is a new one (ie, if it doesn't have an id yet).
	 *
	 * @uses id to know if the object is a new one, and to update the correct row in the database if it isn't
	 * @uses fields to know what data the class is using
	 * @uses function secureData
	 * @uses DB::nonquery() to run the sql query
	 * @uses DB::getInsertedId() to get the id of a new object
	 * @uses Mem::get() to check if the object is already in the memcache or not
	 * @uses Mem::set() to save an objects state to the memcache (used if the object isn't already in the memcache)
	 * @uses Mem::replace() to update an objects state in the memcache (used if the object is already in the memcache)
	 * @uses Security::escape() to secure against sql injections
	 * @uses function getTable to get the name of the table for the class
	 * @return void
	 */
	public function commit()
	{
		// sparar objektet till motsvarande tabell i databasen
		global $db;
		$this->secureData();

		if (!$this->id) { // Skapa ny rad
			// Generera SQL för att skapa en rad.
			$sql = "insert into " . self::getTable() .
					" (" . $this->getFields() . ") values (";
			$sql .= implode(", ", $this->getFieldsForSQL());
			$sql .= ")";
			// Kör SQL, hämta ID och populera instansvariabel.
			$db->nonquery($sql);
			$this->id = $db->getInsertedId();
		} else { // Uppdatera befintlig rad
			// Generera SQL för att uppdatera befintlig rad.
			$sql = "update " . self::getTable() . " set ";
			$sqlParts = array();
			foreach ($this->getFieldsForSQL() as $field => $value) {
				$sqlParts[] = "$field = $value";
			}
			$sql .= implode(", ", $sqlParts);
			$sql .= " where id = " . (int)$this->id;
			$db->nonquery($sql);
		}
		$db->setBufferObjects($this, self::getTable());
		return true;
	}

	/**
	 * Dummy function for returning objects without arguments. Used when loading from database.
	 *
	 * @return Object of the type of the child using the function
	 */
	abstract public static function __getEmptyObject();


	/**
	 * Creates an empty object and fills it with data loaded from the database
	 *
	 * @param class the class for which we are creating a new object
	 * @param fields an array with fieldnames and values to use for the new object
	 * @uses function getEmptyObject get an empty object of the correct type
	 * @return Object a new object of the specified type with the specified data
	 */
	private static function __getObj($class, $fields)
	{
		$newObj = call_user_func(array(
			$class,
			"__getEmptyObject"
		));
		foreach($fields as $fieldkey => $fieldvalue) {
			$newObj->$fieldkey = $fieldvalue;
		}
		return $newObj;
	}

	/**
	 * Returns the object with the specified id. This is what you use when loading an object from databse. "new Object(...)" is only used when creating a whole new object. This function is always overloaded by the child, which calls this parent function after adding needed data. Mobject::loadById is never called, and wouldn't work if it was. Instead it is called via the child.
	 *
	 * @param int|string $id the id of the object to load (most likely, can be used with other fields as well though, see the field parameter)
	 * @param string $table the table from which to load it (this is supplied by the child)
	 * @param string $field the field which should be used to match this object from the database. defaults to id (and is rarely used in any other way)
	 * @uses TABLEPREFIX to get the correct table name (prefix + supplied table name)
	 * @uses DB::getBufferObjects load buffered objects (makes sure that an object doesn't need to be loaded twice on the same pageload, this saves queries)
	 * @uses Mem::get check if the object is already in the memcache (if it is, we don't need to load it from the database)
	 * @uses DB::row runs the select query from the database and return the matching row
	 * @uses function __getObj gets the needed data and creates the object
	 * @uses DB::setBufferObjects adds the loaded object to the buffer to avoid loading it again in the future
	 * @return Object the loaded object
	 */
	protected static function loadById($id, $table, $field = "id")
	{
		$obj = array_pop(self::loadByIds(array($id), $table, $field));
		if (!$obj) {
			throw new ObjectsNotFoundException("$table: $id");
		}
		return $obj;
	}
	
	/**
	 * Loads a lot of objects at a time. Used to avoid multiple loadById calls. This saves lots of queries when using functions such as lister.
	 *
	 * @param array $ids array of ids to load
	 * @param string $table the table from which to load the objects (ie the name of the class)
	 * @param string $field which field to use for matching the data in the ids array (most likely the id field)
	 * @param string $order used if any sorting is needed (added to the sql query)
	 * @uses TABLEPREFIX to get the correct table name (prefix + supplied table name)
	 * @uses DB::getBufferObjects load buffered objects (makes sure that an object doesn't need to be loaded twice on the same pageload, this saves queries)
	 * @uses Mem::get check if the object is already in the memcache (if it is, we don't need to load it from the database)
	 * @uses DB::query runs the select query from the database and return the matching rows
	 * @uses function __getObj gets the needed data and creates the object
	 * @uses DB::setBufferObjects adds the loaded object to the buffer to avoid loading it again in the future
	 * @uses Misc::arrayKeyMerge() to merge the objects loaded by buffer or memcache with those loaded from database
	 * @return array an array of objects
	 */
	protected static function loadByIds($IDs, $objName, $field = "id", $orderBy = null)
	{
		global $db;

		$loadDebug = defined("DEBUG") && DEBUG && isset($_GET["load_debug"]);
		$bufferObjects = $db->getBufferObjects();
		$result = array();

		if ($loadDebug) {
			$stats = new Stats();
			$stats->numIDs = count($IDs);
			$stats->sql = "<none generated>";
		}

		// Check local memory storage ("buffer") for the IDs requested.
		foreach ($IDs as $idx => $objID) {
			if (isset($bufferObjects[$objName][$objID])) {
				$result[$objID] =  $bufferObjects[$objName][$objID];
				unset($IDs[$idx]);
			}
		}

		if ($loadDebug) {
			$stats->numIDsFromCache = count($result);
		}

		// Fetch any remaining objects from the database.
		if ($IDs) {
			// Ascertain that the IDs are integers.
			$intIDs = array();
			foreach ($IDs as $objID) { $intIDs[] = (int)$objID; }

			// Construct SQL
			$sql = sprintf("select * from %s where %s in (%s)",
							self::TABLEPREFIX . strtolower($objName),
							$field, implode(",", $intIDs));
			if ($orderBy) {
				$sql .= " order by $orderBy";
			}

			if ($loadDebug) {
				$stats->sql = $sql;
			}

			// Fetch objects and store in local memory ("buffer").
			$res = $db->query($sql);
			while ($row = mysql_fetch_assoc($res)) {
				$objID = (int)$row["id"];
				$obj = self::__getObj($objName, $row);
				$db->setBufferObjects($obj, $objName);
				$result[$objID] = $obj;
			}
		}

		// Wouldn't this be awesome? It would. But we can't, because a lot of
		// the code relies on being able to pass in any number of IDs and only
		// get existing objects back. <profanities />
		/* $missingIDs = array_diff(array_keys($result), $IDs);
		   if ($missingIDs) {
		  	throw new ObjectsNotFoundException(implode(", ", $missingIDs));
		   } */

		if ($loadDebug) {
			$stats->storeDifference();
			echo "\n<!--\n";
			echo "Load $stats->numIDs $objName objects\n";
			echo "$stats->numIDsFromCache objects in cache.\n";
			echo "SQL: $stats->sql\n";
			echo $stats->numIDs - count($result) . " missing objects.\n";
			echo "Memory used: $stats->memUsedFmted bytes\n";
            echo "Time spent: $stats->timeSpent seconds\n";
			echo "-->\n";
		}

		return $result;
	}
	
	/**
	 * Creates an array of objects based on a list of supplied ids. It's also possible to specify that the list should be of all ids EXCEPT those included in the array. Sorting is also possible.
	 *
	 * @param string $sender The class which calls the function, used to get the correct table
	 * @param array $ids an array of ids to include in the list
	 * @param array $notin an array of ids NOT to include in the list
	 * @param string $order by what field the list should be sorted (if any)
	 * @uses function classTotable to get the correct tablename for the supplied class
	 * @uses DB::query to get the list of id's
	 * @uses function loadByIds used to load the objects, all at once
	 * @return array An array of objects
	 */
	
	public static function listByIds($sender, $ids, $notin = false, $order = null)
	{ // returnerar en lista med objekt från en lista med id-nummer ($ids)

		global $db;
		
		if (count($ids) == 0) {
			return array();
		}
		
		if($order || $notin) {
		
			if ($notin) $inject = " not in ";
			else $inject = " in ";
			$sql = "SELECT id FROM " . self::classToTable($sender) . " WHERE id $inject (" . implode(",", $ids) . ")";
		
			if ($order) $sql.= " ORDER BY " . $order;
			$res = $db->query($sql);
			$result = array();
			$ids = array();
			while ($data = mysql_fetch_assoc($res)) {
				$ids[] = $data["id"];
			}
		}
		$result = self::loadByIds($ids, $sender);
		return $result;
	}
	
	/**
	 * Supplies a list of all objects of the class. Has to be implemented by the subclass.
	 *
	 * @return array Array of all objects
	 */
	abstract static function listAll(); // Implementeras i subklassen: returnerar en lista med alla sparade objekt

	/**
	 * Returns a comma-separated list of all the database fields used by the class
	 *
	 * @return string
	 */
	protected function getFields()
	{
		return implode(", ", array_keys($this->fields));
	}

	/**
	 * Returns field names as keys and field values for SQL use as key values.
	 */
	protected function getFieldsForSQL()
	{
		$result = array();

		foreach ($this->fields as $field => $type) {
			$value = $this->{$field};
			switch ($type) {
				case "int":
					if (!$value && $value !== 0)
						$value = "null";
					else
						$value = (int)$value;
					break;
				case "str":
				case "date":
					$value = "'" . Security::escape($value) . "'";
					break;
				default:
					throw new MobjectException("unknown field type '$type'");
			}
			$result[$field] = $value;
		}

		return $result;
	}
	
	/**
	 * Selects the value of a specific field from all objects of the class
	 *
	 * @param string $field the field which is requested
	 * @param string $class the name of the class
	 * @param string $order any sorting order
	 * @uses function classToTable to get the table for the specified class
	 * @uses DB::query() to fetch all rows from the db (one row per object)
	 * @return array an array with id as key and the value of the field as value
	 */
	public static function listField($field, $class, $order = null)
	{

		global $db;
		$table = self::classToTable($class);
		$sql = "SELECT id, $field FROM " . $table;
		
		if ($order) $sql.= " ORDER BY " . $order;
		$res = $db->query($sql);
		$result = array();
		while ($data = mysql_fetch_array($res, MYSQL_NUM)) {
			$result[$data[0]] = $data[1];
		}
		mysql_free_result($res);
		return $result;
	}
	
	
	public function getId()
	{
		return $this->id;
	}
	
	public function getTable()
	{
		return self::TABLEPREFIX . strtolower(get_class($this));
	}

	// STATIC FUNCTIONS
	/**
	 * Creates a list (an array) of objects of the class, based on the value of a certain field. Offsets, limits, types of search, sort orders and groupings can be specified as well
	 *
	 * @param string $sender the class used
	 * @param string $field the field used to select the values that we want
	 * @param string $value the value that the field should have
	 * @param string $order the field by which we should sort the list (if specified)
	 * @param string $offset an offset used to skip a number of objects (if specified)
	 * @param string $limit used to limit the number of returned objects (if specified)
	 * @param string $search if set, this overrides the value and is used instead. uses LIKE '%search%' in sql instead of =
	 * @param string $way the sort order (asc or desc)
	 * @param string $group any group by clause
	 * @uses Security::escape()
	 * @uses function classToTable to get the correct tablename for the class (the sender parameter)
	 * @uses DB::query to get a list of matching ids
	 * @uses function loadByIds to load all objects that are to be returned from the function
	 * @return array an array of objects based on the parameters
	 */
	protected static function lister($sender, $field = null, $value = null, $order = null, $offset = null, $limit = null, $search = null, $way = null, $group = null)
	{ // returnerar en lista med objekt där ett visst fält ($field) har ett visst värde ($value)

		global $db;
		$value = Security::escape($value);
		$table = self::classToTable($sender);
		$sql = "select * from $table ";
		
		if ($field != null && $search != null) {
			$sql.= "where $field like '%$search%' ";
		} elseif ($field != "" && $value != "") {
			$sql.= "where $field = '$value' ";
		}
		
		if ($group) { $sql .= "group by ". $group . " "; }
		if ($order) { $sql.= "order by " . $order . " "; }
		if ($way) {	$sql .= $way . " "; }
		
		if ($offset == null && $limit != null) {
			$sql.= "limit 0," . $limit . " ";
		} elseif ($offset) {
			$sql.= "limit " . $offset . "," . $limit . " ";
		}
		
		$res = $db->query($sql);
		$objects = array();
		while ($row = mysql_fetch_assoc($res)) {
			$objects[$row["id"]] = self::__getObj($sender, $row);
		}

		if (defined("DEBUG") && DEBUG && isset($_GET["mobject_debug"])) {
			echo "\n<!--\n";
			echo "    Running query for $sender objects.\n";
			echo "    SQL: $sql\nResults:\n";
			var_dump($objects);
			echo "\n-->\n";
		}

		return $objects;
	}
	
	/**
	 * Creates a list (an array) of objects based on a number of arguments (field = value)
	 *
	 * @param string $sender the class using multilister
	 * @param array $array an array that consists of the fields to be used and the values they need to have (field => value)
	 * @param string $order sorting order (if specified)
	 * @uses DB::query
	 * @uses function loadById to load the objects
	 * @return array the array of objects
	 */	
	protected static function multiLister($sender, $array, $order = null)
	{ // returnerar en lista med objekt där vissa fält (arrayen keys) har ett visst värde (arrayen values)

		global $db;
		$table = self::classToTable($sender);
		$sql = "SELECT id FROM $table WHERE ";
		$first = true;
		foreach($array as $field => $value) {
			
			if (!$first) {
				$sql.= "AND ";
			} else {
				$first = false;
			}
			$sql.= "$field = $value ";
		}
		
		if ($order) $sql.= "order by " . $order . " ";
		$res = $db->query($sql);
		$objects = array();
		while ($data = mysql_fetch_assoc($res)) {
			$objects[$data["id"]] = call_user_func(array(
				$sender,
				"loadById"
			) , $data["id"]);
		}
		return $objects;
	}
	
	/**
	 * Returns the correct tablename for the specified class.
	 *
	 * @param string $class the class for which we want a tablename
	 * @return string the name of the table
	 */
	public static function classToTable($class)
	{

		return self::TABLEPREFIX . strtolower($class);
	}
}

class MobjectException extends Exception { }
class ObjectsNotFoundException extends MobjectException { }
