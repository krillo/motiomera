<?php
/**
* Class and Function List:
* Function list:
* - __construct()
* - __getEmptyObject()
* - addQuestion()
* - updateQuestion()
* - deleteQuestion()
* - getRightAnswerById()
* - getQuestions()
* - getProQuestions()
* - getAnswers()
* - isAnswered()
* - answerQuestion()
* - getId()
* - setNamn()
* - getNamn()
* - setMedlemId()
* - getMedlemId()
* - getMedlem()
* - setTillagd()
* - getTillagd()
* - setTilltrade()
* - getTilltrade()
* - setTilltradeForetag()
* - getTilltradeForetag()
* - setTilltradeAllaGrupper()
* - getTilltradeAllaGrupper()
* - harForetagTilltrade()
* - isAgare()
* - addTilltradeGrupp()
* - harMedlemTilltrade()
* - harGruppTilltrade()
* - getTilltradesGrupper()
* - listAll()
* - listAsArray()
* - loadById()
* - loadMedlemsQuizblock()
* - loadQuizByMedlem()
* - loadProQuiz()
* - nextQuestionID()
* - getQuestion()
* - quizExists()
* - isQuestionAnswered()
* - delete()
* Classes list:
* - MinaQuiz extends Mobject
* - MinaQuizException extends Exception
*/
/**
 * MinaQiz
 *
 * Users can create their own quizes that other users can answer.
 *
 * @package MotioMera Steg 2
 * @author Mikael Grön
 */

class MinaQuiz extends Mobject
{
	const QUIZ_TABLE = 'minaquiz';
	const QUIZ_QUESTIONS_TABLE = 'minaquiz_fragor';
	const QUIZ_ANSWERS_TABLE = 'minaquiz_besvarade';
	
	protected $id;						// int
	protected $namn;					// string
	protected $medlem_id;				// int
	protected $tillagd;					// datetime
	protected $tilltrade;				// enum (alla/vissa)
	protected $tilltrade_foretag;		// enum (ja/nej)
	protected $tilltrade_alla_grupper;	// enum (ja/nej)

	protected $fields = array(
		"id" =>						"int",
		"namn" =>					"str",
		"medlem_id" =>				"int",
		"tilltrade" =>				"str",
		"tilltrade_foretag" =>		"str",
		"tilltrade_alla_grupper" =>	"str",
		"tillagd" =>				"date",
	);
	
	public $fragor = false;
	
	public $svar = false;
	function __construct($data, $proQuiz = false, $dummy_object = false)
	{
		
		if (!$dummy_object) {
			global $USER, $SETTINGS;
			
			if (empty($data["namn"])) {
				throw new MinaQuizException("Inget namn angivet", -1);
			} else {
				$this->medlem_id = $proQuiz ? 0 : $USER->getId();
				$this->setNamn($data["namn"]);
				
				if (!$proQuiz) {

					// se om "Alla grupper" har tillgång till detta album
					$tilltrade_alla_grupper = "nej";
					
					if (isset($data["tilltrade_grupper"])) {
						foreach($data["tilltrade_grupper"] as $grupp => $value) {
							
							if ($grupp == "alla") {
								$tilltrade_alla_grupper = "ja";
								unset($grupp);
							}
						}
					}
					$this->setTilltrade($data["tilltrade"]);
					
					if (isset($data["tilltrade_foretag"])) {
						$this->setTilltradeForetag($data["tilltrade_foretag"]);
					}
					$this->setTilltradeAllaGrupper($tilltrade_alla_grupper);
				}
				
				$this->commit();
				
				if (!$proQuiz) {
					if (isset($data["tilltrade_grupper"])) {
						foreach($data["tilltrade_grupper"] as $grupp => $value) {
							$this->addTilltradeGrupp($grupp);
						}
					}
				}
				/**
				 * Lägg till frågorna till quizen
				 * De sparas i en separat tabell
				 */
				foreach($data['fraga'] as $key => $questionText) {
					$this->addQuestion($questionText, $data['ratt_svar'][$key], $data['fel_svar_1'][$key], $data['fel_svar_2'][$key]);
				}
			}
		}
	}
	
	public static function __getEmptyObject()
	{
		$class = get_class();
		return new $class(null, null, true);
	}
	
	public function addQuestion($question, $correct_answer, $wrong_answer_1, $wrong_answer_2)
	{
		
		global $db;
		
		if (isset($question) && isset($correct_answer) && isset($wrong_answer_1) && isset($wrong_answer_2)) {
			$question = mysql_real_escape_string($question);
			$correct_answer = mysql_real_escape_string($correct_answer);
			$wrong_answer_1 = mysql_real_escape_string($wrong_answer_1);
			$wrong_answer_2 = mysql_real_escape_string($wrong_answer_2);

			// Slumpa vilka svar som hamnar var och notera vilket av dem som är rätt
			$answers = array(
				$correct_answer,
				$wrong_answer_1,
				$wrong_answer_2
			);
			$corrector = array(
				$wrong_answer_1 => 'wrong',
				$wrong_answer_2 => 'wrong',
				$correct_answer => 'correct'
			);
			shuffle($answers);
			$count = 0;
			$ratt_svar = 0;
			foreach($answers as $description => $answer) {
				$count++;
				$svarname = 'svar_' . $count;
				$$svarname = $answer;
				
				if ($corrector[$answer] == 'correct') {
					$ratt_svar = $count;
				}
			}

			

			// Hitta högsta existerande 'ordning' (aka tyngd) för sorteringens skull
			$gammal_ordning = $db->value('SELECT MAX(ordning) FROM ' . parent::TABLEPREFIX . self::QUIZ_QUESTIONS_TABLE . ' WHERE minaquiz_id = ' . $this->id);
			
			if ($gammal_ordning != NULL) {
				$ordning = $gammal_ordning + 1;
			} else {
				$ordning = 0;
			}

			// Skapa SQL-satsen
			$SQL = 'INSERT INTO ' . parent::TABLEPREFIX . self::QUIZ_QUESTIONS_TABLE . ' (minaquiz_id, fraga, svar_1, svar_2, svar_3, ratt_svar, ordning)' . ' VALUES (' . $this->id . ', "' . $question . '", "' . $svar_1 . '", "' . $svar_2 . '", "' . $svar_3 . '", "' . $ratt_svar . '", ' . $ordning . ')';
			$db->query($SQL);
		}
	}
	
	public function updateQuestion($fid, $question, $correct_answer, $wrong_answer_1, $wrong_answer_2)
	{

		/*
		TODO Kolla om den här funktionen har nån funktion (phun)
		*/
		$questions = $this->getQuestions();
		
		if (isset($questions[$fid])) {
			$question = $questions[$fid];
			print_r($question);
			exit;
		} else {
			throw new MinaQuizException("Försökte uppdatera en fråga som inte fanns i quizzet", 1);
		}
	}
	
	public function deleteQuestion($fragaId)
	{
		global $db;
		$fragor = $this->getQuestions();
		
		if (isset($fragor[$fragaId])) {
			$db->query('DELETE FROM ' . parent::TABLEPREFIX . self::QUIZ_QUESTIONS_TABLE . ' WHERE id = ' . $fragaId);
			$db->query('DELETE FROM ' . parent::TABLEPREFIX . self::QUIZ_ANSWERS_TABLE . ' WHERE minaquiz_fragor_id = ' . $fragaId);
		} else {
			throw new SecurityException("Ej behörig", "Du har inte behörighet att ta bort frågan");
		}
	}
	
	public function getRightAnswerById($fid)
	{
		global $db;
		return $db->value('SELECT ratt_svar FROM ' . parent::TABLEPREFIX . self::QUIZ_QUESTIONS_TABLE . ' WHERE id = ' . $fid);
	}
	
	public function getQuestions($qid = false)
	{
		if (!isset($this) || !$this->fragor) {
			global $db;
			
			if (isset($this)) {
				$qid = $this->id;
			} elseif (!isset($qid)) {
				return false;
			}
			$questions = $db->allValuesAsArray('SELECT * FROM ' . parent::TABLEPREFIX . self::QUIZ_QUESTIONS_TABLE . ' WHERE minaquiz_id = ' . $qid . ' ORDER BY ordning;');
			
			if (isset($this)) {
				$this->fragor = $questions;
			}
		} else {
			$questions = $this->fragor;
		}
		foreach($questions as $key => $question) {
			$fraga = explode(" ", $question['fraga']);
			foreach($fraga as $subkey => $word) {
				if (strlen($word) > 40) {
					$fraga[$subkey] = substr($word, 0, 40) . '...';
				}
			}
			$questions[$key]['fraga'] = implode(" ", $fraga);
			$svar1 = explode(" ", $question['svar_1']);
			foreach($svar1 as $subkey => $word) {
				if (strlen($word) > 40) {
					$svar1[$subkey] = substr($word, 0, 40) . '...';
				}
			}
			$questions[$key]['svar_1'] = implode(" ", $svar1);
			$svar2 = explode(" ", $question['svar_2']);
			foreach($svar2 as $subkey => $word) {
				if (strlen($word) > 40) {
					$svar2[$subkey] = substr($word, 0, 40) . '...';
				}
			}
			$questions[$key]['svar_2'] = implode(" ", $svar2);
			$svar3 = explode(" ", $question['svar_3']);
			foreach($fraga as $subkey => $word) {
				if (strlen($word) > 40) {
					$fraga[$subkey] = substr($word, 0, 40) . '...';
				}
			}
			$questions[$key]['svar_3'] = implode(" ", $svar3);
		}
		unset($question, $fraga, $svar1, $svar2, $svar3, $word);
		
		return $questions;
	}
	
	public function getProQuestions()
	{
		global $SETTINGS, $db;
		$SQL = '
			SELECT question.*, quiz.namn as quiznamn
			FROM ' . parent::TABLEPREFIX . self::QUIZ_TABLE . ' quiz,
			     ' . parent::TABLEPREFIX . self::QUIZ_QUESTIONS_TABLE . ' question
			WHERE quiz.medlem_id = 0 AND
			      quiz.id = question.minaquiz_id
			ORDER BY rand()
			LIMIT 0, ' . $SETTINGS['proquizfragor_per_kommunquiz'] . ';';
		$data = $db->allValuesAsArray($SQL);
		return $data;
	}
	
	public function getAnswers($user = false, $qid = false)
	{
		
		if (isset($this)) {
			$fragor = $this->getQuestions();
		} elseif ($qid && is_numeric($qid)) {
			$fragor = MinaQuiz::getQuestions($qid);
		} else {
			throw new MinaQuizException("Försökte hämta svar utan att quiz angetts", 1);
		}
		
		if (!$fragor) {
			return false;
		}
		$fragoIDn = array();
		foreach($fragor as $id => $data) {
			$fragoIDn[] = $id;
		}
		
		if (!count($fragoIDn)) {
			return false;
		}
		$minaquiz_fragor_idn = implode(', ', $fragoIDn);
		$sql = 'SELECT * FROM ' . parent::TABLEPREFIX . self::QUIZ_ANSWERS_TABLE . ' WHERE minaquiz_fragor_id IN (' . $minaquiz_fragor_idn . ')';
		
		if ($user) {
			$sql.= ' AND medlem_id = ' . $user->getId();
		}
		global $db;
		$svaren = $db->allValuesAsArray($sql);
		foreach($svaren as $id => $svarData) {
			
			if (isset($this)) {
				$this->fragor[$svarData['minaquiz_fragor_id']]['svar'] = $svarData;
				$fragor = $this->fragor;
			} else {
				$fragor[$svarData['minaquiz_fragor_id']]['svar'] = $svarData;
			}
		}
		return $fragor;
	}
	
	public static function isAnswered($questionId)
	{
		global $db;
		global $USER;

		// Denna funktion ska inte användas utanför actions/minaquiz.php, och där görs redan koll på om användaren är inloggad.
		$res = $db->query('SELECT * FROM ' . parent::TABLEPREFIX . self::QUIZ_ANSWERS_TABLE . ' WHERE id = ' . mysql_real_escape_string($questionId) . ' AND medlem_id = ' . $USER->getId());
		return mysql_num_rows($res) ? true : false;
	}
	
	public static function answerQuestion($questionId, $answer)
	{
		global $db;
		global $USER;

		// Denna funktion ska inte användas utanför actions/minaquiz.php, och där görs redan koll på om användaren är inloggad.
		
		if ($db->query('INSERT INTO ' . parent::TABLEPREFIX . self::QUIZ_ANSWERS_TABLE . ' (medlem_id, minaquiz_fragor_id, svar)' . ' VALUES (' . $USER->getId() . ', ' . mysql_real_escape_string($questionId) . ', "' . ($answer ? 1 : 0) . '")')) {
			return true;
		} else {
			return false;
		}
	}
	/**
	 * getId
	 *
	 * Hämta quizets id
	 *
	 * @return int
	 * @author emgee
	 */
	
	public function getId()
	{
		return $this->id;
	}
	
	public function setNamn($namn)
	{
		$this->namn = $namn;
	}
	
	public function getNamn()
	{
		return stripslashes($this->namn);
	}
	
	public function setMedlemId($medlem_id)
	{
		$this->medlem_id = $medlem_id;
	}
	
	public function getMedlemId()
	{
		return $this->medlem_id;
	}
	
	public function getMedlem()
	{
		return Medlem::loadById($this->getMedlemId());
	}
	
	public function setTillagd($tillagd)
	{
		$this->tillagd = $tillagd;
	}
	
	public function getTillagd()
	{
		return $this->tillagd;
	}
	
	public function setTilltrade($tilltrade)
	{
		$this->tilltrade = $tilltrade;
	}
	
	public function getTilltrade()
	{
		return $this->tilltrade;
	}
	
	public function setTilltradeForetag($tilltrade_foretag)
	{
		$this->tilltrade_foretag = $tilltrade_foretag;
	}
	
	public function getTilltradeForetag()
	{
		return $this->tilltrade_foretag;
	}
	
	public function setTilltradeAllaGrupper($tilltrade_alla_grupper)
	{
		$this->tilltrade_alla_grupper = $tilltrade_alla_grupper;
	}
	
	public function getTilltradeAllaGrupper()
	{
		return $this->tilltrade_alla_grupper;
	}
	
	public function harForetagTilltrade()
	{
		
		if ($this->getTilltradeForetag() == "ja") {
			return true;
		} else {
			return false;
		}
	}
	/**
	 * isAgare
	 *
	 * Kontrollera om besökaren äger quizet i fråga
	 *
	 * @return bool
	 * @author emgee
	 */
	
	public function isAgare()
	{
		global $USER;
		
		if ($USER->getId() == $this->medlem_id) {
			return true;
		} else {
			return false;
		}
	}
	/**
	 * addTilltradeGrupp
	 *
	 * Lägg till en grupp som har tillgång till quizet
	 *
	 * @param string $grupp_id
	 * @return void
	 * @author emgee
	 */
	
	public function addTilltradeGrupp($grupp_id)
	{
		global $db;
		$db->nonquery("INSERT INTO mm_fotoalbumGruppAcl (fotoalbum_id, grupp_id) VALUES ('" . $this->getId() . "', '" . $grupp_id . "')");
	}
	
	public function harMedlemTilltrade($medlem)
	{
		
		if (Security::authorized(USER) == false) {

			// icke inloggad besökare
			
			if ($this->getTilltrade() == "alla") {
				return true;
			} else {
				return false;
			}
		}

		// alla har tillträde
		
		if ($this->getTilltrade() == "alla") return true;

		// ägare har självklart tilltrade till sina egna album
		
		if ($this->isAgare()) return true;

		// gå igenom användarens grupper och se om nån av dem har direkt tillträde
		$grupper = Grupp::listByMedlem($medlem);
		
		if ($grupper != null) {
			foreach($grupper as $grupp) {
				
				if ($this->harGruppTilltrade($grupp->getId() , $this->getId()) == true) {

					// tillträde via grupp
					return true;
				}
			}
		}

		// om ALLA grupper har tillgång,
		// hämta alla användarens grupper OCH ägarens grupper och se om de båda är medlemmar i samma grupp

		
		if ($this->getTilltradeAllaGrupper() == "ja") {
			$agare_grupper = Grupp::listByMedlem($this->getMedlem());
			
			if ($grupper != null && $agare_grupper != null) {
				foreach($grupper as $grupp) {
					foreach($agare_grupper as $agare_grupp) {
						
						if ($agare_grupp->getId() == $grupp->getId()) {

							// gemensam grupp
							return true;
						}
					}
				}
			}
		}

		// ta reda på om ägaren av quizet och besökaren är medlemmar i samma företag
		// samt om företagsmedlemmar har tilltrade till detta quiz

		$foretag = Foretag::loadByMedlem($medlem);
		
		if ($foretag != null) {
			$foretag_id = $foretag->getId();
		}
		$medlem_foretag = Foretag::loadByMedlem($this->getMedlem());
		
		if ($medlem_foretag != null) {
			$medlem_foretag_id = $medlem_foretag->getId();
		}
		
		if ($foretag != null && $medlem_foretag != null && $foretag_id == $medlem_foretag_id && $this->harForetagTilltrade() == true) {
			return true;
		}
		return false;
	}
	
	public static function harGruppTilltrade($grupp_id, $quiz_id)
	{
		global $db;
		$sql = $db->query("SELECT * FROM mm_minaquizGruppAcl WHERE grupp_id = " . Security::secure_data($grupp_id) . " AND minaquiz_id = " . $quiz_id);
		
		if (mysql_num_rows($sql) > 0) {
			return true;
		} else {
			return false;
		}
	}
	
	public function getTilltradesGrupper()
	{
		global $db;
		$grupper = array();
		$sql = $db->query("SELECT * FROM mm_minaquizGruppAcl WHERE minaquiz_id = " . $this->getId());
		while ($row = mysql_fetch_array($sql, MYSQL_ASSOC)) {
			$grupper[] = $row["grupp_id"];
		}
		
		if (count($grupper) == 0) {

			// inga grupper har tillgång till detta quiz
			return null;
		} else {
			return $grupper;
		}
	}
	static function listAll()
	{
		return parent::lister(get_class());
	}
	
	public static function listAsArray($medlem, $order_by = "namn ASC")
	{
		global $db;
		$medlem_id = is_int($medlem) ? $medlem : $medlem->getId();
		$minaquiz = $db->allValuesAsArray("	SELECT
											*
										FROM
											mm_minaquiz
										WHERE
											medlem_id = '" . $medlem_id . "'
										ORDER BY
											$order_by
		");
		foreach ($minaquiz as $id => $quiz) {
			$fragor = MinaQuiz::getQuestions($id);
			$minaquiz[$id]['fragor'] = $fragor;
			$minaquiz[$id]['antal_fragor'] = count($fragor);
		}
		return $minaquiz;
	}
	static function loadById($id)
	{
		return parent::loadbyid($id, self::QUIZ_TABLE);
	}
	
	public function hasQuiz($member)
	{
		global $db;
		$val = $db->value('SELECT id FROM ' . parent::TABLEPREFIX . self::QUIZ_TABLE . ' WHERE medlem_id = ' . $member->getId());
		return is_numeric($val) ? true : false;
	}
	
	public function loadMedlemsQuizblock($member, $antal = 5, $minSida = false)
	{
		global $db;
		global $USER;
		$userTemp = $USER;	
		$memberId = is_int($member) ? $member : $member->getId();
		$allQuiz = $db->allValuesAsArray('SELECT id, namn, medlem_id FROM ' . parent::TABLEPREFIX . self::QUIZ_TABLE . ' WHERE medlem_id = ' . $memberId . ' ORDER BY id DESC LIMIT 0, ' . $antal);
		$quiz = array();
		foreach($allQuiz as $id => $thequiz) {
			
			if (!isset($USER)) {
				$userTemp = false;
			}
			$quizObject = MinaQuiz::loadById($id);
			$quizObject->getQuestions();
			$allFragor = $quizObject->getAnswers($userTemp);
			$fragor = array();
			foreach($allFragor as $id => $fraga) {
				if (!isset($fraga['svar']) || $minSida) {
					$fragor[$id] = $fraga;
				}
			}
			
			if (count($fragor)) {
				$quiz[$id] = $thequiz;
				$quiz[$id]['fragor'] = $fragor;
				$quiz[$id]['antal_fragor'] = count($quiz[$id]['fragor']);
			}
		}
		return $quiz;
	}
	
	public function loadQuizByMedlem($member)
	{
		global $db;
		global $USER;
		$memberId = is_int($member) ? $member : $member->getId();
		$allQuiz = $db->allValuesAsArray('SELECT id FROM ' . parent::TABLEPREFIX . self::QUIZ_TABLE . ' WHERE medlem_id = ' . $memberId . ' ORDER BY id DESC');
		$quiz = array();
		foreach($allQuiz as $id) {
			
			if (!isset($USER)) {
				$USER = false;
			}
			$quizObject = MinaQuiz::loadById($id);
			$quiz[$quizObject->getId() ] = $quizObject;
		}
		return $quiz;
	}
	
	public function loadProQuiz()
	{
		return MinaQuiz::loadQuizByMedlem(0);
	}
	
	public function nextQuestionID($QuizID, $userId)
	{

		// Hämta IDt för nästa fråga. Returnerar false om inga frågor finns kvar.
		global $db;
		
		if (is_numeric($QuizID) && is_numeric($userId)) {
			$res = $db->query('SELECT id FROM ' . parent::TABLEPREFIX . self::QUIZ_QUESTIONS_TABLE . ' WHERE minaquiz_id = ' . $QuizID . ' AND id not in ' . '(SELECT minaquiz_fragor_id FROM ' . parent::TABLEPREFIX . self::QUIZ_ANSWERS_TABLE . ' WHERE medlem_id = ' . $userId . ')' . ' ORDER BY ordning desc');
			
			if (!mysql_num_rows($res)) {
				return false;
			} else {
				$data = mysql_fetch_row($res);
				return $data[0];
			}
		} else {
			throw new MinaQuizException('Ett fel uppstod', 'Frågan kunde inte hämtas');
		}
	}
	
	public function getQuestion($QuizID, $number = 1)
	{

		// Hämta fråga efter QuizID och siffra i ordningen.
		global $db;

		// Felkoll (Hackerkoll)
		
		if (!isset($QuizID) || !is_numeric($QuizID) || !is_numeric($number)) throw new MinaQuizException('Ett fel uppstod', 'Frågan kunde inte hämtas');

		// Fixa ner nuffran till databaskompatibel
		
		if ($number <= 1) $number--;
		else $number = 0;
		
		if (self::quizExists($QuizID)) {
			$fraga = $db->allValuesAssArray('SELECT * FROM ' . parent::TABLEPREFIX . self::QUIZ_QUESTIONS_TABLE . ' WHERE minaquiz_id = ' . mysql_real_escape_string($QuizID) . ' ORDER BY ordning desc LIMIT ' . $number . ', 1');
			return $fraga;
		}
		return false;
	}
	
	public function quizExists($QuizID)
	{
		global $db;
		
		if (!is_numeric($QuizID)) {
			throw new MinaQuizException('Ett fel uppstod', 'Quizet kunde inte hämtas');
		}
		$sql = 'SELECT * FROM ' . parent::TABLEPREFIX . self::QUIZ_TABLE . ' WHERE id = ' . mysql_real_escape_string($QuizID);
		$res = $db->query($sql);
		return mysql_num_rows($res) ? true : false;
	}
	
	public function isQuestionAnswered($QuestionID = false, $userId)
	{
		
		if (is_numeric($QuestionID) && $userId) {
			$res = $db->query('SELECT * FROM ' . parent::TABLEPREFIX . self::QUIZ_ANSWERS_TABLE . ' WHERE medlem_id = ' . $userId . ' AND minaquiz_fragor_id = ' . $QuestionID);
			return mysql_num_rows($res) ? true : false;
		} else {
			throw new MinaQuizException('Ett fel uppstod', 'Frågan kunde inte hämtas');
		}
	}
	
	public function delete()
	{
		global $db;

		// Kolla om det är ägaren eller kanske en admin som försöker ta bort quizet
		
		if (Security::authorized(ADMIN) || Security::authorized(USER, $this->getMedlem())) {

			// Hämta och loopa igenom alla quizets frågor
			$fragor = $this->getQuestions();
			foreach($fragor as $fraga) {

				// Ta bort frågan
				$this->deleteQuestion($fraga['id']);

				// Ta bort alla svar som hör till frågan
				$db->query('DELETE FROM ' . parent::TABLEPREFIX . self::QUIZ_ANSWERS_TABLE . ' WHERE minaquiz_fragor_id = ' . $fraga['id']);
			}

			// Ta bort själva quizet
			parent::delete();
		} else throw new SecurityException("Ej behörig", "Du har inte behörighet att ta bort quizet");
	}
}

class MinaQuizException extends Exception
{
}
?>
