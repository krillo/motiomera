<h1>Stegrapport</h1>

<table border="0" cellpadding="0" cellspacing="0" class="motiomera_form_table">
	<tr>
		<th>Inrapporterat</th>
		<td class="mmRawText">{$steg->getDatum()|nice_date}</td>
	</tr>
	<tr>
		<th>Aktivitet</th>
		<td class="mmRawText">{$aktivitet->getNamn()}</td>
	</tr>
	<tr>
		<th>{$aktivitet->getEnhet()|capitalize}</th>
		<td class="mmRawText">{$steg->getAntal()}</td>
	</tr>
</table>

<br />

{if isset($steg) && !$steg->getLast()}
<a href="{$urlHandler->getUrl(Steg, URL_DELETE, $steg->getId())}" onclick="{jsConfirm msg="Vill du verkligen ta bort denna stegrapport?"}">Ta bort</a>  | 
{/if}<a href="{$_SERVER.HTTP_REFERER}">Tillbaka</a>