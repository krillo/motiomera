<h1>{if isset($dialekt)}Redigera ljudfil{else}Ny ljudfil{/if}</h1>

{if isset($dialekt)}
<p><a href="{$urlHandler->getUrl(Kommundialekt, URL_ADMIN_DELETE, $dialekt->getId())}" onclick="{jsConfirm msg="Är du säker på att du vill ta bort den här ljudfilen?"}">Ta bort</a></p>
{/if}
<form action="{$urlHandler->getUrl(Kommundialekt, URL_ADMIN_SAVE)}" method="post">
	{if isset($dialekt)}
	<input type="hidden" name="id" value="{$dialekt->getId()}" />
	{else}
	<input type="hidden" name="kommun_id" value="{$_GET.kid}" />
	{/if}
	
	<table border="0" cellpadding="0" cellspacing="0" class="motiomera_form_table">
		<tr>
			<th>Kommun</th>
			<td class="mmRawText"><a href="{$urlHandler->getUrl(Kommun, URL_EDIT, $kommun->getId())}">{$kommun->getNamn()}</a></td>
		</tr>
		<tr>
			<th>Kön</th>
			<td>
				{mm_html_options name=kon options=$opt_kon selected=$sel_kon}
				
			</td>
		</tr>
		<tr>
			<th>Ålder</th>
			<td>
				{mm_html_options name=alder options=$opt_alder selected=$sel_alder}
				
			</td>
		</tr>
		<tr>
			<th>URL</td>
			<td><input type="text" class="mmWidthHundraProcent" name="url" value="{if isset($dialekt)}{$dialekt->getUrl()}{/if}" /></td>
		</tr>
		{if isset($dialekt) && $dialekt->getMedlem()}
		{assign var=medlem value=$dialekt->getMedlem()}
		<tr>
			<th>Uppladdad av</td>
			<td class="mmRawText"><a href="{$urlHandler->getUrl(Medlem, URL_VIEW, $medlem->getId())}">{$medlem->getANamn()}</td>
		</tr>
		{/if}
		{if isset($dialekt)}
		<tr>
			<th>Godkänd</td>
			<td class="mmRawText">
				{if $dialekt->getGodkand()}
				<a href="{$urlHandler->getUrl(Kommundialekt, URL_ADMIN_UNAPPROVE, $dialekt->getId())}" title="Klicka för att ta bort godkännande"><span class="mmGreen">Ja</span>
				{else}
				<a href="{$urlHandler->getUrl(Kommundialekt, URL_ADMIN_APPROVE, $dialekt->getId())}" title="Klicka för att godkänna"><span class="mmRed">Nej</span></a>
				{/if}

			</td>
		</tr>
		<tr>
			<th>Ljud</th>
			<td>
				
				<object type="application/x-shockwave-flash" data="/flash/soundPlayer.swf?url={$dialekt->getUrl()}" width="23" height="23" id="kommundialekt" align="middle">
						<param name="movie" value="/flash/soundPlayer.swf?url={$dialekt->getUrl()}" />
						<param name="quality" value="high" />
						<param name="allowFullScreen" value="false" />
						<param name="scale" value="showall" />
				</object>
				
			</td>
		</tr>
		{/if}
		<tr class="mmLastRow">
			<td></td>
			<td><input type="submit" value="Spara" /></td>
		</tr>

	</table>
	
</form>