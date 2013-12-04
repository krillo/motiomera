<?php

require_once($_SERVER["DOCUMENT_ROOT"]."/php/init.php");

Security::demand(USER);


$rutt = new Rutt($USER);
$rutten = $rutt->getRutt();

$sista = $rutt->getCurrentIndex();

$just_nu_id = $USER->getJustNuKommunId();

$just_nu_kommun = Kommun::loadById($just_nu_id);

$exclude[] = $just_nu_kommun->getKod();



$avstand = $just_nu_kommun->listAvstand();	

$grannkommuner = array();

foreach($avstand as $tmp){

	$exclude[] = $tmp["id"];

	$grannkommuner[] = Kommun::loadById($tmp["id"]);
}

?>
<map animation='1' showShadow='1' mapLeftMargin='0' mapRightMargin='0' mapBottomMargin='0'  maptopMargin='0' showBevel='0' showCanvasBorder='0'  showMarkerLabels='1' fillColor='F1f1f1' borderColor='CCCCCC' baseFont='Arial Narrow' baseFontSize='16' markerBorderColor='000000' markerBgColor='FF5904' markerRadius='6' legendPosition='bottom' useHoverColor='1' hoverColor='000000' showMarkerToolTip='1'  markerFontColor='FF5904' connectorColor='FF0000' showLabels='0' BorderColor='0372AB' showToolTip='1' waterBodyColor='00CCFF' waterBodyAlpha='50' >
	<data>
	
		<entity id= '<?=$just_nu_kommun->getKod()?>' color='#FF0000'/>

		<?php
		

		while(list($key,$kommun) = each($grannkommuner)) {
		
			$exclude[] = $kommun->getKod();
			



	   			?><entity id= '<?=$kommun->getKod()?>' color='#00FF00' link="<?=$urlHandler->getUrl("Stracka", URL_SAVE)?>&target=<?=$kommun->getId()?>"/>
		   		<?php 
	   		
		}
		
		
		while(list($key,$stracka) = each($rutten)) {
		
		
			if($key > $sista) {
				// har ej gått till denna kommun. bryt
		   		
				break;
			}
						
			if(array_search($stracka["Kommun"]->getKod(),$exclude) === false) {
		
	   			?><entity id= '<?=$stracka["Kommun"]->getKod()?>' color='#FFCC00' />
		   		<?php
	   		
	   		}
	   		
			$exclude[] = $stracka["Kommun"]->getKod();
		
		}
		
		$kommuner = Kommun::listAll();
		
		while(list($key,$kommun) = each($kommuner)) {
		
			if(array_search($kommun->getKod(),$exclude) === false) {
			
				?><entity id= '<?=$kommun->getKod()?>'/>
				<?php

				
				
			}
		
		}

		
		?>
	   	</data>
	 
</map>
		