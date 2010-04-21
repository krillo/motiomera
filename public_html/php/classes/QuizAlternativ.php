<?php
/**
* Class and Function List:
* Function list:
* - __construct()
* - __getEmptyObject()
* - listAll()
* - loadById()
* - listByQuizFraga()
* - delete()
* - isRattSvar()
* - getId()
* - getQuizFraga()
* - getQuizFragaId()
* - getText()
* - setQuizFraga()
* - setQuizFragaId()
* - setText()
* Classes list:
* - QuizAlternativ extends Mobject
*/

class QuizAlternativ extends Mobject
{
	
	protected $id;
	
	protected $quizFraga_id;
	
	protected $quizFraga;
	
	protected $text;
	
	protected $fields = array(
		"quizFraga_id" => "int",
		"text" => "str"
	);
	
	public function __construct(QuizFraga $quizFraga, $text, $rattSvar = false, $dummyObject = false)
	{
		
		if (!$dummyObject) {
			$this->setQuizFraga($quizFraga);
			$this->setText($text);
			
			if ($rattSvar) {
				$this->commit();
				$this->getQuizFraga()->setRattSvar($this);
				$this->getQuizFraga()->commit();
			}
			$this->commit();
		}
	}
	
	public static function __getEmptyObject()
	{
		$class = get_class();
		return new $class(QuizFraga::__getEmptyObject() , null, null, true);
	}

	// STATIC FUNCTIONS ///////////////////////////////////////
	
	public static function listAll()
	{
	}
	
	public static function loadById($id)
	{
		return parent::loadById($id, get_class());
	}
	
	public static function listByQuizFraga(QuizFraga $quizFraga)
	{
		return parent::lister(get_class() , "quizFraga_id", $quizFraga->getId());
	}

	// PUBLIC FUNCTIONS ///////////////////////////////////////
	
	public function delete()
	{
		Security::demand(EDITOR);
		
		if ($this->isRattSvar()) {
			$this->getQuizFraga()->removeRattSvar();
			$this->getQuizFraga()->commit();
		}
		parent::delete();
	}
	
	public function isRattSvar()
	{
		$rattSvar = $this->getQuizFraga()->getRattSvar();
		
		if ($rattSvar && $rattSvar->getId() == $this->getId()) {
			return true;
		} else {
			return false;
		}
	}

	// SETTERS & GETTERS //////////////////////////////////////
	
	public function getId()
	{
		return $this->id;
	}
	
	public function getQuizFraga()
	{
		
		if (!$this->quizFraga) $this->quizFraga = QuizFraga::loadById($this->getQuizFragaId());
		return $this->quizFraga;
	}
	
	public function getQuizFragaId()
	{
		return $this->quizFraga_id;
	}
	
	public function getText()
	{
		return $this->text;
	}
	
	public function setQuizFraga(QuizFraga $quizFraga)
	{
		$this->quizFraga = $quizFraga;
		$this->quizFraga_id = $quizFraga->getId();
	}
	
	public function setQuizFragaId($id)
	{
		$this->quizFraga = null;
		$this->quizFraga_id = $id;
	}
	
	public function setText($text)
	{
		$this->text = $text;
	}
}
?>
