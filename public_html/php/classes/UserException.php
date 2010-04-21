<?php
/**
* Class and Function List:
* Function list:
* - __construct()
* - setTitle()
* - setBacklink()
* - setBacklinkTitle()
* - getTitle()
* - getBacklink()
* - getBacklinkTitle()
* Classes list:
* - UserException extends Exception
*/

class UserException extends Exception
{
	
	protected $title;
	
	protected $backlink;
	
	public function __construct($title, $msg, $backlink = null, $backlinktitle = null)
	{
		parent::__construct($msg, null);
		global $SETTINGS;
		$this->setTitle($title);
		$this->setBacklink($backlink);
		$smarty = new MMSmarty();
		$smarty->assign("exception", $this);
		
		if (!empty($backlinktitle)) {
			$smarty->assign("backlinktitle", $backlinktitle);
		} else {
			$smarty->assign("backlinktitle", "Gå tillbaka");
		}
		$smarty->display('userexception.tpl');
		exit;
	}
	
	public function setTitle($title)
	{
		$this->title = $title;
	}
	
	public function setBacklink($backlink)
	{
		$this->backlink = $backlink;
	}
	
	public function setBacklinkTitle($backlinktitle)
	{
		$this->backlinkTitle = $backlinktitle;
	}
	
	public function getTitle()
	{
		return $this->title;
	}
	
	public function getBacklink()
	{
		return $this->backlink;
	}
	
	public function getBacklinkTitle()
	{
		return $this->backlinkTitle;
	}
}
?>
