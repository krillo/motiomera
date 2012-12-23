<div class="mmBlueBoxTop"><h3 class="BoxTitle">Händelser</h3></div>
<div class="mmBlueBoxBg">

	<table class="mmFeedTable" border="0" cellpadding="0" cellspacing="0">
		<tr>
			<td></td>
			<td></td>
		</tr>
		{foreach from=$feed item=feedRow name="feedLoop"}
		{if !isset($senaste) || $senaste neq $feedRow->getDatum()|nice_date:"j F":"d"}
		<tr>
			<td colspan="2" class="mmFeedTableDatum"><b>{$feedRow->getDatum()|nice_date:"j F":"d"|capitalize}</b></td>
		</tr>
		{/if}
		{assign var=senaste value=$feedRow->getDatum()|nice_date:"j F":"d"}
		<tr>
			<td class="mmFeedTableKolumnEtt">
				{if $feedRow->isGrupp()}
				<a href="#" onclick="toggleFeedDetails(this, {$smarty.foreach.feedLoop.iteration}); return false;"><img src="/img/icons/plus.gif" alt="" /></a>
				{else}
				<img src="/img/icons/dot.gif" alt="" />
				{/if}	
			</td>
			<td>
				{$feedRow->getText()}<br />
				{if $feedRow->isGrupp()}
				<div id="mmFeedDetails{$smarty.foreach.feedLoop.iteration}" class="mmFeedDetails">
					{foreach from=$feedRow->listFeedItems() item=item}
					&nbsp;&raquo;&nbsp; {$item->getText()} ({$item->getDatum()})<br />
					{/foreach}
				</div>
				{/if}
			</td>
		</tr>	
		{/foreach}
	</table>
</div>
<div class="mmBlueBoxBottom"></div>