<?php
/**
 * Class and Function List:
 * Function list:
 * - __construct()
 * - __getEmptyObject()
 * - listAll()
 * - listByReceiver()
 * - loadById()
 * - listMailInbox()
 * - getToName()
 * - listMailOutbox()
 * - removeAllMemberMail()
 * - removeMail()
 * - removeMailFromFolder()
 * - getMedlemOlastaMailCount()
 * - getFromName()
 * - getToInFolder()
 * - setToInFolder()
 * - getFromInFolder()
 * - setFromInFolder()
 * - getToDeleted()
 * - setToDeleted()
 * - getFromDeleted()
 * - setFromDeleted()
 * - getIsAnswered()
 * - setIsAnswered()
 * - getIsRead()
 * - setIsRead()
 * - getDateSent()
 * - setDateSent()
 * - getSendTo()
 * - setSendTo()
 * - getSentFrom()
 * - setSentFrom()
 * - getMsg()
 * - setMsg()
 * - setAllowLinks()
 * - getAllowLinks()
 * - getSubject()
 * - setSubject()
 * - getId()
 * - getToId()
 * Classes list:
 * - MotiomeraMail extends Mobject
 * - MotiomeraMailException extends Exception
 */

class MotiomeraMail extends Mobject
{
	protected $id; // int
	protected $subject; // string
	protected $msg; // string
	protected $from_id; // int
	protected $to_id; // int
	protected $date_sent; // string
	protected $is_read; // int
	protected $is_answered; // int
	protected $from_deleted; // int
	protected $to_deleted; // int
	protected $from_in_folder; // int
	protected $to_in_folder; // int
	protected $allow_links; // string
	protected $fields = array(
		"id" => "int",
		"subject" => "str",
		"msg" => "str",
		"from_id" => "int",
		"to_id" => "int",
		"date_sent" => "date",
		"is_read" => "int",
		"is_answered" => "int",
		"from_deleted" => "int",
		"to_deleted" => "int",
		"from_in_folder" => "int",
		"to_in_folder" => "int",
		"allow_links" => "str",
	);
	
	const MOTIOMERAMAIL_TABLE = "mm_medlemprofildataval";
	
	public function __construct($subject, $msg, $from_id, $to_id, $date_sent, $is_read, $is_answered, $allow_links = 0, $dummy_object = false)
	{
		
		if (!$dummy_object) {
			$this->setSubject($subject);
			$this->setMsg($msg);
			$this->setSentFrom($from_id);
			$this->setSendTo($to_id);
			$this->setDateSent($date_sent);
			$this->setIsRead($is_read);
			$this->setIsAnswered($is_answered);
			$this->setFromDeleted(0);
			$this->setToDeleted(0);
			$this->setFromInFolder(0);
			$this->setToInFolder(0);
			$this->setAllowLinks($allow_links);
			$this->commit();
			$medlem = Medlem::loadById($to_id);
			$medlem->setOlastaMail($medlem->GetOlastamail() + 1);
			$medlem->commit();
		}
	}
	
	public static function __getEmptyObject()
	{
		$class = get_class();
		
		return new $class(null, null, null, null, null, null, null, null, true);
	}

	// STATIC FUNCTION ////////////////////////////////////////
	
	public static function listAll()
	{
		
		return parent::lister(get_class());
	}
	
	public static function listByReceiver($id)
	{
		
		return parent::lister(get_class() , "to_id", $id);
	}
	
	public static function loadById($id)
	{
		
		return parent::loadById($id, get_class());
	}
	
	public static function listMailInbox($to_id, $folder_id, $order_by = 'MM.date_sent DESC')
	{
		Security::demand(USER);
		global $db;
		$sql = "SELECT MM.id, MM.subject, MM.date_sent, MM.is_read, MM.is_answered, M.aNamn FROM mm_motiomeramail AS MM, mm_medlem AS M WHERE MM.to_id = " . $to_id . " AND M.id = MM.from_id AND MM.to_in_folder = " . $folder_id . " AND MM.to_deleted = 0 ORDER BY " . $order_by . ";";
		$result = $db->query($sql);
		$inbox_mails = array();
		while ($row = mysql_fetch_array($result, MYSQL_ASSOC)) {
			$inbox_mails[] = $row;
		}
		
		return $inbox_mails;
	}
	
	public function getToName($id)
	{
		global $db;
		$sql = "SELECT M.aNamn FROM mm_motiomeramail AS MM, mm_medlem AS M WHERE MM.id = " . $id . " AND MM.to_id = M.id";
		
		return $db->value($sql);
	}
	
	public static function listMailOutbox($from_id, $order_by = 'MM.date_sent DESC')
	{
		Security::demand(USER);
		global $db;
		$sql = "SELECT MM.id, MM.to_id, MM.subject, MM.date_sent, MM.is_read, MM.is_answered, M.aNamn FROM mm_motiomeramail AS MM, mm_medlem AS M WHERE MM.from_id = " . $from_id . " AND M.id = MM.from_id AND MM.from_deleted = 0 ORDER BY " . $order_by . ";";
		$result = $db->query($sql);
		$inbox_mails = array();
		$count = 0;
		while ($row = mysql_fetch_array($result, MYSQL_ASSOC)) {
			$inbox_mails[$count] = $row;
			$medlem = Medlem::loadById($row['to_id']);
			$inbox_mails[$count]['to_name'] = $medlem->getANamn();
			$count++;
		}
		
		return $inbox_mails;
	}
	
	public static function removeAllMemberMail(Medlem $medlem)
	{
		
		if ((Security::authorized(ADMIN)) or (Security::authorized(USER, $medlem))) {
			global $db;
			$fromMail = parent::lister(get_class() , "from_id", $medlem->getId());
			foreach($fromMail as $mail) {
				$mail->delete();
			}
			$toMail = parent::lister(get_class() , "to_id", $medlem->getId());
			foreach($toMail as $mail) {
				$mail->delete();
			}
		}
	}
	
	public static function removeMail($mail_id, $remover_id, $remover)
	{
		global $db;
		Security::demand(USER);
		$t = MotiomeraMail::loadById($mail_id);
		
		if ($remover == "to_deleted") {
			$t->setIsRead(1);
			$t->setToDeleted(1);
		} else {
			$t->setFromDeleted(1);
		}
		
		if ($t->getToDeleted() == 1 && $t->getFromDeleted() == 1) {
			$t->delete();
		}
		$t->commit();
	}
	
	public static function removeMailFromFolder($folder_id, $to_id)
	{
		global $db;
		Security::demand(USER);
		$sql = "UPDATE mm_motiomeramail SET to_deleted = 1 WHERE to_id = " . $to_id . " AND to_in_folder = " . $folder_id;
		$db->nonquery($sql);
		$sql = "SELECT to_deleted, from_deleted FROM mm_motiomeramail WHERE to_id = " . $to_id . " AND to_in_folder = " . $folder_id;
		$result = $db->query($sql);
		while ($row = mysql_fetch_array($result)) {
			
			if ($row['from_deleted'] == '1' && $row['to_deleted'] == '1') {
				$sql = "DELETE FROM mm_motiomeramail WHERE id = " . $row['id'];
				$db->nonquery($sql);
			}
		}
		$sql = "DELETE FROM mm_motiomeramail_folders WHERE id = " . $folder_id;
		$db->nonquery($sql);
	}

	// PUBLIC FUNCTIONS ///////////////////////////////////////
	
	public static function getMedlemOlastaMailCount($medlem)
	{
		
		return count(parent::multiLister(get_class() , array(
			"to_id" => $medlem->getId() ,
			"is_read" => 0
		)));
	}

	// SETTERS & GETTERS //////////////////////////////////////
	
	public function getFromName($id)
	{
		global $db;
		$sql = "SELECT M.aNamn FROM mm_motiomeramail AS MM, mm_medlem AS M WHERE MM.id = " . $id . " AND MM.from_id = M.id";
		
		return $db->value($sql);
	}
	
	public function getToInFolder()
	{
		
		return $this->to_in_folder;
	}
	
	public function setToInFolder($to_in_folder)
	{
		$this->to_in_folder = $to_in_folder;
		$this->commit();
	}
	
	public function getFromInFolder()
	{
		
		return $this->from_in_folder;
	}
	
	public function setFromInFolder($from_in_folder)
	{
		$this->from_in_folder = $from_in_folder;
	}
	
	public function getToDeleted()
	{
		
		return $this->to_deleted;
	}
	
	public function setToDeleted($to_deleted)
	{
		$this->to_deleted = $to_deleted;
	}
	
	public function getFromDeleted()
	{
		
		return $this->from_deleted;
	}
	
	public function setFromDeleted($from_deleted)
	{
		$this->from_deleted = $from_deleted;
	}
	
	public function getIsAnswered()
	{
		
		return $this->is_answered;
	}
	
	public function setIsAnswered($is_answered)
	{
		$this->is_answered = $is_answered;
	}
	
	public function getIsRead()
	{
		
		return $this->is_read;
	}
	
	public function setIsRead($is_read)
	{
		$this->is_read = $is_read;

		//$this->commit();
		
	}
	
	public function getDateSent()
	{
		
		return $this->date_sent;
	}
	
	public function setDateSent($date_sent)
	{
		$this->date_sent = $date_sent;
	}
	
	public function getSendTo()
	{
		
		return $this->to_id;
	}
	
	public function setSendTo($to_id)
	{
		$this->to_id = $to_id;
	}
	
	public function getSentFrom()
	{
		
		return $this->from_id;
	}
	
	public function setSentFrom($from_id)
	{
		$this->from_id = $from_id;
	}
	
	public function getMsg()
	{
		$r = strip_tags($this->msg, '<p><a>');
		
		return nl2br($r);
	}
	
	public function setMsg($msg)
	{
		$this->msg = $msg;
	}
	
	public function setAllowLinks($arg)
	{
		
		if ($arg != 0) $this->allow_links = 'true';
		else $this->allow_links = 'false';
	}
	
	public function getAllowLinks()
	{
		
		return $this->allow_links;
	}
	
	public function getSubject()
	{
		
		return ($this->subject);
	}
	
	public function setSubject($subject)
	{
		$this->subject = $subject;
	}
	
	public function getId()
	{
		
		return $this->id;
	}
	
	public function getToId()
	{
		
		return $this->to_id;
	}
}

class MotiomeraMailException extends Exception
{
}
?>
