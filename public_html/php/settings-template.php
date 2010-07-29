<?php
/**
* General settings 
*/
$SETTINGS["email"] = "noreply@motiomera.se"; // Adress som ska stå som avsändare i mail till medlemmar
$SETTINGS["reply_to"] = "per.olsson@aller.se"; // All bounces will land here
$SETTINGS["kontakt"] = "kundservice@aller.se"; // Adressen dit formulärsmail skickas
$SETTINGS["debug_mail"] = array("kristian.erendi@aller.se"); //Adressen dit debug mail skickas till om den är aktiv ex: array("mail1","mail2");
$SETTINGS["new_company_order_mail"] = array("kristian.erendi@aller.se", "krillo@gmail.com"); //Adressen dit debug mail skickas till om den är aktiv ex: array("mail1","mail2");

$SETTINGS["rapport_mail"] = "kristian.erendi@aller.se";
$SETTINGS["url"] = "http://krillomera.se/";
#$SETTINGS["UPPSLAG_URL"] = "http://mabra.allers.dropit.se/Sites/Pren/Templates/Paymentgw____56389.aspx";
#$SETTINGS["KUNDNUMMER_URL"] = "http://mabra.allers.dropit.se/Templates/UserService____51336.aspx?key=jhf9h4opqmcjhu93dn&Get=CustomerFromOrder&Orderid=";
$SETTINGS["UPPSLAG_URL"] = "http://www.allersforlag.se/Sites/Pren/Templates/Paymentgw____56389.aspx";
$SETTINGS["KUNDNUMMER_URL"] = "http://www.allersforlag.se/Templates/UserService____60489.aspx?key=h3zp0x4qgs4k&Get=CustomerFromOrder&Orderid=";



//MySQL
$dbdb =	'motiomera';
$dbhost = 'localhost';
$dbuser = 'motiomera';
$dbpass = 'motiomera';

// Truncate feed items
define('TRUNCATE_OLDER_THAN', 4);

//Debug
define('DEBUG_OVERRIDE', true); // true - turns on debug
define('DEBUG_MAIL', false); //Sätter på debug via mail
define('DEBUG_SMARTY', false); //Sätter på debug i smarty
define("DEBUG_IM", false); //sätter på debug i IM
define("NO_INTERNET", true);
define("GOOGLEMAPS_OVERRIDE_NO_INTERNET", false);

//Memcache
define('MEMCACHE', true); //Sätter på memcache
define('MEMCACHE_EXPIRE', (60*5)); //i sekunder innan man tömmer objectet ur DB. 0 = oändlig tid framöver ;) (60*60*24*30) är max man kan ange, dvs 30 dygn
define('MEMCACHE_SERVER', 'localhost');

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
    'krillomera.se' => 'ABQIAAAAKzMyNmzHpI2ys7Y4yLCUKxTy4MN0sW6gGuXvGNy8gkPzTfAzNBTX7HXHVNDeZ9bpiBeeIiqjxGc89A',
	'testomera.se' => 'ABQIAAAANKJiM3KX0RgwW7CohhxtexTMcDzwyi0Hm9IHdH_sNWT7RCXriBRqeHrTLsDwDJNVmStXOp1zogUnyA',
	'localmotiomera' => 'ABQIAAAAthwpzPchZb-OjNhtFyAU3BSZZqeaU-3s5X6tGQynxqy6msFf4hTGO7Yrlwx8zRlO6JkR3LLzc32P4w',
	'localomera2' => 'ABQIAAAAuMDzylsCMpa8xNliwARAcRTFGTp4vNAWkFcwtEPbyN6x_DGpexRR1mXi4qrQ53r0Hx_9O58_G9HptQ',
	'trunkomera.se' => 'ABQIAAAAuMDzylsCMpa8xNliwARAcRT_kyFNl-ka9r_oTqT-uZF8Air7lxStmrTx98iSJewQi9Wz9HZj-dLTtQ',
	'localhost' => 'ABQIAAAAuMDzylsCMpa8xNliwARAcRT2yXp_ZAY8_ufC3CFXhHIE1NvwkxT83aaz0LC6xlEaulYzjESpV-HB4Q',
	'trunk-locamera.se' => 'ABQIAAAAoKGJwdpOav2ETgqnbjarahSkV6AqBAeUnTI_vPQf_1xiJaon3RS1Wvyc5yeWCzj4dcCTd4uucpkYcQ',
	'motiomera.local' => 'ABQIAAAANKJiM3KX0RgwW7CohhxtexS1LzYKLbLQjIR8BUgnzcVXnHMhFBRhxYys6eSBfGDz1G7wWynCXYGP5w',
	'trunk.motiomera' => 'ABQIAAAAoKGJwdpOav2ETgqnbjarahRRMBEu5CALF7xZh5XrDQKcx7HCdhRqsCjsYrui-qJk3N6UAiLA_9fXsg',
	'motiomera.se' => 'ABQIAAAAoKGJwdpOav2ETgqnbjarahQO7RuXVt7MF_rSjXZeZTDFiOd82xQjxvpURu3QurFzD-6V3p4mcOMujg',
	'www.motiomera.se' => 'ABQIAAAAoKGJwdpOav2ETgqnbjarahSYvq-FPqK4lLNaOXGWuDfDL82xGRTsfWTH7cJboy2WmrVTUP0RY_wNvQ',
    'krillomera:8000' => 'ABQIAAAAKzMyNmzHpI2ys7Y4yLCUKxQxZXq48oV33njLpoh52aXFtCGfsBTAwsq_8zddECoVedO_T_KNMkwD9w',
    'order:8000' => 'ABQIAAAAKzMyNmzHpI2ys7Y4yLCUKxSGGwTHf-R8xkabalYVzie--IfwcxRoAXtWpsYG_1OuOfJJu8ycbC9Bmw',
    'order' => 'ABQIAAAAKzMyNmzHpI2ys7Y4yLCUKxQmbD_Pu_h58hOAVOSeD2sevjvPcBRG4JHAhDqUJZ56sA3eSDNFtvvCgA',
);

if (isset($google_map_api_keys[$_SERVER['HTTP_HOST']])) {
	define('GOOGLEMAPS_APIKEY', $google_map_api_keys[$_SERVER['HTTP_HOST']]);
} else {
	define('GOOGLEMAPS_APIKEY', '');
}

// FTP Data for Företagsfiler
define('FTP_HOST', "sas.jlmdata.se");
define('FTP_USER', "postpac");
define('FTP_PASS', "PALM");
define('FORETAGSFIL_REMOTE_PATH', "/allers/Motiomera_test");
define('FORETAGSFIL_LOCAL_PATH', "/var/www/krillomera/postpac/order_files");



//MSN settings
define('ERR_AUTHENTICATION_FAILED', 911);
define('ERR_SERVER_UNAVAILABLE', 600);
define('ERR_USER_OFFLINE', 217);
define('ERR_TOO_MANY_SESSIONS', 800);
define('OK', 1);
define('DEBUG_IM_MAIL', 'debug@thefarm.se');
define('DEBUG_IM_PASS', 'bonde123');
$SETTINGS['im_recip'] = array(
	'kristian.erendi@aller.se',
);
?>
