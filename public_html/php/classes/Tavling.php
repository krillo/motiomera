<?php
/**
 * Class Tavling
 *
 * Class to handle contests
 *
 * @package    The Farm Library
 * @author     Magnus Knutas
 * @copyright  The Farm 2008
 * @license    All rights reserved by The Farm
 * @version    Release: @package_version@
 * @link       http://www.thefarm.se/
 * @since      Class available since Release 0.1
 * @created    Mon Nov 17 09:02:56 CET 2008
 */
class Tavling extends Mobject
{
	
	protected $id;
	protected $startdatum;
  protected $slutdatum;	
	
	protected $fields = array(
		'id' => 'int',
		'startdatum' => 'str',
	);
	const TABLE = "mm_tavling";
	const RELATION_TABLE = "mm_tavling_save";
	const LAG_SAVE_TABLE = "mm_lag_save";

	/**
	 *@inline magic functions
	 */
	/**
	 * Example:
	 *      __construct  (   )
	 */	
	public function __construct($startdatum, $slutdatum, $dummy_object = false)
	{		
		if (!$dummy_object = false) {
			$this->setStartDatum($startdatum);
      $this->setSlutDatum($slutdatum);			
			$this->commit();
		}
	}
	
	/**
	 * Function __getEmptyObject()
	 *
	 * Abstract class for loading an object from db, the last arg must be true and all args in constructor need to be present
	 *
	 * Example:
	 *     bool __getEmptyObject()  (   )
	 */	
	public static function __getEmptyObject()
	{
		$class = get_class();
		return new $class(true); //last arg need to be true
	}


	/**
	 * Function listAll	
	 * Lister For users
	 * Example:
	 *      listAll  (   )
	 */	
	public static function listAll()
	{
		return parent::lister(get_class());
	}
	
	/**
	 * Function saveLagList
	 * Saves the lag data for future use
	 * Example:
	 *      saveLagList  ( array of lag object  )
	 */	
	public function saveLagList($list)
	{
		global $db;
		foreach($list as $lag) {
			$sql = "INSERT INTO " . self::LAG_SAVE_TABLE . " SET 
				lag_id = " . $lag->getId() . ", 
				bildUrl = '" . $lag->getBildUrl() . "', 
				namn = '" . $lag->getNamn() . "', 
				foretag_id = " . $lag->getForetag()->getId();
			$db->query($sql);
		}
	}
	
	/**
	 * Sets the start date for competition
	 * Example: setStartDatum  (2008-10-08)
	 */	
	public function setStartDatum($date){
		$this->startdatum = $date;
	}
	
	/**
	 * Gets the start date for competition
	 */	
	public function getStartDatum(){
		return $this->startdatum;
	}

  /**
   * Sets the stop date for competition
   * Example: setSlutDatum  (2008-10-08)
   */ 
  public function setSlutDatum($date){
    $this->slutdatum = $date;
  }
  
  /**
   * Gets the start date for competition
   */ 
  public function getSlutDatum(){
    return $this->slutdatum;
  }
  		
	/**
	 * gets the object id
	 */	
	public function getId(){
		return $this->id;
	}	
	
	/**
	 * set the object id
	 */	
	public function setId($id){
		$this->id = $id;
	}
	
	/**
	 * Function getHallOfFameForetag
	 * Gets the companys that are in save tabel
	 * Example:
	 *      getHallOfFameForetag  (  )
	 */	
	public static function getHallOfFameForetag()
	{
		global $db;
		$sql = "SELECT a.foretag_id, COUNT(a.lag_id) as lag, sum(a.steg) as steg, b.namn FROM " . 
			self::RELATION_TABLE . " a, ". 
			foretag::TABLE ." b WHERE 
			a.foretag_id = b.id 
			GROUP BY foretag_id 
			ORDER BY steg DESC";
		// echo $sql;
		return $db->allValuesAsArray($sql);
	}
	
	/**
	 * Function getHallOfFameLag
	 * Gets lag that are in save tabel
	 * Example:
	 *      getHallOfFameLag  (  )
	 */	
	public static function getHallOfFameLag()
	{
		global $db;
		$sql = "SELECT a.foretag_id, a.lag_id, COUNT(a.medlem_id) as medlemmar, sum(a.steg) as steg, b.namn as lag_namn, c.namn as foretag_namn 
			FROM " . self::RELATION_TABLE . " a, " . self::LAG_SAVE_TABLE . " b, ". Foretag::TABLE ." c 
			WHERE a.lag_id = b.lag_id 
			AND a.foretag_id = c.id 
			GROUP BY lag_id 
			ORDER BY steg DESC";
		// echo $sql;
		return $db->allValuesAsArray($sql);
	}
	
	/**
	 * Function getHallOfFameMedlemmar
	 * Gets medlemmar that are in save tabel
	 * Example:
	 *      getHallOfFameMedlemmar  (   )
	 */	
	public function getHallOfFameMedlemmar()
	{
		global $db;
		$sql = "SELECT medlem_id, sum(steg) as steg  
			FROM " . self::RELATION_TABLE . " 
			GROUP BY medlem_id 
			ORDER BY steg DESC";
		// echo $sql;
		return $db->allValuesAsArray($sql);
	}

	
	
  /**
   * This function gets all basic paramaters for a member for a specific contest
   * This is added since not all members are in a lag, no post in mm_lag_save 
   *  
   * @author Krillo
   * @param string $tavlingsid
   * @param string $medlemid
   */	
  public static function getBasicMemberData($tavlingsid, $medlemid){
    global $db;
    $sql = "SELECT a.medlem_id, a.foretag_id, a.lag_id, a.steg, c.namn as foretag_namn, d.anamn as medlem_namn , a.start_datum, a.stop_datum
      FROM " . self::RELATION_TABLE . " a, " . Foretag::TABLE ." c, mm_medlem d  
      WHERE a.tavlings_id = $tavlingsid
      AND a.medlem_id = $medlemid
      AND a.foretag_id = c.id
      AND d.id = $medlemid
      GROUP BY lag_id 
      ORDER BY steg DESC";
    //echo $sql;
    $memberArray = $db->allValuesAsArray($sql);    
    //add also the rank to the array
    $memberArray[0]['rank'] =  self::member_rank($tavlingsid, $medlemid);
    return $memberArray;
  } 



  /**
   * This function gets all the paramaters for a member for a specific contest
   * 
   * <code>
   *         [medlem_id] => 6901
   *         [foretag_id] => 213
   *         [lag_id] => 730
   *         [steg] => 387663
   *         [lag_namn] => Övikskontoret
   *         [foretag_namn] => ML Huskonsult AB
   *         [medlem_namn] => Hägge
   *         [start_datum] => 0000-00-00 00:00:00
   *         [stop_datum] => 0000-00-00 00:00:00
   *         [rank] => 11
   * </code>
   * 
   * @author Krillo
   * @param string $tavlingsid
   * @param string $medlemid
   */
  public static function getResultMember($tavlingsid, $medlemid){
    global $db;
    $sql = "SELECT a.medlem_id, a.foretag_id, a.lag_id, a.steg, b.namn as lag_namn, c.namn as foretag_namn, d.anamn as medlem_namn , a.start_datum, a.stop_datum
      FROM " . self::RELATION_TABLE . " a, " . self::LAG_SAVE_TABLE . " b, ". Foretag::TABLE ." c, mm_medlem d  
      WHERE a.tavlings_id = $tavlingsid
      AND a.medlem_id = $medlemid
      AND a.lag_id = b.lag_id 
      AND a.foretag_id = c.id
      AND d.id = $medlemid
      GROUP BY lag_id 
      ORDER BY steg DESC";
    //echo $sql;
    $memberArray = $db->allValuesAsArray($sql);    
    //add also the rank to the array
    $memberArray[0]['rank'] =  self::member_rank($tavlingsid, $medlemid);
    return $memberArray;
  } 

  
  /**
   * This function counts the rank of the member for a specific contest
   * 
   * @author Krillo
   * @param string $tavlingsid
   * @param string $medlemid
   */
  public static function member_rank($tavlingsid, $medlemid){
    $var = "SET @rownum := 0;";
    $sql = "SELECT rank, medlem_id FROM (
      SELECT @rownum := @rownum + 1 AS rank, medlem_id
      FROM " . self::RELATION_TABLE . " 
      WHERE tavlings_id = $tavlingsid      
      ORDER BY steg DESC
    ) AS result WHERE medlem_id = $medlemid";     
    mysql_query($var);
    $user = mysql_fetch_assoc(mysql_query($sql));
    //echo $sql;
    //print_r($user);   
    return $user['rank'];    
  }
  
    
  
  /**
   * Get all companys for one competition in descending order
   * If $foretagid is submitted then only that company is fetched
   * 
   * 2012-08-23 Change by Krillo
   * The parameter $compDays is removed, the number of competition days are stored in the db and used for calc average
   * 
   * @author Krillo
   * @param string $tavlingsid
   * @param int $foretagid  optional
   */ 
  public static function getResultCompany($tavlingsid, $limit, $foretagid='' ){
    global $db;
    $addCompany = '';
    if(!empty($foretagid)){
      $addCompany = " AND a.foretag_id = $foretagid ";
    }
    $addLimit = '';
    if($limit > 0 ){
      $addLimit = " LIMIT $limit ";
    }                 
    $sql = "SELECT a.foretag_id, c.namn as foretag_namn, sum(a.steg) as foretag_steg_tot, sum(a.steg)/(COUNT(a.medlem_id)*a.antal_dagar) as foretag_steg_medel, a.start_datum, a.stop_datum
      FROM " . self::RELATION_TABLE . " a, " . Foretag::TABLE ." c  
      WHERE a.tavlings_id = $tavlingsid ";
    $sql .= $addCompany;          
    $sql .= " AND a.foretag_id = c.id
      GROUP BY a.foretag_id
      ORDER BY foretag_steg_medel DESC ";
    $sql .= $addLimit;    
    //echo $sql;
    return $db->allValuesAsArray($sql);
  }     
  
  
  
  /**
   * Get all compeditors for one competition in descending order
   * If $foretagid is submitted then only that company is fetched
   * It also adds a rank (rownum) to each member
   * 
   * 2012-08-23 Change by Krillo
   * The parameter $compDays is removed, the number of competition days are stored in the db and used for calc average 
   * 
   * @author Krillo
   * @param string $tavlingsid 
   * @param int $limit      if a number bigger than 0 is submitted then the result is limited at that number
   * @param int $foretagid  optional
   */ 
  public static function getResultAllMembers($tavlingsid, $limit, $foretagid=''){
    global $db;
    //reset rownum in db
    $var = "SET @rownum := 0;";
    mysql_query($var);
    $addCompany = '';          
    if(!empty($foretagid)){
      $addCompany = " AND a.foretag_id = $foretagid ";
    }
    $addLimit = '';
    if($limit > 0 ){
      $addLimit = " LIMIT $limit ";
    }    
    $sql = "SELECT a.medlem_id, d.anamn as medlem_namn, a.foretag_id, a.steg, a.steg/a.antal_dagar as steg_medel ,a.start_datum, a.stop_datum, @rownum := @rownum + 1 AS rank
      FROM " . self::RELATION_TABLE . " a, mm_medlem d   
      WHERE a.tavlings_id = $tavlingsid 
      AND d.id = a.medlem_id ";
    $sql .= $addCompany;     
    $sql .= " ORDER BY steg DESC ";
    $sql .= $addLimit;
    //echo $sql;
    return $db->allValuesAsArray($sql);
  } 
    

  /**
   * Get all teams for one competition in descending order
   * If $foretagid is submitted the only that company is fetched
   * It doesn't select lag with id -1
   * 
   * @author Krillo
   * @param string $tavlingsid
   * @param int $foretagid  optional
   */
  public static function getResultTeam($tavlingsid, $foretagid=''){
    global $db;
    $addCompany = '';
    if(!empty($foretagid)){
      $addCompany = " AND a.foretag_id = $foretagid ";
    }        
    $sql = "SELECT a.foretag_id, c.namn as foretag_namn, a.lag_id, COUNT(a.medlem_id) as medlemmar, sum(a.steg) as steg_tot, 
            sum(a.steg)/COUNT(a.medlem_id) as steg_medel, b.namn as lag_namn, b.bildUrl  
      FROM " . self::RELATION_TABLE . " a, " . self::LAG_SAVE_TABLE . " b, ". Foretag::TABLE ." c 
      WHERE a.tavlings_id = $tavlingsid ";
    $sql .= $addCompany;     
    $sql .= " AND a.lag_id = b.lag_id 
      AND a.lag_id > 0 
      AND a.foretag_id = c.id
      GROUP BY lag_id 
      ORDER BY steg_medel DESC";
    //echo $sql;
    return $db->allValuesAsArray($sql);
  }   
  
  

  /**
   * Get all the team members with data for the submitted team and tavlingsid
   * 
   * @author Krillo
   * @param int $tavlingsid
   * @param int $lagid  
   */
  public static function getResultCompanyTeamMember($tavlingsid, $lagid){
    global $db;
    $sql = "SELECT d.anamn as medlem_namn, a.medlem_id, a.steg, a.foretag_id, a.lag_id, b.namn as lag_namn, c.namn as foretag_namn  
      FROM " . self::RELATION_TABLE . " a, " . self::LAG_SAVE_TABLE . " b, ". Foretag::TABLE ." c, mm_medlem d  
      WHERE a.tavlings_id = $tavlingsid
      AND a.lag_id = $lagid
      AND a.lag_id = b.lag_id 
      AND a.foretag_id = c.id
      AND d.id = a.medlem_id
      GROUP BY a.medlem_id
      ORDER BY steg DESC";
    //echo $sql;
    return $db->allValuesAsArray($sql);
  } 
  
  

/**
 * This function gets all tavling ids for a member
 *
 * @param string $medlemid 
 * @return array
 * @author Aller Internet, Kristian Erendi
 */
  public static function getMemberCompetitions($medlemid){
    global $db;
    $sql = "SELECT medlem_id, tavlings_id, foretag_id, lag_id, start_datum, stop_datum 
      FROM " . self::RELATION_TABLE . "
      WHERE medlem_id = $medlemid";
    //echo $sql;
    $tavlingArray = $db->allValuesAsArray($sql);    
    return $tavlingArray;
  }  
 

/**
 * Return the tavlingid for the company if there is one
 *  
 * @global type $db
 * @param type $fid
 * @return type 
 * @author Reptilo, Kristian Erendi 2012-05-29
 */
public static function getTavlingsId($fid){
    global $db;
    $sql = "select distinct(tavlings_id) from mm_tavling_save where foretag_id = $fid";
    //echo $sql;
    $tid = $db->row($sql);    
    return $tid;
}  
  
  
} // END Class Tavling
?>