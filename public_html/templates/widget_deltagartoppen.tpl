{if !isset($toplist_count)}
	{assign var=length value=11}
{else}
	{assign var=length value=$toplist_count}
{/if}
<div class="{if $hof}hallOfFame{/if} mmTopplistaRuta">
	<div class="mmAlbumBoxTop">
		<h3 {if $hof}style="position:relative; font-size:18px; color:#BB2523; text-align:center; top:60px;"{else} class="mmWhite BoxTitle"{/if}>Deltagartoppen</h3>
	</div>
	<div class="mmRightMinSidaBox">    
	<strong>Snitt per dag från start</strong><br /><br />
	<table width="155" class="toplista" cellpadding="0" cellspacing="0" border="0">
		<tr>
		  <td>&nbsp;</td>
		  <td><b>Medlem</b></td>
		  <td><b>Steg</b></td>
		</tr>
		{foreach name=steglista from=$topplista_medlem item=m key=lagKey}
			{if $smarty.foreach.steglista.iteration < $toplist_count && $USER->getId() neq $m.id}
			<tr>
				<td>{$smarty.foreach.steglista.iteration}.</td><td><a href="{$urlHandler->getUrl("Medlem", URL_VIEW, $m.id)}">{$m.namn}</a></td><td class="number">{$m.stegindex|nice_tal}</td>
			</tr>
			{elseif $smarty.foreach.steglista.iteration < $toplist_count && $USER->getId() eq $m.id}
			<tr>
				<td>{$smarty.foreach.steglista.iteration}.</td><td><strong><a class="mm_topplista_markerad" href="{$urlHandler->getUrl("Medlem", URL_VIEW, $m.id)}">{$m.namn}</a></strong></td><td class="number"><strong class="mm_topplista_markerad">{$m.stegindex|nice_tal}</strong></td>
			</tr>
			{elseif $USER->getId() eq $m.id}
			<tr><td><hr size="1" /></td><td><hr size="1" /></td><td><hr size="1" /></td></tr>
			<tr>
				<td>{$smarty.foreach.steglista.iteration}.</td><td><strong><a class="mm_topplista_markerad" href="{$urlHandler->getUrl("Medlem", URL_VIEW, $m.id)}">{$m.namn}</a></strong></td><td class="number"><strong class="mm_topplista_markerad">{$m.stegindex|nice_tal}</strong></td>
			</tr>
			{/if}
		{/foreach}
	</table>
	</div>
</div>
  