<?php
/**
* Class Mem
*
* This is for memoryOptimizeation
*
* @package    The Farm Library
* @author     Magnus Knutas
* @copyright  The Farm 2008
* @license    All rights reserved by The Farm
* @version    Release: @package_version@ 
* @link       http://www.thefarm.se/
* @since      Class available since Release 0.1
* @created    Sat Dec  6 18:26:20 CET 2008
*/
class Mem extends Memcache
{
	
	/**
	*@inline magic functions
	*/
	
	/**
	 * Function __construct
	 * 
	 * Starting up
	 *
	 * Example:
	 *      __construct  (   )
	 */
	public function __construct()
	{
		$this->connect(MEMCACHE_SERVER, 11211);		
		$this->setCompressThreshold(20000, 0.2);
	}
	
	/**
	 * Function __destruct
	 * 
	 * Kills the connection
	 *
	 * Example:
	 *      __destruct  (  )
	 */
	public function __destruct()
	{
		// print_r($this->getStats());
		$this->close();		
	}
	
	/**
	*@inline Public functions
	*/
	
	/**
	*@inline LISTERS
	*/
	 
	/**
	*@inline private functions
	*/
	
	
	/**
	*@inline SEETERS & GETTERS
	*/
	
	/**
	 * Function get
	 * 
	 * Gets memcached Object
	 *
	 * Example:
	 *      get  ( mm_medlem256 )
	 */
	public function get($key)
	{
		$o = parent::get($key);
		if (!empty($o)) {
			return array($o->getId() => $o);
		} else { 
			return false;
		}
	}
	
	/**
	 * Function getClassic
	 * 
	 * Gets memcached Object by piping to parent class
	 *
	 * Example:
	 *      get  ( mm_medlem256 )
	 */
	public function getClassic($key)
	{
		$o = parent::get($key);
		return $o;
	}
	
	/**
	 * Function setClassic
	 * 
	 * Sets new memcache by piping to parent class
	 *
	 * Example:
	 *      set  ( mm_medlem, ObjectArray, false, 60 )
	 */
	public function setClassic($key, $object, $compress = false, $expire = MEMCACHE_EXPIRE)
	{
		parent::set($key, $object, $compress, $expire);
	}
	
	/**
	 * Function set
	 * 
	 * Sets new memcache
	 *
	 * Example:
	 *      set  ( mm_medlem, ObjectArray, false, 60 )
	 */
	public function set($key, $objectArray, $compress = false, $expire = MEMCACHE_EXPIRE)
	{
		foreach ($objectArray as $id => $object) {
			$checkObject = $this->get($key.$id);
			if (empty($checkObject) or serialize($checkObject) != serialize($object)){
				parent::set($key.$id, $object, $compress, $expire);
			}
		}
	}
	
	/**
	*@inline STATICS
	*/
	
} // END Class Mem
