<?php
/**
* General settings 
*/
$SETTINGS["email"] = "noreply@motiomera.se"; // Adress som ska stå som avsändare i mail till medlemmar
$SETTINGS["kontakt"] = "support@motiomera.se"; // Adressen dit formulärsmail skickas
$SETTINGS["debug_mail"] = array("kristian@motiomera.se"); //Adressen dit debug mail skickas till om den är aktiv ex: array("mail1","mail2");
$SETTINGS["rapport_mail"] = "support@motiomera.se";
$SETTINGS["reply_to"] = "noreply motiomera <noreply@motiomera.se>"; // All bounces will land here
$SETTINGS["url"] = "http://CONF_MOTIOMERA_URL";
$SETTINGS["UPPSLAG_URL"] = $SETTINGS["url"] . "/pages/404.php?arg=UPPSLAG_URL";
$SETTINGS["KUNDNUMMER_URL"] = $SETTINGS["url"] . "/pages/404.php?arg=KUNDNUMMER_URL";
$SETTINGS["new_company_order_mail"] = array("kristian@motiomera.se");  //är nog inte aktiv 
$SETTINGS["paysonReturnUrl"] = $SETTINGS["url"] . "/pages/kvitto.php";
$SETTINGS["paysonCancelUrl"] = $SETTINGS["url"] . "/pages/paysonavbryt.php"; 
$SETTINGS["paysonIpnUrl"] = $SETTINGS["url"] . "/pages/paysonipn.php";
$SETTINGS["paysonReceiverEmail"] = "kassa@motiomera.se";
$SETTINGS["paysonAgentId"] = "CONF_PAYSON_AGENTID";
$SETTINGS["paysonMD5"] = "CONF_PAYSON_MD5";

//deploy and file owners
define('CAPISTRANO_DEPLOY', CONF_CAPISTRANO_DEPLOY);     //set to true if the code is deployed via capistrano
define('FILE_OWNER', 'CONF_FILE_OWNER');                 //which user to chown to, only applied when CAPISTRANO_DEPLOY = true

//MySQL
$dbdb =	'CONF_SQL_DB';
$dbhost = 'CONF_SQL_HOST';
$dbuser = 'CONF_SQL_USER';
$dbpass = 'CONF_SQL_PASS';

// Truncate feed items
define('TRUNCATE_OLDER_THAN', 4);

//Debug
define('DEBUG_OVERRIDE', CONF_DEBUG_OVERRIDE); // true - turns on debug
define('DEBUG_MAIL', CONF_DEBUG_MAIL); //Sätter på debug via mail
define('DEBUG_SMARTY', CONF_DEBUG_SMARTY); //Sätter på debug i smarty
define("DEBUG_IM", CONF_DEBUG_IM); //sätter på debug i IM
define("NO_INTERNET", CONF_NO_INTERNET);
define("GOOGLEMAPS_OVERRIDE_NO_INTERNET", CONF_GOOGLEMAPS_OVERRIDE_NO_INTERNET);

//Memcache
define('MEMCACHE', CONF_USE_MEMCACHE); //Sätter på memcache
define('MEMCACHE_EXPIRE', (60*5)); //i sekunder innan man tömmer objectet ur DB. 0 = oändlig tid framöver ;) (60*60*24*30) är max man kan ange, dvs 30 dygn
define('MEMCACHE_SERVER', 'CONF_MEMCACHE_SERVER');

//Directories
define('AVATAR_PATH', ROOT . "/files/avatarer");
define('LAG_BILD_PATH', ROOT . "/files/lagnamn");
define('FORETAGS_BILD_PATH', ROOT . "/files/foretagsbilder");
define('LAGNAMN_PATH', ROOT . "/files/lagnamn");
define('VISNINGSBILD_PATH', ROOT . "/files/visningsbilder");
define('CUSTOM_VISNINGSBILD_PATH', ROOT . "/files/customvisningsbilder");
define('KOMMUN_IMAGES_PATH', ROOT . "/files/kommunbilder");
define('FOTOALBUM_PATH', ROOT . "/files/bilder");
define('TAB_BOX_TABROOT', ROOT . "/tabroot");
define('EMAIL_SEND_LOG_FILE', ROOT . "/../log/email.log");
define('LOG_DIR', ROOT . "/../log");
define('PDF_TEMPLATE_DIR', ROOT . "/pdfomera/");


// add new keys from http://code.google.com/apis/maps/signup.html
$google_map_api_keys = array(
	'motiomera.dev' => 'ABQIAAAANKJiM3KX0RgwW7CohhxtexS1LzYKLbLQjIR8BUgnzcVXnHMhFBRhxYys6eSBfGDz1G7wWynCXYGP5w',
	'motiomera.se' => 'ABQIAAAAoKGJwdpOav2ETgqnbjarahQO7RuXVt7MF_rSjXZeZTDFiOd82xQjxvpURu3QurFzD-6V3p4mcOMujg',
	'www.motiomera.se' => 'ABQIAAAAoKGJwdpOav2ETgqnbjarahSYvq-FPqK4lLNaOXGWuDfDL82xGRTsfWTH7cJboy2WmrVTUP0RY_wNvQ',
);

if (isset($google_map_api_keys[$_SERVER['HTTP_HOST']])) {
	define('GOOGLEMAPS_APIKEY', $google_map_api_keys[$_SERVER['HTTP_HOST']]);
} else {
	define('GOOGLEMAPS_APIKEY', '');
}

// FTP Data for Företagsfiler
define('FTP_HOST', 'CONF_FTP_HOST');
define('FTP_USER', 'CONF_FTP_USER');
define('FTP_PASS', 'CONF_FTP_PASS');
define('FORETAGSFIL_REMOTE_PATH', "CONF_FTP_REMOTE_PATH");
define('FORETAGSFIL_LOCAL_PATH', "CONF_FTP_LOCAL_PATH");


// Fakturafiler
define('FORETAGSFAKTURA_LOCAL_PATH', "CONF_FAKTURA_LOCAL_PATH");


// member order files
define('MEDLEMSFIL_LOCAL_PATH', "CONF_MEMBER_LOCAL_PATH");



//MSN settings
define('ERR_AUTHENTICATION_FAILED', 911);
define('ERR_SERVER_UNAVAILABLE', 600);
define('ERR_USER_OFFLINE', 217);
define('ERR_TOO_MANY_SESSIONS', 800);
define('OK', 1);
define('DEBUG_IM_MAIL', 'debug@thefarm.se');
define('DEBUG_IM_PASS', 'bonde123');
$SETTINGS['im_recip'] = array(
	'krillo@gmail.com',
);
?>
