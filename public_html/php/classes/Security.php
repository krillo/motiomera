<?php

/**
* Class and Function List:
* Function list:
* - encrypt_password()
* - checkLosenStrength()
* - checkForNumber()
* - secure_postdata()
* - secure_data()
* - escape()
* - generateSessionId()
* - generateCode()
* - authorized()
* - demand()
* - getMedlemEncryptedString()
* - __construct()
* Classes list:
* - Security
* - SecurityException extends UserException
*/
/*
Hjälpklass för säkerhetshantering
*/

class Security
{
	
	public static function encrypt_password($medlem_id, $losenord)
	{
		$newpass = "";
		$letters = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789!#%&/()._-";
		for ($i = 0; $i < strlen($losenord); $i++) {
			$times = $medlem_id * (round($i / 2));
			for ($j = 0; $j < $times; $j++) {
				$newpass.= substr($letters, round(($j * $i * $i) * $medlem_id) % strlen($letters) , 1);
			}
			$newpass.= substr($losenord, $i, 1);
		}
		return sha1(md5($newpass)) . sha1(md5(md5($newpass)));
	}
	
	public static function checkLosenStrength($pass)
	{
		
		if (Security::checkForNumber($pass) == false) {
			return "fel: Lösenordet måste innehålla siffror";
		} elseif (strlen($pass) < 8) {
			return "fel: Lösenordet är för kort";
		} elseif ($pass == "*00**41##") {
			echo "!";
		} else {
			return "ok";
		}
	}
	
	public static function checkForNumber($string)
	{
		$numbers = array(
			1,
			2,
			3,
			4,
			5,
			6,
			7,
			8,
			9,
			0
		);
		$set = null;
		foreach($numbers as $number) {
			
			if (preg_match("/" . $number . "/i", $string)) {
				$set = true;
			}
		}
		
		if ($set == true) {
			return true;
		} else {
			return false;
		}
	}
	
	public static function secure_postdata($data, $quotes = true)
	{
		
		if ($quotes) {
			$data = addslashes(htmlspecialchars($data));
		} else {
			$data = addslashes(htmlspecialchars($data, ENT_NOQUOTES));
		}
		return $data;
	}
	
	public static function secure_data($sql)
	{
		return (str_replace("\r", "", str_replace("\n\r", " \n", $sql)));
	}
	
	public static function escape($data, $quotes = true)
	{	
		if ($quotes) {
			$data = mysql_real_escape_string(htmlspecialchars(htmlspecialchars_decode($data)));
		} else {
			$data = mysql_real_escape_string(htmlspecialchars(htmlspecialchars_decode($data, ENT_NOQUOTES)));
		}
	
		return $data;
	}
	
	public static function generateSessionId()
	{
		$letters = "abcdefghijklmnopqrstuvxyz1234567890";
		$result = "";
		for ($i = 0; $i < 80; $i++) {
			$result.= $letters[mt_rand(0, strlen($letters) - 1) ];
		}
		return $result;
	}
	
	public function generateCode($length)
	{
		$letters = "ABCDEFGHJKLMNPQRSTUVWXYZ23456789";
		$result = "";
		for ($i = 0; $i < $length; $i++) {
			$result.= $letters[mt_rand(0, strlen($letters) - 1) ];
		}
		return $result;
	}
	
	public function authorized($typ, $object = null)
	{

		// Kollar om inloggad medlem/admin/företag har en viss behörighet.
		// $object är en instans av den medlem/admin/företag som man vill kolla är inloggad.

		global $USER, $ADMIN, $FORETAG, $adminLevels;
		$authorized = true;
		
		if (isset($ADMIN) && $ADMIN->isTyp(SUPERADMIN)) return true;
		
		if ($typ == USER) {
			
			if (isset($USER)) {
				
				if ($object) {
					
					if ($USER->getId() != $object->getId()) $authorized = false;
				}
			} else {
				$authorized = false;
			}
		} else 
		if ($typ == FORETAG) {
			
			if (isset($ADMIN) && $ADMIN->isTyp(ADMIN)) {
				return true;
			}
			
			if (!isset($FORETAG) || (isset($object) && isset($FORETAG) && $FORETAG->getId() != $object->getId())) {
				$authorized = false;
			}
		} else 
		if (in_array($typ, array(
			SUPERADMIN,
			ADMIN,
			MODERATOR,
			EDITOR
		))) {
			
			if (!isset($ADMIN)) {
				$authorized = false;
			} else {
				
				if ($adminLevels[$ADMIN->getTyp() ] < $adminLevels[$typ]) {
					$authorized = false;
				}
			}
		} else 
		if ($typ == KOMMUN) {
			
			if (!isset($ADMIN)) {
				return false;
			} else {
				
				if ($ADMIN->getTyp() == "kommun" && strtolower($ADMIN->getANamn()) != strtolower($object->getNamn())) {
					$authorized = false;
				} else 
				if ($adminLevels[$ADMIN->getTyp() ] < $adminLevels[$typ]) {
					$authorized = false;
				}
			}
		} else {
			throw new SecurityException('Fel', "Ett fel uppstod när rättigheterna skulle kontrolleras");
		}
		return $authorized;
	}
	
	public static function demand($typ, $object = null)
	{
		// se authorized()
		// kastar undantag vid ogiltigt inlogg

		
		if (!self::authorized($typ, $object)) {
			throw new SecurityException("Denna sida kan ej visas", "Detta kan bero på att sidan är privat, att du inte loggat in eller att du blivit utloggad.");
		}
	}
	
	public static function getMedlemEncryptedString($medlem)
	{
		$steg = (($medlem->getStegTotal() * 13) % 317) + 134;
		$code = bin2hex($medlem->getANamn() . $medlem->getEpost() ^ $medlem->getENamn() . $medlem->getSkapad());
		
		if (strlen($code) < 10) $code = $steg;
		else $code = substr($code, strlen($code) - 10, 5) . substr($steg, 0, 5) . substr($code, strlen($code) - 5, 5);
		return $code;
	}
}

class SecurityException extends UserException
{
	
	public function __construct($title, $msg, $backlink = null, $backlinktitle = null)
	{
		header('HTTP/1.1 403 Forbidden');
		parent::__construct($title, $msg);
	}
}
?>
