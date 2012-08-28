<?php
/* ajax call 
 * krillo 2012-07-24
 */
require_once($_SERVER["DOCUMENT_ROOT"] . "/php/init.php");
error_reporting(E_ALL);
ini_set('display_errors', '1');


$campaingMemberCode = mb_convert_case(urldecode($_REQUEST["compcampcode"]), MB_CASE_LOWER, "UTF-8");
$campaingMemberCode = trim($campaingMemberCode);  //trim whitespaces
$campaingMemberCode = trim($campaingMemberCode, '"');  //trim "
$validCampaingMemberCodes = Foretag::getValidCampaignMemberCodes();
$ret = -1;
foreach ($validCampaingMemberCodes as $companyId => $value) {
  if($value['campaignMemberCode'] == $campaingMemberCode){
    $ret = $companyId;
  }   
}
echo $ret;
?>