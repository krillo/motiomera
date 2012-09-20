<?php
/*
 * 2012-09-10 Krillo
 * Call this by ajax
 * prints:
 * 1. a member list
 * 2. html clip code
 * 3. email list
 * 
 */
require_once($_SERVER["DOCUMENT_ROOT"] . "/php/init.php");
error_reporting(E_ALL);
ini_set('display_errors', '1');

!empty($_REQUEST['date']) ? $date = $_REQUEST['date'] : $date = '';
!empty($_REQUEST['comps']) ? $comps = $_REQUEST['comps'] : $comps = '';
!empty($_REQUEST['nbr-steps']) ? $nbrSteps = $_REQUEST['nbr-steps'] : $nbrSteps = '';
!empty($_REQUEST['nbr-winners']) ? $nbrWinners = $_REQUEST['nbr-winners'] : $nbrWinners = '';
!empty($_REQUEST['prevwinners']) ? $prevWinners = $_REQUEST['prevwinners'] : $prevWinners = '0';
//echo $prevWinners .'  '.$nbrWinners . '  '.$nbrSteps .'  '.$comps .'  '.$date;

$winners = Medlem::getVeckoVinnare($date, $nbrWinners, $nbrSteps, $comps, $prevWinners);
$i = 0;
$winnertable = '<table id=""><tr>';
$winnertable .= '<tr><th></th><th>ID</th><th>Alias</th><th>Namn</th><th>epost</th><th>steg</th><th>Företag</th><th>Datum</th><th>Tidigare vinst</th><tr/>';
foreach ($winners as $id => $medlem) {
  $i++;
  $winnertable .= '<tr >                    
                     <td class="mmList2">'.$i.'</td>
                     <td class="mmList1">'.$medlem["id"].'</td>
                     <td class="mmList2">'.$medlem["aNamn"].'</td>
                     <td class="mmList2">'.$medlem["fNamn"].' '.$medlem["eNamn"].'</td>
                     <td class="mmList1">'.$medlem["epost"].'</td>
                     <td class="mmList2">'.$medlem["steg"].'</td>
                     <td class="mmList1">'.$medlem["companyname"].'</td>
                     <td class="mmList2">'.$medlem["startdatum"].' - '.$medlem["slutdatum"].'</td>
                     <td class="mmList1" id="win-date-'.$medlem["id"].'">'.$medlem["veckotavling_datum"].'</td>
                   </tr>';
}
$winnertable .= '</table>';
echo $winnertable;


$jdate = new JDate($date);
$week = $jdate->getWeek();
if (count($winners) > 0) {
  $att = null;
  $emaillist = '';
  $winnerIds = '';
  $namelist = '<br/>';
  $html = "<h2>VINNARE VECKA $week</h2><h2>";
  foreach ($winners as $id => $medlem) {
    $att.= "\n";
    $html .= '<a href="/pages/profil.php?mid='.$medlem["id"].'">'.$medlem["aNamn"].'</a><br />';
    $emaillist .= $medlem["epost"] . ", ";
    $winnerIds .= $medlem["id"] . ", "; 
    $namelist .= $medlem["fNamn"].' '.$medlem["eNamn"].' - '.$medlem["epost"].' - '.$medlem["steg"]. '<br />';    
  }
  $html .= "</h2>";
  $winnerIds = substr($winnerIds, 0, strlen($winnerIds)-2); 
}

echo 
'<script type="text/javascript">
  $(function(){
    $("#reg-winners").click(function(event) {
      $.ajax({
        type: "POST",
        url: "/api/regWeekWinners.php",
        data: "&winnerIds='.$winnerIds.'" ,
        success: function(data){
          $("#status").html(data).fadeIn();            
        }
      });            
    });
  });
</script>';


echo '<input type="button" value="Godkänn vinnare" id="reg-winners"/>';

echo '<br/><div><p style="font-weight:bold">HTML för att klistra in på presentationssidan</p>' . htmlspecialchars($html) . '</div>'; 
echo '<br/><div><p style="font-weight:bold">Epostlista</p>' . htmlspecialchars($emaillist) . '</div>'; 
echo '<br/><div><p style="font-weight:bold">Namnlista</p>'.$namelist.'</div>'; 
