<?php
class TextEditor extends Mobject
{
	protected $id; // int
	protected $namn; // string
	protected $tema; // string
	protected $texten; // string
	protected $fields = array(
		"namn" => "str",
		"tema" => "str",
		"texten" => "str"
	);
	
	protected $teman = array(
		"Full Featured" => "Komplett",
		"Simple" => "Enkel"
	);
	const MIN_LENGTH_NAMN = 3;

	// Felmeddelanden:
	// -1  Namnet är för kort

	// -2  Temat är ogiltigt

	
	public function __construct($namn, $tema, $dummy_object = false)
	{
		
		if (!$dummy_object) {
			$this->setNamn($namn);
			$this->setTema($tema);
			
			if (!$exclude_commit) $this->commit();
		}
	}
	
	public static function __getEmptyObject()
	{
		$class = get_class();
		return new $class(null, null, true);
	}

	// PUBLIC FUNCTIONS
	
	public static function listAll()
	{
		return parent::lister(get_class());
	}
	
	public static function loadById($id, $class = false)
	{
		return parent::loadById($id, $class ? $class : get_class());
	}
	
	public static function loadByNamn($namn)
	{
		global $db;
		$sql = "SELECT id FROM " . self::classToTable(get_class()) . " WHERE namn='" . SECURITY::secure_postdata($namn) . "'";
		$id = $db->value($sql);
		
		if ($id) {
			return parent::loadById($id, get_class());
		} else {
			return false;
		}
	}

	// SETTERS & GETTERS
	
	public function getNamn()
	{
		return $this->namn;
	}
	
	public function getTema()
	{
		return $this->tema;
	}
	
	public function getTexten($for_edit = false)
	{
		
		if ($for_edit) {
			return ($this->texten);
		} else {
			return html_entity_decode($this->texten);
		}
	}
	
	public function setNamn($namn)
	{
		
		if (strlen($namn) < self::MIN_LENGTH_NAMN) {
			throw new MedlemException("Namnet är för kort: $namn", -1);
		}
		$this->namn = $namn;
	}
	
	public function setTema($tema)
	{
		
		if (array_key_exists($tema, $this->teman) === false) {
			throw new MedlemException("Felaktigt tema: $tema", -2);
		}
		$this->tema = $tema;
	}
	
	public function setTexten($texten)
	{
		$this->texten = $texten;
	}
	
	public function delete()
	{
		Security::demand(SUPERADMIN);
		parent::delete();
	}
}
?>
