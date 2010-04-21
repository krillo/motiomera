<?php

/**
* Class and Function List:
* Function list:
* - __construct()
* - __getEmptyObject()
* - loadById()
* - loggaIn()
* - listAll()
* - getInloggad()
* - delete()
* - isTyp()
* - loggaUt()
* - getMedlemId()
* - getMedlem()
* - getANamn()
* - getLosenord()
* - getTyp()
* - getSessionId()
* - getDebug()
* - setMedlemId()
* - setMedlem()
* - setANamn()
* - setLosenord()
* - setTyp()
* - setSessionId()
* - setDebug()
* Classes list:
* - Admin extends Mobject
* - AdminException extends Exception
*/
/************************************************
*
* Felkoder
* -1 Ogiltig typ
* -2 Du kan inte ta bort dig själv
*
************************************************/

class Admin extends Mobject
{
	
	protected $id;
	/**  int */
	
	protected $medlem_id;
	/** int */
	
	protected $medlem;
	/** object: Medlem */
	
	protected $aNamn;
	/** string */
	
	protected $losenord;
	/** string, krypterad */
	
	protected $typ;
	/** enum */
	
	protected $sessionId;
	/** string */
	
	protected $fields = array(
		"medlem_id" => "int",
		"aNamn" => "str",
		"losenord" => "str",
		"typ" => "str",
		"sessionId" => "str",
		"debug" => "str",
		/** true/false */
	);
	
	public function __construct($aNamn, $losenord, $typ, $medlem = null, $dummy_object = false)
	{
		
		if (!$dummy_object) {
			Security::demand(SUPERADMIN);
			
			if ($medlem) {
				$this->setMedlem($medlem);
				$this->getMedlem()->setAdmin(true);
				$this->getMedlem()->commit();
			}
			$this->setANamn($aNamn);
			$this->setTyp($typ);
			$this->setLosenord($losenord);
			$this->setDebug("false");
			$this->commit();
		}
	}

	/**@inline*******************************************
	*
	* STATIC FUNCTIONS
	*
	*****************************************************/
	
	public static function __getEmptyObject()
	{
		$class = get_class();
		return new $class(null, null, null, null, true);
	}
	
	public static function loadById($id)
	{
		return parent::loadById($id, get_class());
	}
	
	public static function loggaIn($namn, $losenord, $cookie = false)
	{
		global $db;
		$namn = Security::secure_postdata($namn);
		$losenord = Security::secure_postdata($losenord);
		
		if ($namn == "" || $losenord == "") return false;
		$sql = "SELECT id
				FROM " . self::classToTable(get_class()) . " 
				WHERE anamn='$namn'";
		$id = $db->value($sql);
		
		if ($id != '') {
			$admin = Admin::loadById($id);
			$losenordKrypterat = Security::encrypt_password($id, $losenord);
			
			if ($losenordKrypterat == $admin->getLosenord()) {
				/** Lyckad inloggning */
				$sessionId = Medlem::generateSessionId();
				$admin->setSessionId($sessionId);
				$admin->commit();
				$_SESSION["mm_admin_aid"] = $id;
				$_SESSION["mm_admin_sid"] = $sessionId;
				
				if ($cookie) {
					setcookie("mm_admin_aid", $id, time() + 60 * 60 * 24 * 30, "/");
					setcookie("mm_admin_Sid", $sessionId, time() + 60 * 60 * 24 * 30, "/");
				}
				return true;
			}
		} else {
			throw new AdminException("Felaktigt Login/lösenord", -5);
		}
	}
	
	public static function listAll()
	{
		Security::demand(SUPERADMIN);
		return parent::lister(get_class());
	}
	
	public static function getInloggad()
	{
		
		if (empty($_SESSION["mm_admin_aid"]) && empty($_SESSION["mm_admin_sid"]) && !empty($_COOKIE["mm_admin_aid"]) && !empty($_COOKIE["mm_admin_sid"])) { // försöker hämta från cookie

			$_SESSION["mm_admin_aid"] = $_COOKIE["mm_admin_aid"];
			$_SESSION["mm_admin_sid"] = $_COOKIE["mm_admin_sid"];
		}
		
		if (!empty($_SESSION["mm_admin_aid"])) {
			try {
				$admin = Admin::loadById($_SESSION["mm_admin_aid"]);
				
				if ($admin->getSessionId() == $_SESSION["mm_admin_sid"]) {
					return $admin;
				} else {
					return false;
				}
			}
			catch(Exception $e) {
				return false;
			}
		} else {
			return false;
		}
	}

	/**@inline***************************************
	*
	* PUBLIC FUNCTIONS
	*
	*************************************************/
	
	public function delete()
	{
		Security::demand(SUPERADMIN);
		global $ADMIN;
		
		if ($this->getId() == $ADMIN->getId()) throw new AdminException("Du kan inte ta bort dig själv", -2);
		
		if ($this->getMedlem()) $this->getMedlem()->setAdmin(false);
		parent::delete();
	}
	
	public function isTyp($typ)
	{
		global $adminLevels;
		
		if (defined($typ)) $typ = constant($typ);
		
		if ($adminLevels[$this->getTyp() ] < $adminLevels[$typ]) {
			return false;
		} else {
			return true;
		}
	}
	
	public function loggaUt()
	{
		$this->setSessionId("");
		$this->commit();
		session_destroy();
		setcookie("mm_admin_aid", null, 0, "/");
		setcookie("mm_admin_sid", null, 0, "/");
	}

	/************************************************
	*@inline
	* SETTERS & GETTERS
	*
	************************************************/
	
	public function getMedlemId()
	{
		return $this->medlem_id;
	}
	
	public function getMedlem()
	{
		
		if (!$this->medlem && $this->getMedlemId() != "") $this->medlem = Medlem::loadById($this->medlem_id);
		return $this->medlem;
	}
	
	public function getANamn()
	{
		return $this->aNamn;
	}
	
	public function getLosenord()
	{
		return $this->losenord;
	}
	
	public function getTyp()
	{
		return $this->typ;
	}
	
	public function getSessionId()
	{
		return $this->sessionId;
	}
	
	public function getDebug()
	{
		return $this->debug;
	}
	
	public function setMedlemId($medlem_id)
	{
		$this->medlem_id = $medlem_id;
	}
	
	public function setMedlem(Medlem $medlem)
	{
		$this->medlem = $medlem;
		$this->setMedlemId($medlem->getId());
	}
	
	public function setANamn($aNamn)
	{
		$this->aNamn = $aNamn;
	}
	
	public function setLosenord($losenord)
	{
		$this->losenord = Security::encrypt_password($this->getId() , $losenord);
	}
	
	public function setTyp($typ)
	{
		$this->typ = $typ;
	}
	
	public function setSessionId($sessionId)
	{
		$this->sessionId = $sessionId;
	}
	
	public function setDebug($debug)
	{
		$this->debug = $debug;
	}
}

class AdminException extends UserException
{
	
	public function __construct($msg, $code, $medlem_id = null)
	{
		parent::__construct($msg);
	}
}
?>
