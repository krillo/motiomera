<?php
// LILLA RUTTKARTAN PÅ "PLANERAD RUTT"
require_once($_SERVER["DOCUMENT_ROOT"]."/php/init.php");

//Security::demand(USER);

error_reporting(0);

if(isset($_GET["medlem"])) {
	$medlem = Medlem::loadById($_GET["medlem"]);

}
else {
	$medlem = $USER;
}


$rutt = new Rutt($medlem);
$rutten = $rutt->getRutt();

$sista = $rutt->getCurrentIndex();

$just_nu_id = $medlem->getJustNuKommunId();

$just_nu_kommun = Kommun::loadById($just_nu_id);

$exclude[] = $just_nu_kommun->getKod();

$kommuntext = array();
$c = 0;
while(list($key,$stracka) = each($rutten)) {

	$c++;
	$key = $stracka["Kommun"]->getKod();
	$kommuntext[$key] = $kommuntext[$key]?($kommuntext[$key] . ",$c"):$c;

}

reset($rutten);

?>
<map animation='1' showShadow='1' mapLeftMargin='0' mapRightMargin='0' mapBottomMargin='0'  maptopMargin='0' showBevel='0' showCanvasBorder='0'  showMarkerLabels='1' fillColor='F1f1f1' borderColor='CCCCCC' baseFont='Arial Narrow' baseFontSize='10' markerBorderColor='000000' markerBgColor='FF5904' markerRadius='6' legendPosition='bottom' useHoverColor='0' hoverColor='FF0000' showMarkerToolTip='1'  markerFontColor='FF5904' connectorColor='FF0000' showLabels='0'  includeValueInLabels='1' BorderColor='0372AB' showToolTip='1' waterBodyColor='00CCFF' waterBodyAlpha='50' >
	<data>
		<entity id= '<?=$just_nu_kommun->getKod()?>' color='#DBB444' link='/kommun/<?=($just_nu_kommun->getUrlNamn())?>'  displayValue='<?=$komuntext[$just_nu_kommun->getKod()]?$kommuntext[$just_nu_kommun->getKod()]:" "?>' />

		<?php
		
		while(list($key,$stracka) = each($rutten)) {
			
			if($key > $sista) {
				// har ej gått till dessa kommuner: 
				if(array_search($stracka["Kommun"]->getKod(),$exclude) === false) 
					{?><entity id= '<?=$stracka["Kommun"]->getKod()?>' color='#EDDCA9' link='/kommun/<?=($stracka["Kommun"]->getUrlNamn())?>' displayValue=' ' /> <?php }
				$exclude[] = $stracka["Kommun"]->getKod();
				//break;
			}
			// har gått till dessa kommuner:
			if(array_search($stracka["Kommun"]->getKod(),$exclude) === false) {
	   			?><entity id= '<?=$stracka["Kommun"]->getKod()?>' color='#DBB444' link='/kommun/<?=($stracka["Kommun"]->getUrlNamn())?>' displayValue=' ' /><?php }
			$exclude[] = $stracka["Kommun"]->getKod();

		}
		
		$kommuner = Kommun::listAll();
		
		while(list($key,$kommun) = each($kommuner)){
			// Alla andra kommuner:
			if(array_search($kommun->getKod(),$exclude) === false) 
				{?><entity id= '<?=$kommun->getKod()?>' displayValue=' ' /><?php }
		}
		

		
		?>

	   	</data>
	 
</map>
		