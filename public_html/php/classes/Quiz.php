<?php
/**
* Class and Function List:
* Function list:
* - __construct()
* - loadById()
* - loadByKommun()
* - saveQuiz()
* - slumpaFragor()
* - addFraga()
* - getKommun()
* - listFragor()
* - setKommunId()
* - setKommun()
* - setFragor()
* - medlemKlaratKommun()
* - removeAllMemberQuizresults()
* Classes list:
* - Quiz
* - QuizException extends Exception
*/

class Quiz
{
	
	protected $kommun;
	
	protected $kommun_id;
	
	protected $fragor = array();
	const RELATION_TABLE = "mm_quizFraga";
	const TABLE = "mm_quizsuccess";

	// Felkoder
	// -1 $antal får inte överskrida antalet frågor'

	
	public function __construct(Kommun $kommun)
	{
		$this->setKommun($kommun);
		$this->setFragor(QuizFraga::listByKommun($kommun));
	}

	// STATIC FUNCTIONS ///////////////////////////////////////
	
	public static function loadById($id)
	{
		return parent::loadById($id, get_class());
	}
	
	public static function loadByKommun(Kommun $kommun)
	{
		return new Quiz($kommun);
	}
	
	public static function saveQuiz($medlem_id, $kommun_namn)
	{
		global $db;
		$kommun = Kommun::loadByNamn($kommun_namn);
		$kommun_id = $kommun->getId();
		$sql = "SELECT COUNT(*) AS total FROM mm_quizsuccess WHERE kommun_id = " . $kommun_id . " AND medlem_id = " . $medlem_id;
		$result = $db->query($sql);
		$row = mysql_fetch_array($result);
		$total = $row['total'];
		
		if ($total == 0) {
			$date = date('Y-m-d H:s:i');
			$sql = "INSERT INTO mm_quizsuccess (kommun_id, medlem_id, quiz_date) VALUES(" . $kommun_id . ", " . $medlem_id . ", '" . $date . "')";
			$db->nonquery($sql);
		}
	}

	// PUBLIC FUNCTIONS ///////////////////////////////////////
	
	public function slumpaFragor($antal)
	{

		/*
		$fragor = shuffle($this->listFragor());
		if($antal > count($fragor))
		throw new QuizException('$antal får inte överskrida antalet frågor', -1);
		$result = array();
		$i = 0;
		foreach($fragor as $key=>$value){
		$result[$key] = $value;
		if(!$i < $antal)
		break;
		$i++;
		}
		return $result;
		*/
	}
	
	public function addFraga($fraga)
	{
		$quizFraga = new QuizFraga($this->getKommun() , $fraga);
		return $quizFraga;
	}

	// SETTERS & GETTERS //////////////////////////////////////
	
	public function getKommun()
	{
		return $this->kommun;
	}
	
	public function listFragor()
	{
		return $this->fragor;
	}
	
	public function setKommunId($id)
	{
		$this->kommun = null;
		$this->kommun_id = $id;
	}
	
	public function setKommun(Kommun $kommun)
	{
		$this->kommun = $kommun;
		$this->kommun_id = $kommun->getId();
	}
	
	public function setFragor($fragor)
	{
		$this->fragor = $fragor;
	}
	
	public static function medlemKlaratKommun($medlem, $kommun)
	{
		global $db;
		$sql = "SELECT id FROM mm_quizsuccess WHERE medlem_id = " . $medlem->getId() . " AND kommun_id = " . $kommun->getId();
		$value = $db->value($sql);
		
		if ($value) {
			return true;
		} else {
			return false;
		}
	}
	
	public static function removeAllMemberQuizresults($medlem)
	{
		global $db;
		$sql = "DELETE FROM mm_quizsuccess WHERE medlem_id = '" . $medlem->getId() . "'";
		$db->nonquery($sql);
	}
}

class QuizException extends Exception
{
}
?>
