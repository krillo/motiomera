<h3 class="mmMarginTop">Resultat</h3>
<table border="0" width="200" cellspacing="0" cellpadding="4" class="mmAdressbokTable">
	{foreach from=$topplista->getTopplista() item=placering key=key}
	{if ($key==0) }<tr class="mmAdressbokCellWhite1"><td>&nbsp;</td><td class="mmTextAlignLeft">Användarnamn</td><td class="mmTextAlignLeft">Född</td><td class="mmTextAlignLeft">Kön</td>{/if}
	<tr class="mmMailCell{if (($key%2)==0)}Green{else}White{/if}2">
		<td>#{$key+1}</td>
		<td><a href="
		{$urlHandler->getUrl(Medlem, URL_VIEW, $placering.medlem->getId())}
		">{$placering.medlem->getANamn()}</a></td>
		<td class="mmTextAlignLeft">
			{$placering.medlem->getFodelsear()}
		</td>
		<td class="mmTextAlignLeft">
			{$placering.medlem->getKon()|capitalize}
		</td>
		<td class="mmTextAlignLeft">
		{if (!in_array($placering.medlem->getId(), $nolink))}
		<img src="/img/icons/AdressbokAddIcon.gif" /> 
		<a href="{$urlHandler->getUrl("Adressbok", URL_SAVE, $placering.medlem->getId())}">Lägg till i Mina vänner</a>
		{/if}{*}{$placering.steg|nice_tal}{*}</td>
	</tr>
	{foreachelse}
	<tr>
		<td>Inga medlemmar matchade sökningen</td>
	</tr>
	{/foreach}
</table>