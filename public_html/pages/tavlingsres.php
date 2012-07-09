<?php

include $_SERVER["DOCUMENT_ROOT"] . '/php/init.php';
define("COMPETITION_DAYS", 35);
define("DELTAGARTOPPEN_MAX", 200);
define("LAGTOPPEN_MAX", 25);
define("FORETAGSTOPPEN_MAX", 400);
!empty($_REQUEST['id']) ? $mid = $_REQUEST['id'] : $mid = null;
!empty($_REQUEST['tid']) ? $tid = $_REQUEST['tid'] : $tid = null;
!empty($_REQUEST['fid']) ? $fid = $_REQUEST['fid'] : $fid = null;

if ($tid != null) {
  //$smarty = new MMSmarty(true, -1); // Caches the contest content indefinitely
  $smarty = new MMSmarty();    
  
  switch (true) {
    case isset($mid):
      $medlemArray = Tavling::getBasicMemberData($tid, $mid);
      //print_r($medlemArray); 
      $foretag_namn = $medlemArray[0]['foretag_namn'];
      $foretag_id = $medlemArray[0]['foretag_id'];
      $foretagArray = Tavling::getResultCompany($tid, COMPETITION_DAYS, - 1, $foretag_id);       //get the submitted companys data
      //print_r($foretagArray);     
      $foretagLagArray = Tavling::getResultTeam($tid, $foretag_id);   //get all the teams with median for the submitted company  Lagtoppen
      //print_r($foretagLagArray);   
      $allaLag = array();       //get all the teams with member data for the submitted company   Slutresultat inom lagen
      foreach ($foretagLagArray as $key => $lag) {
        $foretagMedlemArray = Tavling::getResultCompanyTeamMember($tid, $lag['lag_id']);
        array_push($allaLag, $foretagMedlemArray);
      }
      //print_r($allaLag);  
      //get all members for the submitted company
      //Deltagartoppen  no limit - all members (-1) 
      $allCompMembArray = Tavling::getResultAllMembers($tid, COMPETITION_DAYS, -1, $foretag_id);
      //print_r($allCompMembArray);  
      //get all members for the whole contest    Deltagartoppen
      //if the member ranks lower than DELTAGARTOPPEN_MAX then add her the the array
      $allMembArray = Tavling::getResultAllMembers($tid, COMPETITION_DAYS, DELTAGARTOPPEN_MAX);
      if ($medlemArray[0]['rank'] > DELTAGARTOPPEN_MAX) {
        array_push($allMembArray, $medlemArray[0]);
      }
      //print_r($allMembArray);  	    
      $lagArray = Tavling::getResultTeam($tid);   //get all the teams with median  Lagtoppen
      //print_r($foretagLagArray);   
      $allForetagArray = Tavling::getResultCompany($tid, COMPETITION_DAYS, FORETAGSTOPPEN_MAX);       //get all companys average   Foretagstoppen
      //print_r($allForetagArray);   
      $medlem = Medlem::loadById($mid);
      $foretag = Foretag::loadById($medlem->getForetag()->getId());

      $smarty->assign("medlem", $medlem);      
      break;
    case isset($fid):
      $foretagArray = Tavling::getResultCompany($tid, COMPETITION_DAYS, - 1, $fid);       //get the submitted companys data
      //print_r($foretagArray);
      $foretagLagArray = Tavling::getResultTeam($tid, $fid);   //get all the teams with median for the submitted company  Lagtoppen
      //print_r($foretagLagArray);         
      $allaLag = array();       //get all the teams with member data for the submitted company   Slutresultat inom lagen
      foreach ($foretagLagArray as $key => $lag) {
        $foretagMedlemArray = Tavling::getResultCompanyTeamMember($tid, $lag['lag_id']);
        array_push($allaLag, $foretagMedlemArray);
      }
      //print_r($allaLag);  
      //get all members for the submitted company
      //Deltagartoppen  no limit - all members (-1) 
      $allCompMembArray = Tavling::getResultAllMembers($tid, COMPETITION_DAYS, -1, $fid);
      //print_r($allCompMembArray);  
      //get all members for the whole contest    Deltagartoppen
      //if the member ranks lower than DELTAGARTOPPEN_MAX then add her the the array
      $allMembArray = Tavling::getResultAllMembers($tid, COMPETITION_DAYS, DELTAGARTOPPEN_MAX);
      //print_r($allMembArray);  	    
      $lagArray = Tavling::getResultTeam($tid);   //get all the teams with median  Lagtoppen
      //print_r($foretagLagArray);   
      $allForetagArray = Tavling::getResultCompany($tid, COMPETITION_DAYS, FORETAGSTOPPEN_MAX);       //get all companys average   Foretagstoppen
      //print_r($allForetagArray);   
      $foretag = Foretag::loadById($fid);
      break;
    default:
      throw new UserException('Något har gått fel', 'Prova igen senare eller rapportera felet till support@motiomera.se');
      break;
  }
}


$smarty->assign("foretagArray", $foretagArray);
$smarty->assign("allaLag", $allaLag);
$smarty->assign("foretagLagArray", $foretagLagArray);
$smarty->assign("allCompMembArray", $allCompMembArray);
$smarty->assign("allMembArray", $allMembArray);
$smarty->assign("lagArray", $lagArray);
$smarty->assign("allForetagArray", $allForetagArray);

//these two highlites the user on tavlingsresultat.php !?!?
$smarty->assign("tavlingsresultatsidan", true);
$smarty->assign("foretagCustomBild", CustomForetagsbild::getImgUrlIfValidFile($foretag_id));
$bildblock = FotoalbumBild::loadForetagsBildblock($foretag, $antal = 20);
$smarty->assign("bildblock", $bildblock);
$smarty->display('contest_results_template_from_db.tpl', $foretag_id);
?>