<div id="mmGrafikPositioner">
	<div class="mmGrafikPositionText">Totala steg per deltagare från start</div>
	<div class="mmGrafikPosition mmGrafikPositionTop">	
		{foreach from=$positioner item=position key=medlem_id name=loopPositionerTop}
		{if $smarty.foreach.loopPositionerTop.iteration % 2 neq 0}
		<div class="mmGrafikPositionMedlem" style="margin-left: {$position}px;">
			{assign var=nummer value=$positioner|@count}
			<div class="mmh3 mmOrange">#{$nummer-$smarty.foreach.loopPositionerTop.iteration+1}</div>
			<b>{$medlemmar[$medlem_id]->getANamn()}</b><br />
			{$medlemmar[$medlem_id]->getJustNuKommunNamn()}<br />
			
				{$medlemmar[$medlem_id]->getStegTotalLag($lag2)|nice_tal}<br />
				
			{assign var=avatar value=$medlemmar[$medlem_id]->getAvatar()}
			<a href="{$urlHandler->getUrl(Medlem, URL_VIEW, $medlemmar[$medlem_id]->getId())}"><img src="{$avatar->getUrl()}" class="mmAvatar" alt="" /></a><br />|
		</div>
		{/if}
		{/foreach}
	</div>
	<div class="mmGrafikPosition mmGrafikPositionBottom">
		{foreach from=$positioner item=position key=medlem_id name=loopPositionerBottom}
		{if $smarty.foreach.loopPositionerBottom.iteration % 2 eq 0}
		<div class="mmGrafikPositionMedlem" style="margin-left: {$position}px;">
			|<br />
			{assign var=nummer value=$positioner|@count}
			<div class="mmh3 mmOrange">#{$nummer-$smarty.foreach.loopPositionerBottom.iteration+1}</div>
			<b>{$medlemmar[$medlem_id]->getANamn()}</b><br />
			{$medlemmar[$medlem_id]->getJustNuKommunNamn()}<br />
			
				{$medlemmar[$medlem_id]->getStegTotalLag($lag2)|nice_tal}<br />
			
			{assign var=avatar value=$medlemmar[$medlem_id]->getAvatar()}
			<a href="{$urlHandler->getUrl(Medlem, URL_VIEW, $medlemmar[$medlem_id]->getId())}"><img src="{$avatar->getUrl()}" class="mmAvatar" alt="" /></a>
		</div>
		{/if}
		{/foreach}
	</div>
</div>
