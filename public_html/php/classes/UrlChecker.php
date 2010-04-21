<?php
/**
* Class and Function List:
* Function list:
* - __construct()
* - currentPageURL()
* - currentFileName()
* - matchUrl()
* - setCurrentUrl()
* - setMarkedMenu()
* - setFileName()
* - setJsPackage()
* - setGoogleMapsApiKey()
* - getCurrentUrl()
* - getFileName()
* - getMarkedMenu()
* - getJsPackage()
* - getGoogleMapsApiKey()
* Classes list:
* - UrlChecker
*/

class UrlChecker
{
	
	private $currenturl; //url adressen

	
	private $filename; //Sidans titel

	
	private $markedmenu; //Vart man är på sidan för närvarande

	
	private $jsPackage = array();
	/** Javascripts includes array */
	
	private $googleApiKey;
	/** Google key */
	
	private $jsDefaultFiles = array(
		"/js/globals.js",
		"/js/ajax.js",
		"/js/functions.js",
		"/js/kalender.js",
		"/js/popup.js",
		"/js/tabbox.js",
		"/js/steg.js",
		"/js/validation.js",
		"/js/highslide/highslide.packed.js",
		"/js/AC_RunActiveContent.js",
		"/js/FusionMaps.js",
		"/js/mail_popup.js",
	);
	
	private $google_map_api_keys = array(
		/** some google maps keys */
		'localomera2' => 'ABQIAAAAuMDzylsCMpa8xNliwARAcRTFGTp4vNAWkFcwtEPbyN6x_DGpexRR1mXi4qrQ53r0Hx_9O58_G9HptQ',
		'trunkomera.se' => 'ABQIAAAAuMDzylsCMpa8xNliwARAcRT_kyFNl-ka9r_oTqT-uZF8Air7lxStmrTx98iSJewQi9Wz9HZj-dLTtQ',
		'localhost' => 'ABQIAAAAuMDzylsCMpa8xNliwARAcRT2yXp_ZAY8_ufC3CFXhHIE1NvwkxT83aaz0LC6xlEaulYzjESpV-HB4Q',
		'trunk-locamera.se' => 'ABQIAAAAoKGJwdpOav2ETgqnbjarahSkV6AqBAeUnTI_vPQf_1xiJaon3RS1Wvyc5yeWCzj4dcCTd4uucpkYcQ',
	);
	
	protected $urlMarkup = array(
		"KOMMUNER" => array(
			"editkommun.php" => array(
				"/js/jquery-1.2.6.min.js",
				"/js/jquery.motiomera.js"
			) ,
		) ,
		"FASTA UTMANINGAR" => array(
			"fastautmaningar_route.php" => array(
				"/js/jquery-1.2.6.min.js",
				"/js/jquery.motiomera.js"
			) ,
		) ,
		"MIN SIDA" => array(
			"minsida.php" => array(
				"/js/jquery-1.2.6.min.js",
				"/js/jquery.motiomera.js"
			) ,
			"rapport.php" => array() ,
			"valj_rutt.php" => array() ,
		) ,
		"MITT LAG" => array(
			"lag.php" => array() , //om det är mitt lag så ska den vara här annars ska den vara i mittforetag

			
		) ,
		"MITT FÖRETAG" => array(
			"foretag.php" => array() ,
		) ,
		"FÖRETAGSTÄVLING" => array(
			"foretagstavling.php" => array() ,
		) ,
		"MOTIOMERAMAIL" => array(
			"mail.php" => array() ,
		) ,
		"FOTOALBUM" => array(
			"fotoalbum.php" => array() ,
			"fotoalbumskapa.php" => array() ,
			"fotoalbumbildladdaupp.php" => array('/js/fotoalbum.js') ,
			"fotoalbumvisa.php" => array(
				'/js/fotoalbum.js',
				'/php/libs/uploadprogressmeter/server.php?client=main,request,httpclient,dispatcher,json,util',
				'/php/libs/uploadprogressmeter/server.php?stub=UploadProgressMeterStatus',
				'/js/uploadprogressmeter/uploadprogressmeter.js',
			) ,
		) ,
		"QUIZ" => array(
			"minaquizskapa.php" => array(
				"/js/jquery-1.2.6.min.js",
				"/js/jquery.motiomera.js"
			) ,
			"minaquizandra.php" => array(
				"/js/jquery-1.2.6.min.js",
				"/js/jquery.motiomera.js"
			) ,
			"minaquizvisa.php" => array(
				"/js/jquery-1.2.6.min.js",
				"/js/jquery.motiomera.js"
			) ,
			"minaquiz.php" => array(
				"/js/jquery-1.2.6.min.js",
				"/js/jquery.motiomera.js"
			) ,
		) ,
		"PROQUIZ" => array(
			"proquizskapa.php" => array(
				"/js/jquery-1.2.6.min.js",
				"/js/jquery.motiomera.js"
			) ,
			"proquizandra.php" => array(
				"/js/jquery-1.2.6.min.js",
				"/js/jquery.motiomera.js"
			) ,
			"proquizvisa.php" => array(
				"/js/jquery-1.2.6.min.js",
				"/js/jquery.motiomera.js"
			) ,
			"proquiz.php" => array(
				"/js/jquery-1.2.6.min.js",
				"/js/jquery.motiomera.js"
			) ,
		) ,
		"PÅMINNELSER" => array(
			"paminnelse_andrasql.php" => array(
				"/js/jquery-1.2.6.min.js",
				"/js/jquery.motiomera.js"
			) ,
			"paminnelse_skapasql.php" => array(
				"/js/jquery-1.2.6.min.js",
				"/js/jquery.motiomera.js"
			) ,
		) ,
		"MINA VÄNNER" => array(
			"adressbok.php" => array() ,
			"profil.php" => array(
				"/js/jquery-1.2.6.min.js",
				"/js/jquery.motiomera.js"
			) ,
		) ,
		"KLUBBAR" => array(
			"klubbar.php" => array() ,
			"klubb.php" => array() ,
			"editklubb.php" => array() ,
		) ,
		"INSTÄLLNINGAR" => array(
			"installningar.php" => array() ,
		) ,
		"OM MOTIOMERA" => array(
			"ommotiomera.php" => array() ,
		) ,
		"VANLIGA FRÅGOR" => array(
			"vanligafragor.php" => array() ,
		) ,
		"TÄVLINGAR" => array(
			"tavlingar.php" => array() ,
		) ,
		"KOMMUNJAKTEN" => array(
			"kommunjakten.php" => array() ,
			"quiz.php" => array(
				'/js/the_quiz.js'
			) ,
		) ,
		"FÖR FÖRETAG" => array(
			"for_foretag.php" => array() ,
		) ,
		"MEDLEMMAR" => array(
			"medlemmar.php" => array(
				"/js/jquery-1.2.6.min.js",
				"/js/jquery.tablesorter.js",
				"/js/jquery.motiomera.js"
			) ,
		) ,
		"LISTA FÖRETAG" => array(
			"listforetag.php" => array(
				"/js/jquery-1.2.6.min.js",
				"/js/jquery.tablesorter.js",
				"/js/jquery.motiomera.js"
			) ,
		) ,
	);
	
	private $extraUrls = array(
		"KOMMUNJAKTEN" => array(
			"/kommunjakten/",
			"/kommun/",
		) ,
	);
	
	public function __construct()
	{
		$this->setCurrentUrl($this->CurrentPageURL());
		$this->setFileName($this->CurrentFileName());
		
		if (isset($this->google_map_api_keys[$_SERVER['HTTP_HOST']])) {
			$key = $this->google_map_api_keys[$_SERVER['HTTP_HOST']];
		} else {
			$key = null;
		}
		$this->setGoogleMapsApiKey($key);
		$this->matchUrl();
	}

	////////////////////////////////////////////////////////////////////////////////////////////////
	//PUBLICS

	
	public function currentPageURL()
	{
		$pageURL = 'http';
		
		if (isset($_SERVER["HTTPS"])) {
			
			if ($_SERVER["HTTPS"] == "on") {
				$pageURL.= "s";
			}
		}
		$pageURL.= "://";
		
		if ($_SERVER["SERVER_PORT"] != "80") {
			$pageURL.= $_SERVER["SERVER_NAME"] . ":" . $_SERVER["SERVER_PORT"] . $_SERVER["REQUEST_URI"];
		} else {
			$pageURL.= $_SERVER["SERVER_NAME"] . $_SERVER["REQUEST_URI"];
		}
		return $pageURL;
	}
	
	public function currentFileName()
	{
		return substr($_SERVER["SCRIPT_NAME"], strrpos($_SERVER["SCRIPT_NAME"], "/") + 1);
	}

	///////////////////////////////////////////////////////////////////////////////////////////////
	//PRIVATES

	
	private function matchUrl()
	{
		$marked = false;
		foreach($this->urlMarkup as $key => $value) {
			
			if (is_array($value)) {
				foreach($value as $page => $jspackage) {
					
					if ($page == $this->filename) {

						//echo($this->filename);
						//print_r($jspackage);

						//$jspackage = $this->addExtra($jspackage);

						$this->setJsPackage($jspackage);
						$this->setMarkedMenu($key);
						$marked = true;
						return true;
					}
				}
			}
		}
		
		if ($marked == false) {
			foreach($this->extraUrls as $key => $value) {
				foreach($value as $string) {
					
					if (preg_match($string . "i", $this->currenturl)) {
						$this->setJsPackage($jspackage);
						$this->setMarkedMenu($key);
						$marked = true;
						return true;
					}
				}
			}
		}
		
		if ($marked == false) {
			$this->setJsPackage($jspackage);
			$this->setMarkedMenu("HEM");
		}
	}

	///////////////////////////////////////////////////////////////////////////////////////////////
	//SETTERS & GETTERS

	///////////////////////////////////////////////////////////////////////////////////////////////

	
	public function setCurrentUrl($url)
	{
		$this->currenturl = $url;
	}
	
	public function setMarkedMenu($name)
	{
		$this->markedmenu = $name;
	}
	
	public function setFileName($name)
	{
		$this->filename = $name;
	}
	
	public function setJsPackage($pack)
	{
		foreach($pack as $js) {
			$this->jsPackage[] = $js;
		}
		foreach($this->jsDefaultFiles as $js) {
			$this->jsPackage[] = $js;
		}
	}
	
	public function setGoogleMapsApiKey($key)
	{
		$this->googleMapsApiKey = $key;
	}
	
	public function getCurrentUrl()
	{
		return $this->currenturl;
	}
	
	public function getFileName()
	{
		return $this->filename;
	}
	
	public function getMarkedMenu()
	{
		return $this->markedmenu;
	}
	
	public function getJsPackage()
	{

		//print_r($this->jsPackage);
		return $this->jsPackage;
	}
	
	public function getGoogleMapsApiKey()
	{
		return $this->googlemapsApiKey;
	}
}
?>
