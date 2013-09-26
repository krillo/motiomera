<?php
/*
  function handleError($errno, $errstr, $errfile, $errline, array $errcontext){
  // error was suppressed with the @-operator
  if (0 === error_reporting()) {
  return false;
  }
  throw new ErrorException($errstr, 0, $errno, $errfile, $errline);
  }
  set_error_handler('handleError');
 */
@session_start();   //krillo 20120808 added @ to suppress warning, thus wp has sometimes allready started a session  
if (MM_WP_INIT === false) {
  if (!isset($noutf8) && !isset($js_header)) {
    header("Content-Type: text/html; charset=utf-8");
  } else if (isset($js_header)) {
    header("Content-Type: text/javascript");
  }
}




//  echo session_status();
//  print_r( $_SESSION);


/*
if(MM_WP_INIT === true){
echo 'MM_WP_INIT är kört';


}else {
  echo 'INTE... MM_WP_INIT';
}
*/






//mm and wp settings
//MM_SERVER_ROOT_URL
//WP_SERVER_ROOT_URL



define('INIT', true);

if (!defined('ROOT')) {
  //define('ROOT', $_SERVER["DOCUMENT_ROOT"]);  //original mm code
  $root = __DIR__ . '/../';                     //special just so that it will be correct both in core mm and wp 
  define('ROOT', $root);  
  //echo 'ROOT is just defined: ' . ROOT . "<br>";
} else {
  //echo 'ROOT is defined: ' . ROOT;
}

require_once ROOT . 'php/constants.php';
require_once ROOT . 'php/settings.php';
require_once ROOT . 'php/settings_app.php';
require_once ROOT . 'php/classes/Medlem.php';
require_once SMARTY_DIR . 'Smarty.class.php';

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
if (!$USER) {
  unset($USER);
}

$IM = new sendMsg;
$ADMIN = Admin::getInloggad();
if (!$ADMIN) {
  unset($ADMIN);
}

$FORETAG = Foretag::getInloggad();
if (!$FORETAG){
  unset($FORETAG);
}

$adminLevels = array(
    "kommun" => 0,
    "redaktor" => 1,
    "moderator" => 2,
    "admin" => 3,
    "superadmin" => 4,
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

if (isset($USER))
  $adressbok = Adressbok::loadByMedlem($USER);

function __autoload($class_name) {

  if ($class_name != "LoggerPropertyConfigurator" && $class_name != "utf_normalizer" && $class_name != "PEAR_Error" && $class_name != "pear")
    require_once ROOT . "/php/classes/$class_name.php";
}
