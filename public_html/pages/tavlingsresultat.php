<?php
include $_SERVER["DOCUMENT_ROOT"]  . '/php/init.php';
ini_set('memory_limit','128M');

if(isset($_GET["id"]) && $_GET["id"] == floor($_GET["id"]))
{
  $id = $_GET["id"];
  $medlem = Medlem::loadById($id);
  $foretag = Foretag::loadById($medlem->getForetag()->getId());
  $foretagId = $foretag->getId();
  $slutDatum = $foretag->getSlutdatum(); // this is the active companys enddate, all companies with this enddate or later should be shown
  $slutDatum_ts = strtotime($slutDatum);
}
else
{
  throw new UserException('Du tillhör inte detta företag', 'Sidan du försökte komma åt kräver att man använder en länk som man får i ett mail, när man gått klart en företagstävling');
}

$smarty = new MMSmarty(true, -1); // Caches the contest content indefinitely
$smarty->assign("medlem",$medlem);
$smarty->assign("tavlingsresultatsidan",true);

if(!$smarty->is_cached('contest_results_template.tpl', $foretagId))
{

  if(isset($foretag)){
    $CompanyTeams = $foretag->listLag();
    $smarty->assign("CompanyTeams", $CompanyTeams);
  }


  $smarty->assign('pagetitle', ucfirst($foretag->getNamn()).' &mdash; Sammanfattning av tävling');
  $kommun = $foretag->getKommun();
  $smarty->assign("foretag", $foretag);
  $smarty->assign("kommun", $kommun);


  $topplistan = $foretag->getTopplistaLag(false, true);
  $flagslice = null;
  $nr = null;

  if(count($topplistan) < 2)
  {
    $multiplier = 0;
  }
  else
  {
    if(count($topplistan)>10)
    {
      $flagslice = true;
      $nr=9;
    }
    else
    {
      $nr=count($topplistan)-1;
    }
    if (empty($nr))
    {
      $nr = 1;
    }
    $multiplier = 500/($nr);
  }

  $i = 0;   
  $positioner = array();
  foreach($topplistan as $lag)
  {
    $positioner[$i] = $lag;
    $i++;
  }

  $positioner = array_reverse($positioner);
  if(count($positioner)>10)
  {
    $positioner = array_slice($positioner, count($positioner)-10, 10);
  }

  $smarty->assign("positioner", $positioner);
  $smarty->assign("nr", $nr);
  $smarty->assign("multiply", $multiplier);
  $smarty->assign("topplistan", $topplistan);

  //false if no custom added
  $foretagCustomBild = CustomForetagsbild::getImgUrlIfValidFile($foretag->getId());
  $smarty->assign("foretagCustomBild", $foretagCustomBild);


  $bildblock = FotoalbumBild::loadForetagsBildblock($foretag, $antal = 20);
  $smarty->assign("bildblock", $bildblock);

  // 
  // Global toplists.
  //
  $tf = array();
  $tfObjects = Foretag::listAll();
  $tf = array();
  foreach($tfObjects as $tForetag){
    if(!empty($tForetag) && strtotime($tForetag->getSlutDatum()) >= $slutDatum_ts){
      
      $stegindex = $tForetag->getStegIndex();
      if ($stegindex != 0) {
        
        $foretag_ids[] = $tForetag->getId();
        
        $tNamn = $tForetag->getNamn();
        $tNamn = utf8_decode($tNamn);
        $tNamn = wordwrap($tNamn,15,"<br/>",true);
        $tNamn = utf8_encode($tNamn);

        $tf[] = array("stegindex" => $stegindex,"namn"=> $tNamn, "id"=> $tForetag->getId());
      }
      
      unset($tForetag);

    }
  }
  
  unset($tfObjects);
  unset($foretag_stegtotal_cache);
  if(count($tf) != 0)
  {
    array_multisort($tf, SORT_DESC);
  }
  
  $tl = array();
  $tlObjects = Lag::listAll();

  $tl = array();
  foreach($tlObjects as $lag){
    if(!empty($lag) && strtotime($lag->getForetag()->getSlutDatum()) >= $slutDatum_ts){
      $stegindex = $lag->getStegIndex();
      if($stegindex != 0){
        
        $tNamn = $lag->getNamn() . "<br/> från " . $lag->getForetag()->getNamn();
        
        $tNamn = utf8_decode($tNamn);
        $tNamn = wordwrap($tNamn,15,"<br/>",true);
        $tNamn = utf8_encode($tNamn);

        $tl[] = array("stegindex" => $stegindex,"namn"=> $tNamn, "id"=> $lag->getId());
      }
      
      unset($lag);

    }
    

  }
  unset($tlObjects);
  unset($lag_stegtotal_cache);
  if(count($tl) != 0) {
    array_multisort($tl, SORT_DESC);
  }

  $tm = array();
  if(empty($foretag_ids)) {
    $foretag_ids = array();
    foreach($tf as $foretag) {
      $foretag_ids[] = $foretag["id"];
    }
  }
  
  $sql = "SELECT foretag_id, medlem_id, aNamn FROM " . Foretag::KEY_TABLE . " f INNER JOIN " . Medlem::TABLE . " m ON f.medlem_id=m.id WHERE medlem_id > 0 AND foretag_id IN (" . implode(",",$foretag_ids) . ")";
  $res = $db->query($sql);
  
  while($row = mysql_fetch_array($res)) {
    try {
      $tForetag = Foretag::loadById($row["foretag_id"]);
      $medlem_id = $row["medlem_id"];
      $medlem_anamn = $row["aNamn"];
    }
    catch(Exception $e) {
      // Foretag doesn't exist (we check this just in case)
      continue;
    }
    if(!empty($medlem_id)){
      $stegindex = Medlem::getStegIndexForMedlemId($medlem_id,$tForetag);
      if($tForetag != null && $stegindex != null && strtotime($tForetag->getSlutDatum()) >= $slutDatum_ts){

        $tm[] = array("stegindex"=> $stegindex, "namn"=> $medlem_anamn, "id"=> $medlem_id);

      }
    }
  }
  
  unset($tForetag);
  unset($medlem_stegtotal_cache);
  unset($res);
  if(count($tm) != 0)
  {
    array_multisort($tm, SORT_DESC);
  }
  
  unset($positioner);
  unset($topplistan);   
  unset($tmObjects);
  unset($tlObjects);
  unset($tfObjects);
  
  $smarty->assign("medlemmar", $medlemmar);
  $topplistaDeltagare = new Topplista();
  $topplistaDeltagare->addParameter(Topplista::PARAM_FORETAG, $foretag);
  $topplistaDeltagare->addParameter(Topplista::PARAM_START, $foretag->getStartdatum());
  $smarty->assign("topplistaDeltagare", $topplistaDeltagare);

  $smarty->assign("startDatum",$foretag->getStartdatum());
  $smarty->assign("slutDatum", $slutDatum);
  $smarty->assign("topplista_foretag", $tf);
  $smarty->assign("topplista_lag", $tl);
  $smarty->assign("topplista_medlem", $tm);

}
$smarty->display('contest_results_template.tpl', $foretagId);
?>
