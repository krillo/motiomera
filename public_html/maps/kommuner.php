<?php
require_once($_SERVER["DOCUMENT_ROOT"]."/php/init.php");
 
?>
<map animation='1' showShadow='1' mapLeftMargin='0' mapRightMargin='0' mapBottomMargin='0'  maptopMargin='0' showBevel='0' showCanvasBorder='0'  showMarkerLabels='1' fillColor='F1f1f1' borderColor='CCCCCC' baseFont='Arial Narrow' baseFontSize='16' markerBorderColor='000000' markerBgColor='FF5904' markerRadius='6' legendPosition='bottom' useHoverColor='1' hoverColor='FFCC66' showMarkerToolTip='1'  markerFontColor='FF5904' connectorColor='FF0000' showLabels='0'  includeValueInLabels='1' BorderColor='0372AB' showToolTip='1' waterBodyColor='00CCFF' waterBodyAlpha='50' >
	<data>
		<?		
		$kommuner = Kommun::listAll();
		
		while(list($key,$kommun) = each($kommuner)){
			if(array_search($kommun->getKod(),$exclude) === false) 
				{?><entity id= '<?=$kommun->getKod()?>' link='/kommun/<?=urlencode(utf8_decode($kommun->getUrlNamn()))?>' displayValue=' ' /><?php }
		}
		

		
		?>

	   	</data>
	 
</map>
