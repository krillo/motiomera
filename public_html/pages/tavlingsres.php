<?php
include $_SERVER["DOCUMENT_ROOT"]  . '/php/init.php';

define("COMPETITION_DAYS", 35);
define("DELTAGARTOPPEN_MAX", 100);
define("LAGTOPPEN_MAX", 25);
define("FORETAGSTOPPEN_MAX", 50);



if(isset($_GET["id"]) && $_GET["id"] == floor($_GET["id"]) && isset($_GET["tid"]) && $_GET["tid"] == floor($_GET["tid"]))
{
	$mid = $_GET["id"];
  $tid = $_GET["tid"];	
	
  //get the data for the userid submitted 
	$medlemArray = Tavling::getResultMember($tid, $mid);
	//print_r($medlemArray); 
	$foretag_namn = $medlemArray[0]['foretag_namn'];
	$foretag_id = $medlemArray[0]['foretag_id'];
	
	
  //get the submitted companys data
  $foretagArray = Tavling::getResultCompany($tid, COMPETITION_DAYS, - 1, $foretag_id);
  //print_r($foretagArray); 

  
  //get all the teams with median for the submitted company
  //Lagtoppen
  $foretagLagArray = Tavling::getResultTeam($tid, $foretag_id); 
  //print_r($foretagLagArray);   
    
  
  //get all the teams with member data for the submitted company
  //Slutresultat inom lagen
  $allaLag = array();
  foreach ($foretagLagArray as $key => $lag) {
    $foretagMedlemArray = Tavling::getResultCompanyTeamMember($tid, $lag['lag_id']);
    array_push($allaLag, $foretagMedlemArray);    
  }
  //print_r($allaLag);  

    
  //get all members for the submitted company
  //Deltagartoppen  no limit - all members (-1) 
  $allCompMembArray = Tavling::getResultAllMembers($tid, COMPETITION_DAYS, -1, $foretag_id);
  //print_r($allCompMembArray);  
  
  
  //get all members for the whole contest
  //Deltagartoppen
  //if the member ranks lower than DELTAGARTOPPEN_MAX then add her the the array
  $allMembArray = Tavling::getResultAllMembers($tid, COMPETITION_DAYS, DELTAGARTOPPEN_MAX);
  if($medlemArray[0]['rank'] > DELTAGARTOPPEN_MAX){
    array_push($allMembArray, $medlemArray[0]);    
  }
  //print_r($allMembArray);  	
	
 
  //get all the teams with median
  //Lagtoppen
  $lagArray = Tavling::getResultTeam($tid); 
  //print_r($foretagLagArray);   
  

  //get all companys average
  //Foretagstoppen
  $allForetagArray = Tavling::getResultCompany($tid, COMPETITION_DAYS, FORETAGSTOPPEN_MAX);
  //print_r($allForetagArray);   
  
	
	
	
		
	
  
  
	$medlem = Medlem::loadById($mid);
	$foretag = Foretag::loadById($medlem->getForetag()->getId());
//	$foretagId = $foretag->getId();
//	$slutDatum = $foretag->getSlutdatum(); // this is the active companys enddate, all companies with this enddate or later should be shown
//  $slutDatum = date("Y-m-d", strtotime($foretag->getSlutdatum() . "-1 day"));   //-1 so that it doesn't add steps added monday after ended competition 
//	$slutDatum_ts = strtotime($slutDatum);  
}
else
{
	throw new UserException('Du tillhör inte detta företag', 'Sidan du försökte komma åt kräver att man använder en länk som man får i ett mail, när man gått klart en företagstävling');
}

//$smarty = new MMSmarty(true, -1); // Caches the contest content indefinitely
	$smarty = new MMSmarty(); 
	$smarty->assign("foretagArray", $foretagArray);
  $smarty->assign("allaLag", $allaLag);
  $smarty->assign("foretagLagArray", $foretagLagArray);  
  $smarty->assign("allCompMembArray", $allCompMembArray);  
  $smarty->assign("allMembArray", $allMembArray);
  $smarty->assign("lagArray", $lagArray);
  $smarty->assign("allForetagArray", $allForetagArray); 
  
  //these two highlites the user on tavlingsresultat.php !?!?
  $smarty->assign("tavlingsresultatsidan",true);
  $smarty->assign("medlem",$medlem);
  
  $smarty->assign("foretagCustomBild", CustomForetagsbild::getImgUrlIfValidFile($foretag_id));    

  
  $bildblock = FotoalbumBild::loadForetagsBildblock($foretag, $antal = 20);
  $smarty->assign("bildblock", $bildblock);
  
	
	// $smarty->assign("positioner", $positioner);

/*	

	
	
	
	$smarty->assign("CompanyTeams", $foretagArray);
	
	$smarty->assign('pagetitle', ucfirst($foretag_namn . ' &mdash; Sammanfattning av tävling'));
	$smarty->assign("foretag", $foretag);

	$smarty->assign("positioner", $positioner);
	$smarty->assign("nr", $nr);
	$smarty->assign("multiply", $multiplier);
	$smarty->assign("topplistan", $topplistan);

	//false if no custom added
	$foretagCustomBild = CustomForetagsbild::getImgUrlIfValidFile($foretag->getId());
	$smarty->assign("foretagCustomBild", $foretagCustomBild);


	$bildblock = FotoalbumBild::loadForetagsBildblock($foretag, $antal = 20);
	$smarty->assign("bildblock", $bildblock);

	$smarty->assign("topplistaDeltagare", $topplistaDeltagare);

	$smarty->assign("startDatum", $medlemArray[0][start_datum]);
	$smarty->assign("slutDatum", $medlemArray[0][stop_datum]);
	$smarty->assign("topplista_foretag", $tf);
	$smarty->assign("topplista_lag", $tl);
	$smarty->assign("topplista_medlem", $tm);

*/


  
  //false if no custom added
  
  
$smarty->display('contest_results_template_from_db.tpl', $foretag_id);
?>
