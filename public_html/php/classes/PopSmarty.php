<?php
/**
* Class and Function List:
* Function list:
* - __construct()
* - display()
* - display_bare()
* Classes list:
* - PopSmarty extends Smarty
*/

class PopSmarty extends Smarty
{
	
	public function __construct()
	{
		global $USER, $ADMIN, $FORETAG, $urlHandler, $security;
		$this->template_dir = ROOT . '/popup/templates';
		$this->compile_dir = ROOT . '/popup/templates_c';
		$this->config_dir = ROOT . '/php/libs/smarty/configs';
		$this->cache_dir = ROOT . '/php/libs/smarty/cache';
		$this->assign("pagename", "Motiomera.se");
		$this->assign("_GET", $_GET);
		$this->assign("_POST", $_POST);
		$this->assign("urlHandler", $urlHandler);
		$this->assign("security", $security);
		
		if ($USER) $this->assign("USER", $USER);
		
		if ($ADMIN) $this->assign("ADMIN", $ADMIN);
		
		if ($FORETAG) $this->assign("FORETAG", $FORETAG);
		$this->register_function('stegToKm', array(
			'Steg',
			'stegToKm'
		));
	}
	function display($resource_name, $cache_id = null, $compile_id = null)
	{
		$this->fetch($resource_name, $cache_id, $compile_id, true);
	}
	function display_bare($resource_name, $cache_id = null, $compile_id = null)
	{
		$this->fetch($resource_name, $cache_id, $compile_id, true);
	}
}
?>
