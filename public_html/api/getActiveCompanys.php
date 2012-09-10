<?php
/* ajax call - return all active companys for the actual date
 * krillo 2012-09-07
 */
require_once($_SERVER["DOCUMENT_ROOT"] . "/php/init.php");
error_reporting(E_ALL);
ini_set('display_errors', '1');

!empty($_REQUEST['date']) ? $date = $_REQUEST['date'] : $date = 'false';
//get all active companys, make checkboxes 
$compArray = Foretag::getAllActiveCompanys($date);
$checkbox = '
<script type="text/javascript">
  jQuery(document).ready(function(){
  
   $("#all").click(function(event) {
      var all = $("#all:checked").val();
      if(all === undefined){
        $("[type=checkbox]").attr("checked", false);
      } else {
        $("[type=checkbox]").attr("checked", true);

      }  
    });
  });
</script>';

$checkbox .= '<table id=""><tr>';
$checkbox .= '<td><input type="checkbox" value="alla" id="all" name="all-companys" checked></td><td colspan="2"><label for="all">Alla eller ingen</label></td><tr/>';
$checkbox .= '<tr><th></th><th>Id</th><th>#</th><th>Företagsnamn</th><th>Tävlingsdatum</th><tr/>';
foreach ($compArray as $id => $comp) {
  $checkbox .= '<tr>
                 <td class="mmList1"><input type="checkbox" value="'.$id.'" id="'.$id.'" name="company" checked></td>
                 <td class="mmList2"><label for="'.$id.'">'.$comp['id'].'</label></td>
                 <td class="mmList1">'.$comp['count'].'</td>
                 <td class="mmList2"><a href="/pages/editforetag.php?fid='.$id.'&tab=2" >'.$comp['namn'].'</a></td>
                 <td  class="mmList1" width="160px">'.$comp['startdatum'].' - '.$comp['slutdatum'].'</td>
                </tr>';
}
$checkbox .= '</table>';
echo $checkbox;
?>