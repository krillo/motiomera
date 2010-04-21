{if !isset($toplist_count)}
	{assign var=length value=11}
{else}
	{assign var=length value=$toplist_count}
{/if}
<div class="{if $hof}hallOfFame{/if} mmTopplistaRuta"> 
	<div class="mmAlbumBoxTop">
		<h3 {if $hof}style="position:relative; font-size:18px; color:#BB2523; text-align:center; top:60px;"{else} class="mmWhite BoxTitle"{/if}>Lagtoppen</h3>
	</div>
	<div class="mmRightMinSidaBox">
	
	<strong>Dagligt snitt per deltagare från start</strong><br /><br />
	<table class="toplista" width="155" cellpadding="0" cellspacing="0" border="0">
		<tr>
		  <td>&nbsp;</td>
		  <td><b>Medlem</b></td>
		  <td><b>Steg</b></td>
		</tr>
		{foreach name=steglista from=$topplista_lag item=lag key=lagKey}
			{if !isset($TEAM)}
				{assign var=T.id value=0}
			{else}
				{assign var=Tid value=$TEAM->getId()}
			{/if}

			{if $smarty.foreach.steglista.iteration < $toplist_count && $lag.id neq $Tid}
			<tr>
				<td>{$smarty.foreach.steglista.iteration}.</td><td><a href="{$urlHandler->getUrl("Lag", URL_VIEW, $lag.id)}">{$lag.namn}</a></td><td class="number">{$lag.stegindex|nice_tal}</td>
			</tr>
			{elseif $smarty.foreach.steglista.iteration < $toplist_count && $lag.id eq $Tid}
			<tr>
				<td>{$smarty.foreach.steglista.iteration}.</td><td><strong><a class="mm_topplista_markerad" href="{$urlHandler->getUrl("Lag", URL_VIEW, $lag.id)}">{$lag.namn}</a></strong></td><td class="number"><strong class="mm_topplista_markerad">{$lag.stegindex|nice_tal}</strong></td>
			</tr>
			{elseif $lag.id eq $Tid}
			<tr><td><hr size="1" /></td><td><hr size="1" /></td><td><hr size="1" /></td></tr>
			<tr>
				<td>{$smarty.foreach.steglista.iteration}.</td><td><strong><a class="mm_topplista_markerad" href="{$urlHandler->getUrl("Lag", URL_VIEW, $lag.id)}">{$lag.namn}</a></strong></td><td class="number"><strong class="mm_topplista_markerad">{$lag.stegindex|nice_tal}</strong></td>
			</tr>
			{elseif $lag.id eq $Tid}
			{/if}
		{/foreach}
	</table>
	</div>
</div>