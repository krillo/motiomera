<?php
/**
* Class and Function List:
* Function list:
* - __construct()
* - display()
* - display_bare()
* Classes list:
* - AdminSmarty extends Smarty
*/

class AdminSmarty extends Smarty
{
	const HEADERTPL = "../../templates/header.tpl";
	const FOOTERTPL = "../../templates/footer.tpl";
	
	public function __construct()
	{
		global $USER, $ADMIN, $FORETAG, $urlHandler, $security, $urlChecker, $sajtDelarObj;
		
		if (isset($ADMIN) && $ADMIN->getTyp() == "kommun") {
			global $kommun;
			Security::demand(KOMMUN, $kommun);
		} else {
			Security::demand(EDITOR);
		}
		$this->template_dir = ROOT . '/admin/templates';
		$this->compile_dir = ROOT . '/admin/templates_c/';
		$this->config_dir = ROOT . '/php/libs/smarty/configs';
		$this->cache_dir = ROOT . '/php/libs/smarty/cache';
		$this->assign("pagename", "Motiomera.se");
		$this->assign("_GET", $_GET);
		$this->assign("_POST", $_POST);
		$this->assign("urlHandler", $urlHandler);
		$this->assign("sajtDelarObj", $sajtDelarObj);
		$this->assign("security", $security);
		$this->assign("inAdmin", true);
		$this->assign("GOOGLEMAPS_APIKEY", GOOGLEMAPS_APIKEY);
		$this->assign("BROWSER", Medlem::getCurrentBrowserVersion(true));
		$this->assign("urlChecker", $urlChecker);
		
		if ($ADMIN) $this->assign("ADMIN", $ADMIN);
		
		if ($USER) $this->assign("USER", $USER);
		
		if ($FORETAG) $this->assign("FORETAG", $FORETAG);
	}
	function display($resource_name, $cache_id = null, $compile_id = null)
	{
		$this->fetch(self::HEADERTPL, null, null, true);
		echo '<div id="mmColumnMiddle">';
		$this->fetch($resource_name, $cache_id, $compile_id, true);
		echo '</div>';
		$this->fetch(self::FOOTERTPL, null, null, true);
	}
	function display_bare($resource_name, $cache_id = null, $compile_id = null)
	{
		$this->fetch($resource_name, $cache_id, $compile_id, true);
	}
}
?>
