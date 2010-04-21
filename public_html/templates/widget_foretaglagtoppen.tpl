<div class="mmAlbumBoxTop">
		<h3 class="mmWhite BoxTitle">Lagtoppen</h3>
	</div>
	<div class="mmRightMinSidaBox">
	
	<strong>Snitt per deltagare från start</strong><br /><br />
	<table width="155" cellpadding="0" cellspacing="0" border="0">
		<tr>
		  <td>&nbsp;</td>
		  <td><b>Lag</b></td>
		  <td><b>Steg</b></td>
		</tr>
		{foreach name=steglista from=$topplistan item=l}

			{if $smarty.foreach.steglista.iteration < 11 && isset($lag) && $l->getId() neq $lag->getId()}
			<tr>
				<td>{$smarty.foreach.steglista.iteration}.</td>
				<td><a href="{$urlHandler->getUrl("Lag", URL_VIEW, $l->getId())}">{$l->getNamn()}</a></td>
				<td class="number">{$l->getStegTotal(true)|nice_tal}</td>
			</tr>
			{elseif $smarty.foreach.steglista.iteration < 11 && isset($lag) && $l->getId() eq $lag->getId()}
			<tr>
				<td>{$smarty.foreach.steglista.iteration}.</td>
				<td><strong><a class="mm_topplista_markerad" href="{$urlHandler->getUrl("Lag", URL_VIEW, $l->getId())}">{$l->getNamn()}</a></strong></td>
				<td class="number"><strong class="mm_topplista_markerad">{$l->getStegTotal(true)|nice_tal}</strong></td>
			</tr>
			{elseif isset($lag) && $l->getId() eq $lag->getId()}
			<tr><td><hr size="1" /></td><td><hr size="1" /></td><td><hr size="1" /></td></tr>
			<tr>
				<td>{$smarty.foreach.steglista.iteration}.</td>
				<td><strong><a class="mm_topplista_markerad" href="{$urlHandler->getUrl("Lag", URL_VIEW, $l->getId())}">{$l->getNamn()}</a></strong></td>
				<td class="number"><strong class="mm_topplista_markerad">{$l->getStegTotal(true)|nice_tal}</strong></td>
			</tr>
			{/if}
		{/foreach}
	</table>

	
	</div>
