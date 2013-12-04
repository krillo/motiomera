<?php
// STOR KARTA NÄR MAN VÄLJER RUTT

require_once($_SERVER["DOCUMENT_ROOT"]."/php/init.php");

Security::demand(USER);


$rutt = new Rutt($USER);
$rutten = $rutt->getRutt();

$last_stracka = null;

while(list($key,$stracka) = each($rutten)) {

	$last_stracka = $stracka;
}

$rutten = $rutt->getRutt();



$sista = $rutt->getCurrentIndex();

$just_nu_id = $USER->getJustNuKommunId();

$just_nu_kommun = Kommun::loadById($just_nu_id);

$exclude[] = $just_nu_kommun->getKod();
$exclude[] = $last_stracka["Kommun"]->getKod();



$avstand = $last_stracka["Kommun"]->listAvstand();	

$grannkommuner = array();

foreach($avstand as $tmp){

	$exclude[] = $tmp["id"];

	$tk = Kommun::loadById($tmp["id"]);

	$grannkommuner[] = $tk;
}

?>
<map animation='1' showShadow='1' mapLeftMargin='0' mapRightMargin='0' mapBottomMargin='0'  maptopMargin='0' showBevel='0' showCanvasBorder='0'  showMarkerLabels='1' fillColor='F1f1f1' borderColor='CCCCCC' baseFont='Arial Narrow' baseFontSize='16' markerBorderColor='000000' markerBgColor='FF5904' markerRadius='6' legendPosition='bottom' useHoverColor='0' hoverColor='FFCC66' showMarkerToolTip='1'  markerFontColor='FF5904' connectorColor='FF0000' showLabels='0' BorderColor='0372AB' showToolTip='1' waterBodyColor='00CCFF' waterBodyAlpha='50' >
	<data>
	
		<entity id= '<?=$just_nu_kommun->getKod()?>' color='#DBB444'/>
		<entity id= '<?=$last_stracka["Kommun"]->getKod()?>' color='#EDDCA9'/>

		<?php
		

		while(list($key,$stracka) = each($rutten)) {
			if($key > $sista) {
			
				//har ej gått till denna kommun. bryt
				
				if(array_search($stracka["Kommun"]->getKod(),$exclude) === false) {
					?><entity id= '<?=$stracka["Kommun"]->getKod()?>' color='#EDDCA9'<? 			
				if(array_search($stracka["Kommun"],$grannkommuner) !== false)
					{?>link="javascript:motiomera_ruttLaggTill(<?=$stracka["Kommun"]->getId()?>)" tooltext="Klicka för att gå till <?=$stracka["Kommun"]->getNamn()?>!"<?}
				?> />
					<?php
				}
				$exclude[] = $stracka["Kommun"]->getKod();
				
				break;
			}
			
			// KOmmun som jag varit i:
			if(array_search($stracka["Kommun"]->getKod(),$exclude) === false) {
	   			?><entity id= '<?=$stracka["Kommun"]->getKod()?>' color='#DBB444'<? 			
				if(array_search($stracka["Kommun"],$grannkommuner) !== false)
					{?>link="javascript:motiomera_ruttLaggTill(<?=$stracka["Kommun"]->getId()?>)" tooltext="Klicka för att gå till <?=$stracka["Kommun"]->getNamn()?>!"<?}
				?> />
		   		<?php
	   		}
			$exclude[] = $stracka["Kommun"]->getKod();
		}
		
		while(list($key,$stracka) = each($rutten)) {
					
			if(array_search($stracka["Kommun"]->getKod(),$exclude) === false) {
	   			?><entity id= '<?=$stracka["Kommun"]->getKod()?>' color='#EDDCA9'<? 			
				if(array_search($stracka["Kommun"],$grannkommuner) !== false)
					{?>link="javascript:motiomera_ruttLaggTill(<?=$stracka["Kommun"]->getId()?>)" tooltext="Klicka för att gå till <?=$stracka["Kommun"]->getNamn()?>!"<?}
				?> />
		   		<?php
	   		}
			$exclude[] = $stracka["Kommun"]->getKod();
		}

		while(list($key,$kommun) = each($grannkommuner)) {
			if(array_search($kommun->getKod(),$exclude) === false)
				{?><entity id= '<?=$kommun->getKod()?>' <?//color='#FFEE00'?> link="javascript:motiomera_ruttLaggTill(<?=$kommun->getId()?>)" tooltext="Klicka för att gå till <?=($kommun->getNamn())?>!"/> <?php }	   		
		$exclude[] = $kommun->getKod();
		}
		
		
	?>
	</data>
	 
</map>
		