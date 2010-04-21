<h1>Steg</h1>

<p>
	<strong>Dina stegrapporter för {$_GET.datum|nice_date:"j F"}</strong><br />
	<table border="0" cellspacing="0" cellpadding="0">
	{foreach from=$stegList item=steg name=stegrapport_foreach}
		{assign var=aktivitet value=$steg->getAktivitet()}
		<tr>
			<td class="mmStegAktivitetTd">
				<a href="{$urlHandler->getUrl(Steg, URL_EDIT, $steg->getId())}">{$aktivitet->getNamn()}</a>
			</td>
			<td class="mmFontSizeAttaNollProcent">
				{$steg->getAntal()} {$aktivitet->getEnhet()}{if $aktivitet->getNamn() neq "Steg"} ({$steg->getSteg()} steg){/if}. Inrapporterat {$steg->getDatum()|nice_date:"j F"}.</small><br />
			</td>
		</tr>
	{foreachelse}
	
	Inga stegrapporter ännu.		
	
	{/foreach}
	</table>
</p>