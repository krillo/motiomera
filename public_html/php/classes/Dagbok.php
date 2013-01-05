<?php
/**
 * Description: The diary class holds both a textual diary and a grade 1 to 5 per day per user.
 * Date: 2013-01-03
 * Author: Kristian Erendi 
 * URI: http://reptilo.se 
 */
class Dagbok extends Mobject {

  protected $id; // int
  protected $mm_id; // int
  protected $kommentar; // string
  protected $betyg; // int
  protected $datum; // date	
  protected $fields = array(
      "mm_id" => "int",
      "kommentar" => "str",
      "betyg" => "int",
      "datum" => "str"
  );

  const TABLE = "mm_dagbok";

  public function __construct($mm_id, $kommentar, $betyg, $datum, $dummy_object = false) {
    if (!$dummy_object) {
      $this->setmmId($mm_id);
      $this->setKommentar($kommentar);
      $this->setBetyg($betyg);
      $this->setDatum($datum);
      $this->commit();
    }
  }

  public static function __getEmptyObject() {
		$class = get_class();
		return new $class(null, null, null, null, true);
  }

  public function getmmId() {
    return $this->mm_id;
  }

  public function setmmId($arg) {
    //Security::demand(USER);
    $this->mm_id = $arg;
  }

  public function getKommentar() {
    return $this->kommentar;
  }

  public function setKommentar($arg) {
    //Security::demand(USER);
    $arg = trim($arg); // remove whitespace
    $this->kommentar = $arg;
  }

  public function getBetyg() {
    return $this->betyg;
  }

  public function setBetyg($arg) {
    //Security::demand(USER);
    $this->betyg = $arg;
  }

  public function getDatum() {
    return $this->datum;
  }

  public function setDatum($arg) {
    //Security::demand(USER);
    $this->datum = $arg;
  }

  public static function getEntryBymmIdDate($mm_id, $date) {
    global $db;
    $sql = "SELECT id, mm_id, kommentar, betyg, datum FROM " . self::TABLE . " WHERE mm_id = $mm_id AND datum = '$date'" ;
    $result = $db->oneRowAsObject($sql);
    return $result;
  }

  public static function loadById($id) {
    return parent::loadById($id, get_class());
  }

  public static function loadBymmIdDate($mm_id, $date) {  
    global $db;
    $sql = "SELECT id FROM " . self::TABLE . " WHERE mm_id = $mm_id AND date = '$date'" ;
    $id = $db->value($sql);
    print_r($id);
    /*
    if()
    try {
      $dagbok = Dagbok::loadById($db->value($sql));
    } catch (Exception $e) {
      if ($e->getCode() == - 2)
        throw new MedlemException("E-postadressen kunde inte hittas", -16);
    }
      
     
    return $medlem;
    */
  }
    
  
  public static function listAll() {
    
  }

}

class DagbokException extends Exception {
  
}

?>
