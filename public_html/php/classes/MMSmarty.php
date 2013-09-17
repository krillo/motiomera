<?php
/**
* Class and Function List:
* Function list:
* - __construct()
* - showSidebar()
* - noShowHeaderFooter()
* - display()
* - assign()
* - assign_by_ref()
* - display_bare()
* Classes list:
* - MMSmarty extends Smarty
*/

class MMSmarty extends Smarty
{
	var $showSidebar = false;
	var $showHeaderFooter = true;
	var $footer = '';
  
	private $contentCacheLifetime;
	const HEADERTPL = "header.tpl";
	const FOOTERTPL = "footer.tpl";
	const SIDEBARTPL = "sidebar.tpl";
  
	
	public function __construct($cache = false, $cacheLiftime = 3600)
	{
		global $USER, $ADMIN, $FORETAG, $SETTINGS, $urlHandler, $security, $adressbok, $urlChecker, $db, $sajtDelarObj, $footer;
		$this->template_dir = ROOT . '/templates';
		$this->compile_dir = ROOT . '/templates_c';
		$this->config_dir = ROOT . '/php/libs/smarty/configs';
		$this->cache_dir = ROOT . '/php/libs/smarty/cache';

		// Visar t.ex. trunkomera.se ist�llet f�r MotioMera, om s� �r fallet.
		
		if ($_SERVER['HTTP_HOST'] != 'motiomera.se'):
			$this->assign("pagename", ucfirst($_SERVER['HTTP_HOST']));
		else:
			$this->assign("pagename", "MotioMera");
		endif;
		
		if (defined('DEBUG_SMARTY') && DEBUG_SMARTY) {
			$this->assign('debugSmarty', true);
		}

		//$this->assign("pagename", "MotioMera");
		$this->assign("microtime", Misc::get_milliseconds(true));
		$this->assign("_GET", $_GET);
		$this->assign("_POST", $_POST);
		$this->assign("_SERVER", $_SERVER);
		$this->assign("urlHandler", $urlHandler);
		$this->assign("sajtDelarObj", $sajtDelarObj);
		$this->assign("urlChecker", $urlChecker);
		$this->assign("security", $security);
		$this->assign("GOOGLEMAPS_APIKEY", GOOGLEMAPS_APIKEY);
		$this->assign("DEBUG", DEBUG);
		$this->assign("mm_url", $SETTINGS['url']);
		$this->contentCacheLifetime = $cacheLiftime;
		$this->compile_check = true;

    //the rss flow from mabra.com
    $file = ROOT . "/files/rsscache/motiofeed.txt";
    $fh = fopen($file, "r") or die("cant open file");
    $smotiofeed = file_get_contents($file);
    fclose($fh);
    $rss = unserialize($smotiofeed);
    $this->assign("rss", $rss);

		
		if ($cache) {
			$this->caching = 2;
		} else {
			$this->caching = false;
		}
		$this->assign("BROWSER", Medlem::getCurrentBrowserVersion(true));
		$helpers = Help::listByPage($_SERVER['PHP_SELF']);
		$this->assign("helpers", $helpers);
		$this->assign('currentPage', Misc::getCurrentPage());
		
		if ($USER) {
			$this->assign("USER", $USER);
			$this->assign("adressbok", $adressbok);
		}
		
		if ($ADMIN) {
			$this->assign("ADMIN", $ADMIN);
			$this->assign("inAdmin", true);
		}
		
		if ($FORETAG) $this->assign("FORETAG", $FORETAG);
		$this->register_function('stegToKm', array(
			'Steg',
			'stegToKm'
		));
    
    /*
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_URL, 'http://mm.dev/api-footer/');
    $footer = curl_exec($ch);
    curl_close($ch);
     * 
     */

          
    //$this->footer = file_get_contents('http://mm.dev/api-footer/');

	}


  public function getMMWPFooter(){
    return $this->footer;
  } 
  
	function showSidebar()
	{
		$this->showSidebar = true;
	}
	function noShowHeaderFooter()
	{
		$this->showHeaderFooter = false;
	}
	function display($resource_name, $cache_id = null, $compile_id = null)
	{
		global $db;
		
		if ($this->showHeaderFooter) {
			$this->cache_lifetime = 0;
			$this->fetch(self::HEADERTPL, null, null, true);
		}
		$this->cache_lifetime = $this->contentCacheLifetime;
		
		if ($this->showSidebar) {
			include (ROOT . "/pages/sidebar.php");
			echo '<div id="mmColumnMiddle">';
			$this->fetch($resource_name, $cache_id, $compile_id, true);
			echo '</div>';
			$this->fetch(self::SIDEBARTPL, null, null, true);
		} else {
			
			if ($this->showHeaderFooter) {
				echo '<div id="mmColumnMiddleWide">';
			}
			$this->fetch($resource_name, $cache_id, $compile_id, true);
			
			if ($this->showHeaderFooter) {
				echo '</div>';
			}
		}
		
		if ($this->showHeaderFooter) {
			$this->assign("querycount", $db->getQuerycount());
			$this->assign("footer", $this->footer);
			$this->cache_lifetime = 0;
			$this->fetch(self::FOOTERTPL, null, null, true);
		}
	}
	/**
	 * assigns values to template variables
	 *
	 * @param array|string $tpl_var the template variable name(s)
	 * @param mixed $value the value to assign
	 */
	function assign($tpl_var, $value = null)
	{
		
		if (is_array($tpl_var)) {
			foreach($tpl_var as $key => $val) {
				
				if ($key != '') {
					$this->_tpl_vars[$key] = $val;
				}
			}
		} else {
			
			if ($tpl_var != '') {
				$this->_tpl_vars[$tpl_var] = $value;
			}
		}
		unset($value);
	}
	/**
	 * assigns values to template variables by reference
	 *
	 * @param string $tpl_var the template variable name
	 * @param mixed $value the referenced value to assign
	 */
	function assign_by_ref($tpl_var, &$value)
	{
		
		if ($tpl_var != '') {
			$this->_tpl_vars[$tpl_var] = & $value;
		}
		unset($value);
	}
	function display_bare($resource_name, $cache_id = null, $compile_id = null)
	{
		$this->fetch($resource_name, $cache_id, $compile_id, true);
	}
}
?>
