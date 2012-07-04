<?php
/* ajax call 
 * krillo 2012-07-24
 */
require_once($_SERVER["DOCUMENT_ROOT"] . "/php/init.php");
error_reporting(E_ALL);
ini_set('display_errors', '1');


$campaingMemberCode = mb_convert_case(urldecode($_REQUEST["compcampcode"]), MB_CASE_LOWER, "UTF-8");
$validCampaingMemberCodes = Foretag::getValidCampaignMemberCodes();
$ret = -1;
foreach ($validCampaingMemberCodes as $companyId => $value) {
  if($value['campaignMemberCode'] == $campaingMemberCode){
    $ret = $companyId;
  }   
}
echo $ret;

/*
//kampanjkod added by krillo 11-01-18
if($_POST["kontotyp"] == "kampanjkod"){
  $key = mb_convert_case(urldecode($_POST["kampanjkod"]), MB_CASE_LOWER, "UTF-8");
  $AS400Kampanjkod = Order::$kampanjkoder[$key];
	if($AS400Kampanjkod == "free"){
    $m->addPaidUntil(92);  //set account valid for three months
    $m->setLevelId(1);     //set level to pro
    $m->confirm($_POST["losenord"]);
    $m->sendActivationEmail();
    $m->commit();
    throw new UserException("V√§lkommen till MotioMera!", "Grattis, du √§r nu medlem i MotioMera! Men innan du kan k√∂ra ig√•ng m√•ste du aktivera ditt konto. <br />Det √§r enkelt, s√• h√§r g√∂r du:</p><p>Vi har nu skickat ett mail till adressen " . $m->getEpost() . ". N√§r du klickar p√• l√§nken som finns i mailet s√• aktiveras ditt Motiomera-konto. Proceduren √§r en s√§kerhets√•tg√§rd som vi anv√§nder f√∂r att ingen ska registrera ett konto i ditt namn. Om du inte ser meddelandet kan det av misstag ha blivit klassificerat som skr√§ppost. Se efter om du hittar e-postmeddelandet i din skr√§ppost-mapp.</p><p>Hoppas du f√•r en rolig tid hos MotioMera!<br />Med v√§nlig h√§lsning</p><p><b>MotioMera</b>");
  } elseif(key_exists($AS400Kampanjkod, Order::$campaignCodes)){  //special campaign is verified proceed with order
    $m->confirm($_POST["losenord"]);
    $o = new Order("medlem", $m, $AS400Kampanjkod);
    $o->setMedlem($m);
    $o->gorUppslag();  //this function sends header and breaks execution
    die();
  }
}
*/


?>