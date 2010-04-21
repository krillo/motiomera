<div id="mmGrafikPositioner">
	<div class="mmGrafikPositionText">Snitt per deltagare i lag från start</div><div class="mmClearBoth"></div>
	<div class="mmGrafikPosition mmGrafikPositionTop">
		{foreach from=$positioner item=lag key=list name=loopPositionerTop}
		{if $smarty.foreach.loopPositionerTop.iteration % 2 neq 0}
		<div class="mmGrafikPositionMedlem" style="margin-left: {$list*$multiply}px;">
			{assign var=nummer value=$topplistan|@count}
			{assign var=stegtotal value=$lag->getStegTotal(false,null,true)}
			{assign var=antalmedlemmar value=$lag->countMedlemmar()}
			<div class="mmh3 mmOrange">#{$nr+2-$smarty.foreach.loopPositionerTop.iteration}</div>
			<b>{$lag->getNamn()}</b><br />
			
				{$stegtotal/$antalmedlemmar|nice_tal}<br />					
				<a href="{$urlHandler->getUrl(Lag, URL_VIEW, $lag->getId())}"><img src="{$lag->getBildFullUrl()}" class="mmAvatar" alt="" /></a><br />|
				
		</div>
		{/if}
		{/foreach}
	</div>
	<div class="mmGrafikPosition mmGrafikPositionBottom">
		{foreach from=$positioner item=lag key=list name=loopPositionerBottom}
		{if $smarty.foreach.loopPositionerBottom.iteration % 2 eq 0}
		<div class="mmGrafikPositionMedlem" style="margin-left: {$list*$multiply}px;">
			|<br />
			{assign var=nummer value=$topplistan|@count}
			{assign var=stegtotal value=$lag->getStegTotal(false,null,true)}
			{assign var=antalmedlemmar value=$lag->countMedlemmar()}
			<div class="mmh3 mmOrange">#{$nr+2-$smarty.foreach.loopPositionerBottom.iteration}</div>
			<b>{$lag->getNamn()}</b><br />
			
				{$stegtotal/$antalmedlemmar|nice_tal}<br />					
				<a href="{$urlHandler->getUrl(Lag, URL_VIEW, $lag->getId())}"><img src="{$lag->getBildFullUrl()}" class="mmAvatar" alt="" /></a><br />
				
		</div>
		{/if}
		{/foreach}
	</div>
</div>
