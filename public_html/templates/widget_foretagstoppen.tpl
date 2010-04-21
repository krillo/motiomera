{if !isset($toplist_count)}
	{assign var=length value=11}
{else}
	{assign var=length value=$toplist_count}
{/if}
<div class="{if $hof}hallOfFame{/if} mmTopplistaRuta">
	<div class="mmAlbumBoxTop">
		<h3 {if $hof}style="position:relative; font-size:18px; color:#BB2523; text-align:center; top:60px;"{else} class="mmWhite BoxTitle"{/if}>F&ouml;retagstoppen</h3>
	</div>
	<div class="mmRightMinSidaBox">
	<strong>Dagligt snitt per deltagare från start</strong><br /><br />
	<table class="toplista" width="155" cellpadding="0" cellspacing="0" border="0">
		<tr>
		  <td>&nbsp;</td>
		  <td><b>Medlem</b></td>
		  <td><b>Steg</b></td>
		</tr>
		{foreach name=steglista from=$topplista_foretag item=foretag key=foretagKey}
			{if $smarty.foreach.steglista.iteration < $toplist_count && $foretag.id neq $COMPANY->getId()}
			<tr>
				<td>{$smarty.foreach.steglista.iteration}.</td><td>{$foretag.namn}</td><td class="number">{$foretag.stegindex|nice_tal}</td>
			</tr>
			{elseif $smarty.foreach.steglista.iteration < $toplist_count && $foretag.id eq $COMPANY->getId()}
			<tr>
				<td>{$smarty.foreach.steglista.iteration}.</td><td><strong class="mm_topplista_markerad">{$foretag.namn}</strong></td><td class="number"><strong class="mm_topplista_markerad">{$foretag.stegindex|nice_tal}</strong></td>
			</tr>
			{elseif $foretag.id eq $COMPANY->getId()}
			<tr><td><hr size="1" /></td><td><hr size="1" /></td><td><hr size="1" /></td></tr>
			<tr>
				<td>{$smarty.foreach.steglista.iteration}.</td><td><strong class="mm_topplista_markerad">{$foretag.namn}</strong></td><td class="number"><strong class="mm_topplista_markerad">{$foretag.stegindex|nice_tal}</strong></td>
			</tr>
			{/if}
		{/foreach}
	</table>
	</div>
</div>