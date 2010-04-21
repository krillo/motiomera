<?php
/**
* Class and Function List:
* Function list:
* - __construct()
* - __getEmptyObject()
* - loadById()
* - listByIds()
* - listAll()
* - removeAllMedlemRader()
* - delete()
* - getAnslagstavlaId()
* - getMedlemId()
* - getMedlem()
* - getAnslagstavla()
* - getText()
* - getTs()
* - setAnslagstavlaId()
* - setMedlemId()
* - setText()
* - setTs()
* - getDatum()
* Classes list:
* - AnslagstavlaRad extends Mobject
* - AnslagstavlaRadException extends Exception
*/

class AnslagstavlaRad extends Mobject
{
	
	protected $id; // int

	
	protected $anslagstavla_id; // int

	
	protected $anslagstavla; // Anslagstavla

	
	protected $medlem_id; // int

	
	protected $medlem; // Medlem

	
	protected $text; // String

	
	protected $ts; // int

	
	protected $fields = array(
		"anslagstavla_id" => "int",
		"medlem_id" => "int",
		"text" => "str",
		"ts" => "str"
	);

	// Felkoder
	// -1 Fšrsšk att flytta en rad till en annan anslagstavla

	// -2 Fšrsšk att byta medlem som skrivit en rad

	// -3 Fšrsšk att byta tidsstŠmpel pŒ en rad

	// -4 heltalsfel

	
	public function __construct($anslagstavla_id, $medlem_id, $text, $dummy_object = false)
	{
		
		if (!$dummy_object) {
			$this->setAnslagstavlaId($anslagstavla_id);
			$this->setMedlemId($medlem_id);
			$this->setText($text);
			$this->setTs(time());
			$this->commit();
		}
	}
	
	public static function __getEmptyObject()
	{
		$class = get_class();
		return new $class(null, null, null, true);
	}
	
	public static function loadById($id)
	{
		return parent::loadById($id, get_class());
	}
	
	/**
	 * 090511 krillo - added order by
	 * pass parameter like this $order = " ts desc "   
	 */
	public static function listByIds($ids, $order = null)
	{		
		return parent::listByIds(get_class() , $ids, false, $order);
	}
	
	
	public static function listAll()
	{
		return parent::lister(get_class());
	}
	
	public static function removeAllMedlemRader(Medlem $medlem)
	{
		$rader = self::lister(get_class() , "medlem_id", $medlem->getId());
		foreach($rader as $rad) $rad->delete();
	}
	
	public function delete()
	{
		parent::delete();
	}

	// SETTERS & GETTERS ////////////////////////////////////////
	
	public function getAnslagstavlaId()
	{
		return $this->anslagstavla_id;
	}
	
	public function getMedlemId()
	{
		return $this->medlem_id;
	}
	
	public function getMedlem()
	{
		
		if (empty($this->medlem)) {
			$this->medlem = Medlem::loadById($this->medlem_id);
		}
		return $this->medlem;
	}
	
	public function getAnslagstavla()
	{
		
		if (empty($this->anslagstavla)) {
			$this->anslagstavla = Anslagstavla::loadById($this->anslagstavla_id);
		}
		return $this->anslagstavla;
	}
	
	public function getText()
	{
		$text = strip_tags($this->text, '<p><a>');
		$text = explode(" ", $text);
		foreach($text as $key => $ord):
			
			if (strlen($ord) > 40) {
				$text[$key] = substr($ord, 0, 40) . '...';
			}
		endforeach;
		$text = implode(" ", $text);
		return $text;
	}
	
	public function getTs()
	{
		return $this->ts;
	}
	
	public function setAnslagstavlaId($id)
	{
		
		if (!Misc::isInt($id)) {
			throw new AnslagstavlaException('$id mŒste vara ett heltal', -4);
		}
		
		if ($this->anslagstavla_id) {
			throw new AnslagstavlaException('En rad kan inte flyttas till en annan anslagstavla', -1);
		}
		$this->anslagstavla_id = $id;
	}
	
	public function setMedlemId($id)
	{
		
		if (!Misc::isInt($id)) {
			throw new AnslagstavlaException('$id mŒste vara ett heltal', -4);
		}
		
		if ($this->medlem_id) {
			throw new AnslagstavlaException('Du kan inte byta medlem pŒ en anslagstavlerad', -2);
		}
		$this->medlem_id = $id;
	}
	
	public function setText($text)
	{
		$this->text = $text;
	}
	
	public function setTs($ts)
	{
		
		if (!Misc::isInt($ts)) {
			throw new AnslagstavlaException('$ts mŒste vara ett heltal', -4);
		}
		
		if ($this->ts) {
			throw new AnslagstavlaException('Du kan inte byta tidsstŠmpel pŒ en rad', -3);
		}
		$this->ts = $ts;
	}
	
	public function getDatum()
	{
		return date("Y-m-j h:i", $this->ts);
	}
}

class AnslagstavlaRadException extends Exception
{
}
?>
