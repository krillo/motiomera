<?php
/**
* Class and Function List:
* Function list:
* - __construct()
* - __getEmptyObject()
* - listAll()
* - loadById()
* - listByKommun()
* - removeRattSvar()
* - addAlternativ()
* - getKommunId()
* - getKommun()
* - getFraga()
* - listAlternativ()
* - getRattSvar()
* - getRattSvarId()
* - setKommunId()
* - setFraga()
* - setKommun()
* - setRattSvar()
* - setRattSvarId()
* Classes list:
* - QuizFraga extends Mobject
*/

class QuizFraga extends Mobject
{
	
	protected $id;
	
	protected $kommun_id;
	
	protected $kommun;
	
	protected $fraga;
	
	protected $alternativ = array();
	
	protected $rattSvar_id;
	
	protected $rattSvar;
	
	protected $fields = array(
		"kommun_id" => "int",
		"fraga" => "str",
		"rattSvar_id" => "int"
	);
	
	public function __construct(Kommun $kommun, $fraga, $dummy_object = false)
	{
		
		if (!$dummy_object) {
			$this->setKommun($kommun);
			$this->setFraga($fraga);
			$this->commit();
		}
	}
	
	public static function __getEmptyObject()
	{
		$class = get_class();
		return new $class(Kommun::__getEmptyObject() , null, true);
	}

	// STATIC FUNCTIONS ///////////////////////////////////////
	
	public static function listAll()
	{
	}
	
	public static function loadById($id)
	{
		return parent::loadById($id, get_class());
	}
	
	public static function listByKommun(Kommun $kommun)
	{
		return parent::lister(get_class() , "kommun_id", $kommun->getId());
	}
	
	public function removeRattSvar()
	{
		Security::demand(EDITOR);
		$this->setRattSvarId(null);
	}

	// PUBLIC FUNCTIONS ///////////////////////////////////////
	
	public function addAlternativ($text, $rattSvar = false)
	{
		$alternativ = new QuizAlternativ($this, $text);
		
		if ($rattSvar) $this->setRattSvar($alternativ);
	}

	// SETTERS & GETTERS //////////////////////////////////////
	
	public function getKommunId()
	{
		return $this->kommun_id;
	}
	
	public function getKommun()
	{
		
		if (!$this->kommun) $this->kommun = Kommun::loadById($this->getKommunId());
		return $this->kommun;
	}
	
	public function getFraga()
	{
		return $this->fraga;
	}
	
	public function listAlternativ()
	{
		
		if (!$this->alternativ) $this->alternativ = QuizAlternativ::listByQuizFraga($this);
		return $this->alternativ;
	}
	
	public function getRattSvar()
	{
		
		if (!$this->rattSvar) 
		if ($this->getRattSvarId()) {
			$this->rattSvar = QuizAlternativ::loadById($this->getRattSvarId());
		} else return null;
		return $this->rattSvar;
	}
	
	public function getRattSvarId()
	{
		return $this->rattSvar_id;
	}
	
	public function setKommunId($id)
	{
		Security::demand(EDITOR);
		$this->kommun = null;
		$this->kommun_id = $id;
	}
	
	public function setFraga($fraga)
	{
		Security::demand(EDITOR);
		$this->fraga = $fraga;
	}
	
	public function setKommun(Kommun $kommun)
	{
		Security::demand(EDITOR);
		$this->kommun = $kommun;
		$this->kommun_id = $kommun->getId();
	}
	
	public function setRattSvar(QuizAlternativ $alternativ)
	{
		Security::demand(EDITOR);
		$this->rattSvar = $alternativ;
		$this->rattSvar_id = $alternativ->getId();
	}
	
	public function setRattSvarId($id)
	{
		Security::demand(EDITOR);
		$this->rattSvar_id = $id;
		$this->rattSvar = null;
	}
}
?>
