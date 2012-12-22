	<div class="mmAlbumBoxTop">
		<h3 class="BoxTitle">Steglistan senaste 7 dagarna</h3>
	</div>
	<div class="mmRightMinSidaBox table-padding">
	<table cellpadding="0" cellspacing="0" border="0">
		<tr>
		  <td>&nbsp;</td>
		  <td><b>Medlem</b></td>
		  <td><b>Steg</b></td>
		</tr>
		{foreach name=steglista from=$topplista->getTopplista(10,$medlem) item=placering}
      
		{if $placering.placering == 11}
			{assign var=tomrad value=1}
		{/if}
		{if $placering.placering > 10 && $tomrad == 0}
			{assign var=tomrad value=1}
			<tr><td>&nbsp;</td></tr>
		{/if}
		<tr {if $smarty.foreach.steglista.index % 2}{else}class="odd"{/if}>
			<td style="padding-left: 10px;">{$placering.placering}.</td>
			<td><a href="{$urlHandler->getUrl("Medlem", URL_VIEW, $placering.medlem->getId())}">{if isset($medlem) && $placering.medlem->getId() == $medlem->getId()}<strong class="mm_topplista_markerad">{$placering.medlem->getANamn()|truncate:16}</strong>{else}{$placering.medlem->getANamn()|truncate:16}{/if}</a></td>
			<td style="padding-right: 10px;">{if isset($medlem) && $placering.medlem->getId() == $medlem->getId()}<strong class="mm_topplista_markerad">{$placering.steg|nice_tal}</strong>{else}{$placering.steg|nice_tal}{/if}</td>
		</tr>
		{/foreach}
	</table>
	</div>
  <div class="show-more-link">
    <a href="{$urlHandler->getUrl("Topplista", URL_LIST) }">Visa fler topplistor &raquo;</a>
  </div>
  <div class="clear"></div>