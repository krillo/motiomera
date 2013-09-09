<?php
class DB
{	
	protected $host;	
	protected $user;	
	protected $pass;	
	protected $database;	
	protected $connection;	
	protected $querycount;	
	protected $bufferForDisplay;
	protected $bufferObjects;
	
	public function __construct($host, $user, $pass, $database)
	{
		$this->host = $host;
		$this->user = $user;
		$this->pass = $pass;
		$this->database = $database;
		$this->querycount = 0;
		$this->connect();
	}
	
	/**
	 * Function __destruct
	 * Performes varius tasks when killing class 
	 * Example:
	 *      __destruct  (  )
	 */
	public function __destruct()
	{
		unset($this);
	}
	
	/**
	 * connect to db
	 */
	public function connect()
	{		
		if (!$this->connection && $this->host != "" && $this->user != "" && $this->pass != "") {
			
			if ($this->host != "" && $this->user != "" && $this->pass != "") {
				$this->connection = mysql_connect($this->host, $this->user, $this->pass) or die(mysql_error());
				mysql_select_db($this->database, $this->connection);
				mysql_query("SET CHARACTER SET utf8");
			}
		}
	}
	
	/**
	 * executes query
	 *
	 * @param string $sql
	 * @return result
	 */
	public function query($sql)
	{
    $sqlDebug = defined("DEBUG") && DEBUG && isset($_REQUEST["sql_debug"]);
		$sql = $sql;

		if ($sqlDebug) {
      $stats = new Stats();
		}

		$this->querycount++;
		$res = mysql_query($sql, $this->connection) or $this->error(mysql_error() , $sql);

		if ($sqlDebug) {
      $stats->storeDifference();
      echo "\n<!--\n";
			echo "Query #$this->querycount: $sql\n";
			echo "Memory used: $stats->memUsedFmted bytes\n";
      echo "Time spent: $stats->timeSpent seconds\n";
      echo "-->\n";
		}
		return $res;
	}
	
	public function nonquery($sql)
	{ // Utför anrop som inte returnerar värden

		$this->query($sql);
		return mysql_affected_rows($this->connection);
	}
	
	public function value($sql)
	{ // Returnerar det första värdet från svaret

		$data = mysql_fetch_array($this->query($sql) , MYSQL_NUM);
		return $data[0];
	}
	
/**
 * Returns the first row from the reply
 * @param string $sql
 * @return string first row
 */
	public function row($sql)
	{ 
		return mysql_fetch_assoc($this->query($sql));
	}
	
	/**
	 * Returns an array with alla values in the first field
	 */
	public function valuesAsArray($sql)
	{
		$res = $this->query($sql);
		$result = array();
		while ($data = mysql_fetch_array($res, MYSQL_NUM)) {
			$result[] = $data[0];
		}
		unset($data);
		return $result;
	}

  
  /**
   * Return one row as a stdClass 
   * krillo 13-01-03
   */
	public function oneRowAsObject($sql){
		$res = $this->query($sql);
		$result = new stdClass;
		while ($data = mysql_fetch_assoc($res)) {
			foreach ($data as $field => $value) {
				$result->$field = stripslashes($value);       
			}
		}
		unset($data);
		return $result;
	}
  
  
	/** 
	 * Returnerar en array med alla värden i alla fält (Tvådimensionell).
	 * Lägger även resultatet 'id' som array key, om det finns
	 */ 
	public function allValuesAsArray($sql)
	{
		$res = $this->query($sql);
		$result = array();
		while ($data = mysql_fetch_assoc($res)) {
			foreach ($data as $field => $value) {
				$data[$field] = stripslashes($value);
			}
			if (isset($data['id'])) {
				$result[$data['id']] = $data;
			} else {
				$result[] = $data;
			}
		}
		unset($data);
		return $result;
	}
	
	public function closeConnection()
	{
		mysql_close($this->connection);
	}
	
	protected function error($msg, $sql)
	{
		throw new DBException($msg, 1, $sql);
	}
	
	public function getMaxId($table, $field = "id")
	{ // Returnerar det högsta värdet i en kolumn

		return $this->value("select max($field) as maxVal from $table");
	}
	
	public function getInsertedId()
	{ // Returnerar det senast skapade id-numret

		return mysql_insert_id($this->connection);
	}
	
	public function getQuerycount()
	{
		return $this->querycount;
	}
	
	/**
	 * Function setBufferObjects
	 * 
	 * This is to never load same Object twice
	 *
	 * Example:
	 *      setBufferObjects  ( Object, "medlem" )
	 */
	public function setBufferObjects($object, $typ)
	{
		$this->bufferObjects[$typ][$object->getId()] = $object;
	}
	
	/**
	 * Function clearBufferObjects
	 * 
	 * Clears the buffer for a specific object of a specific type, or all objects of a specific type, or all objects
	 * This is to free memory on pages that uses lots of objects
	 * 
	 * Example:
	 *      clarBufferObjects  ( "medlem", $object )
	 */
	public function clearBufferObjects($typ = false, $object = false)
	{
		if($typ) {
			if(isset($this->bufferObjects[$typ])) {
				if($object) {
					unset($this->bufferObjects[$typ][$object->getId()]);
				}
				else {
					foreach($this->bufferObjects[$typ] as $key=>$obj) {
						unset($this->bufferObjects[$typ][$key]);
					}
					unset($this->bufferObjects[$typ]);
				}
			}
		}
		else {
			foreach($this->bufferObjects as $typ=>$buf) {
				foreach($this->bufferObjects[$typ] as $key=>$obj) {
					unset($this->bufferObjects[$typ][$key]);
				}
				unset($this->bufferObjects[$typ]);
			}
		}
	}


  /**
   * Remove one object from the bufferObjects 
   * 
   * @author Krillo 
   * @date 2013-09-04
   * @param type $typ
   * @param type $id
   */
	public function removeBufferObject($typ = false, $id = 0){
		if($typ) {
			if(isset($this->bufferObjects[$typ])) {
				if($id) {
					unset($this->bufferObjects[$typ][$id]);
				}
			}
		}
	}  
  
	/**
	 * Function getBufferMedlemObjects
	 * 
	 * Gets the object buffer
	 *
	 * Example:
	 *      getBufferMedlemObjects  (  )
	 */
	public function getBufferObjects()
	{
		return $this->bufferObjects;
	}
}

class DBException extends Exception
{
	
	protected $sql;
	
	public function __construct($msg, $code, $sql = '')
	{
		$this->sql = $sql;
		
		if (!substr_count($_SERVER['HTTP_HOST'], 'motiomera.se')) {
			echo 'Database error!<br />';
			echo 'Query: ' . $sql . '<br />';
			echo 'Error: ' . mysql_error();
			throw new Exception("Database Error! " . mysql_error(), 1);
			
			exit;

		}
		parent::__construct($msg, $code);
	}
	
	public function getSql()
	{
		return $this->sql;
	}
	
	public function getCallFile()
	{
		$trace = $this->getTrace();
		return $trace[1]["file"];
	}
	
	public function getCallLine()
	{
		$trace = $this->getTrace();
		return $trace[1]["line"];
	}
}
?>
