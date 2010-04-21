<?php
/**
* Class and Function List:
* Function list:
* - __construct()
* - __getEmptyObject()
* - loadById()
* - listAll()
* - listField()
* - listNamn()
* - setNamn()
* - setEnhet()
* - setVarde()
* - setBeskrivning()
* - setBorttagen()
* - getNamn()
* - getEnhet()
* - getVarde()
* - getBeskrivning()
* - getBorttagen()
* Classes list:
* - Aktivitet extends Mobject
* - AktivitetException extends Exception
*/

class Aktivitet extends Mobject
{
	
	protected $namn; //string
	protected $enhet; //string
	protected $varde; //int
	protected $beskrivning; //string
	protected $borttagen; //string enum('ja','nej')
	protected $svarighetsgrad;

	protected $fields = array(
		"namn" => "str",
		"enhet" => "str",
		"varde" => "int",
		"beskrivning" => "str",
		"borttagen" => "str",
		"svarighetsgrad" => "str",
	);
	
	const TABLE = "mm_aktivitet";

	// Felkoder
	// -1 $namn är för kort

	// -2 $enhet är för kort

	// -3 $varde måste vara ett heltal

	// -4 $borttagen måste vara 'ja' eller 'nej'

	
	public function __construct($namn, $enhet, $varde, $beskrivning, $dummy_object = false)
	{
		
		if (!$dummy_object) {
			Security::demand(EDITOR);
			$this->setNamn($namn);
			$this->setEnhet($enhet);
			$this->setVarde($varde);
			$this->setBeskrivning($beskrivning);
			$this->setBorttagen('nej');
			$this->commit();
		}
	}
	
	public static function __getEmptyObject()
	{
		$class = get_class();
		return new $class(null, null, null, null, true);
	}

	// STATIC FUNCTIONS ///////////////////////////////////////
	
	public static function loadById($id)
	{
		return parent::loadById($id, get_class());
	}
	
	public static function listAll($group = null)
	{
		return parent::lister(get_class() , null, null, "namn", null, null, null, null, $group);
	}
	
	public static function listField($field)
	{
		return parent::listField($field, get_class());
	}
	
	public static function listNamn()
	{
		global $db;
		$sql = "SELECT id, namn FROM " . self::classToTable(get_class());
		$res = $db->query($sql);
		$result = array();
		while ($data = mysql_fetch_assoc($res)) {
			$result[$data["id"]] = $data["namn"];
		}
		return $result;
	}

	// SETTERS & GETTERS ///////////////////////////////////////////////////////
	
	public function setNamn($namn)
	{
		
		if ($namn == "") throw new AktivitetException('$namn är för kort', -1);
		$this->namn = $namn;
	}
	
	public function setEnhet($enhet)
	{
		
		if ($enhet == "") throw new AktivitetException('$enhet är för kort', -2);
		$this->enhet = $enhet;
	}
	
	public function setVarde($varde)
	{
		
		if (!Misc::isInt($varde)) throw new AktivitetException('$varde måste vara ett heltal', -3);
		$this->varde = $varde;
	}
	
	public function setBeskrivning($beskrivning)
	{
		$this->beskrivning = $beskrivning;
	}
	
	public function setBorttagen($borttagen)
	{
		
		if (in_array($borttagen, array(
			'ja',
			'nej'
		))) {
			$this->borttagen = $borttagen;
		} else {
			throw new AktivitetException("Fel värde för Borttagen. Antingen 'ja' eller 'nej'. Inget annat.", -4);
		}
	}
	
	public function getNamn()
	{
		return $this->namn;
	}	
	
	public function getEnhet()
	{
		return $this->enhet;
	}
	
	/**
	 * Function getSvarighetsgrad
	 * 
	 * Gets the Svårighetsgrad
	 *
	 * Example:
	 *      getSvarighetsgrad  (  )
	 */
	public function getSvarighetsgrad()
	{
		return $this->svarighetsgrad;
	}
	
	/**
	 * Function setSvarighetsgrad
	 * 
	 * sets the svarighetsgrad
	 *
	 * Example:
	 *     setSvarighetsgrad( "string" )
	 */
	public function setSvarighetsgrad($s)
	{
		$this->svarighetsgrad = $s;
	}
	
	public function getVarde()
	{
		return $this->varde;
	}
	
	public function getBeskrivning()
	{
		return $this->beskrivning;
	}
	
	public function getBorttagen()
	{
		return $this->borttagen;
	}
}

class AktivitetException extends Exception
{
}
?>
