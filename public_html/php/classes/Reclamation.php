<?php
/**
 * Reclamations
 */
class Reclamation extends Mobject
{  
  protected $id; // int
  protected $foretag_id; // int
  protected $count; // int
  protected $date; // timestamp
  protected $fields = array(
    "foretag_id" => "int",
    "count" => "int",
    "date" => "int",
  );
  
  const RELATION_TABLE = "mm_foretag";


  
  public function __construct($foretag_id, $count, $dummy_object = false)
  {    
    if (!$dummy_object) {
      $this->setForetag_id($foretag_id);
      $this->setCount($count);
      $this->commit();
    }
  }
  
  public static function __getEmptyObject()
  {
    $class = get_class();
    return new $class(null, null, true);
  }

  // STATIC FUNCTION ////////////////////////////////////////  
  public static function loadById($id)
  {
    return parent::loadById($id, get_class());
  }
  
/*
  public static function listByIds($ids, $notin = false, $order = null)
  {
    return parent::listByIds(get_class() , $ids, $notin, $order);
  }
*/  

  public static function listAll()
  {
    return parent::lister(get_class());
  }
  
  /**
   * Get an array of all reclamations made by a company
   *
   * @param int $foretag_id
   * @return array of arrays 
   */
  public static function listByForetag($foretag_id)
  {
    global $db;
    $sql = "SELECT id, count, date  FROM " . self::classToTable(get_class()) . " WHERE foretag_id = " . $foretag_id;
    $res = $db->allValuesAsArray($sql);
    return $res;
  }
  
  
  /**
   * Get the sum of all reclamations made by a company
   *
   * @param int $foretag_id
   * @return int sum 
   */
  public static function sumReclByForetag($foretag_id){
    global $db;
    $sql = "SELECT sum(count)  FROM " . self::classToTable(get_class()) . " WHERE foretag_id = " . $foretag_id;
    $res = $db->value($sql);
    return $res;
  }
  
  
/*  
  public static function listNamn($visaKm = true)
  {
    global $db;
    $sql = "SELECT id, namn FROM " . self::classToTable(get_class());
    $res = $db->query($sql);
    $result = array();
    while ($data = mysql_fetch_assoc($res)) {
      $result[$data["id"]] = $data["namn"];
      
      if ($visaKm) $result[$data["id"]].= " (" . $data["avstand"] . " km)";
    }
    return $result;
  }
  
  public static function listByMedlem(Medlem $medlem)
  {
    global $db;
    $sql = "SELECT mal_id FROM " . self::RELATION_TABLE . " WHERE medlem_id = " . $medlem->getId();
    $ids = $db->valuesAsArray($sql);
    return self::listByIds($ids, false, "id DESC");
  }
*/
  
  
  // SETTERS & GETTERS //////////////////////////////////////
  public function getId()
  {
    return $this->id;
  }
  
  public function getForetag_id()
  {
    return $this->foretag_id;
  }
  public function setForetag_id($arg)
  {
    $this->foretag_id = $arg;
  }
  
  public function getCount()
  {
    return $this->count;
  }
  public function setCount($arg)
  {
    $this->count = $arg;
  }  
  
  public function getDate()
  {
    return $this->date;
  }
  public function setDate($arg)
  {
    $this->date = $arg;
  }

}

class ReclamationException extends Exception
{
}
?>
