<h1>{if isset($fraga)}Redigera fråga{else}Ny fråga{/if}</h1>
{if isset($fraga)}
{if !$fraga->getRattSvar()}
<p style="color: #800">Denna fråga har inget rätt svar markerat.</p>
{/if}
{/if}
<form action="{$urlHandler->getUrl(QuizFraga, URL_ADMIN_SAVE, $fragaId)}" method="post">

	<input type="hidden" name="kid" value="{$kommun->getId()}" />
	<input tyoe="text" size="50" name="fraga" value="{if isset($fraga)}{$fragarad}{/if}" />

	<input type="submit" value="Spara" />
	
</form>

{if isset($fraga)}
<h2>Alternativ</h2>
<form action="{$urlHandler->getUrl(QuizAlternativ, URL_ADMIN_SAVE)}" method="post">
	<input type="hidden" name="fid" value="{$fraga->getId()}" />
	<input type="text" size="30" name="text" />
	<input type="submit" value="Lägg till" />
	<input type="checkbox" name="rattSvar" value="true" /> Rätt svar
</form>
<table border="0" cellpadding="0" cellspacing="0">

{foreach from=$alternativ item=thisAlternativ}
<tr>
	<td style="width: 140px;">{$thisAlternativ->getText()}</td>
	<td style="padding-right: 10px;">{if $thisAlternativ->isRattSvar()}Rätt svar{/if}</td>
	<td><a href="{$urlHandler->getUrl(QuizAlternativ, URL_ADMIN_DELETE, $thisAlternativ->getId())}">Ta bort</a></td>
</tr>
{/foreach}
</table>
{/if}
<p>
	<a href="{$urlHandler->getUrl(QuizFraga, URL_ADMIN_LIST, $kommun->getId())}">Tillbaks</a>
</p>