<?php
session_start();
if(!isset($noutf8)&&!isset($js_header)){
	header("Content-Type: text/html; charset=utf-8");
} else if(isset($js_header)) {
	header("Content-Type: text/javascript");
}

define('INIT', true);

if (!defined('ROOT')) {
	define('ROOT', $_SERVER["DOCUMENT_ROOT"] . '/');
}

require_once ROOT . '/php/constants.php';
require_once ROOT . '/php/settings.php';
require_once ROOT . '/php/settings_app.php';
require_once SMARTY_DIR . 'Smarty.class.php';

// Errorhandling flyttad till efter DEBUG-konstanten. /Micke

if (MEMCACHE) {
	$Memcache = new Mem;
} else {
	$Memcache = false;
}
$db = new DB($dbhost, $dbuser, $dbpass, $dbdb);

$urlHandler = new UrlHandler();
$security = new Security();

$urlChecker = new UrlChecker;

$sajtDelarObj = new SajtDelar();

$USER = Medlem::getInloggad();
if(!$USER) unset($USER);


$IM = new sendMsg;

$ADMIN = Admin::getInloggad();


if(!$ADMIN) unset($ADMIN);

$FORETAG = Foretag::getInloggad();
if(!$FORETAG) unset($FORETAG);

$adminLevels = array(
	"kommun"=>		0,
	"redaktor"=>	1,
	"moderator"=>	2,
	"admin"=>		3,
	"superadmin"=>	4,
);

//////////////////////////////////////////////////////////////////
//Från settings.php
if (isset($ADMIN) && ($ADMIN->getDebug() == "true") or (DEBUG_OVERRIDE == true)) {
	define('DEBUG', true);
} else {
	define('DEBUG', false);
}

require_once ROOT . '/php/errorhandling.php';

//////////////////////////////////////////////////////////////////

// throw new Exception("testar");

if(isset($USER))
	$adressbok = Adressbok::loadByMedlem($USER);

function __autoload($class_name) {

	if($class_name != "LoggerPropertyConfigurator" && $class_name != "utf_normalizer" && $class_name != "PEAR_Error" && $class_name != "pear")
	    require_once ROOT."/php/classes/$class_name.php";
}
?>
