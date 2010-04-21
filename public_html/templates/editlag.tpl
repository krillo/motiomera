<h1>{if isset($editlag)}{$editlag->getNamn()}{else}Nytt lag{/if}</h1>
<p>
	<a href="{$urlHandler->getUrl(Foretag, URL_EDIT, $lagarr)}">Tillbaka</a>
	{if isset($editlag)} | <a href="{$urlHandler->getUrl(Lag, URL_VIEW, $editlag->getId())}">Gå till lagsidan</a>{/if}
</p>

<form action="{if isset($fid)}{$urlHandler->getUrl(Lag, URL_ADMIN_SAVE, $fid)}{else}{$urlHandler->getUrl(Lag, URL_SAVE, $lagid)}{/if}" method="post" onsubmit="return motiomera_validateSkapaLag(this);">
	
	
	<table border="0" class="motiomera_form_table">
		<tr>
			<th>Namn</th>
			<td><input type="text" name="namn" value="{if isset($editlag)}{$editlag->getNamn()}{/if}" /></td>
		</tr>
		{if isset($editlag)}
		<tr>
			<th>Bild</th>
			<td>
				{if $editlag->getBildFullUrl() neq ""}
					<img src="{$editlag->getBildFullUrl()}" id="mmLagAvatar" alt="" /><br />
				{else}
					<img src="{$editlag->getDefaultBildFullUrl()}" id="mmLagAvatar" alt="" />
				{/if}
				<br /><a href="#" onclick="motiomera_valjLagAvatar({$editlag->getId()}); return false;">Välj lagbild</a>&nbsp;&nbsp;
				<a href="#" onclick="motiomera_laddaUppLagbild({$editlag->getId()}); return false;">Ladda upp lagbild</a><br /><br />
				Vid uppladdning av egna lagbilder måste bilderna vara av format jpg, png, gif och av storlek 50x50 pixlar.<br />
			</td>
		</tr>
		{/if}
		<tr class="mmLastRow">
			<th></th>
			<td>
				<input type="submit" value="Spara" />
				
				{if isset($editlag)}
				<input type="button" value="Ta bort lag" onclick="var q=confirm('Är du säker på att du vill ta bort det här laget?'); if(q) window.location='{$urlHandler->getUrl("Lag", URL_DELETE, $editlag->getId())}';" />
				{/if}	
			</td>
		</tr>
	</table>
</form>

{if isset($editlag)}
<p><b>Medlemmar</b></p>

<table border="0" cellpadding="0" cellspacing="0">
	{foreach from=$medlemmar item=medlem}
	<tr>
		<td><a href="{$urlHandler->getUrl(Medlem, URL_VIEW, $medlem->getId())}">{$medlem->getFNamn()}&nbsp;{$medlem->getENamn()}</a></td>
		<td><small><a href="/actions/removemedlemfromlag.php?mid={$medlem->getId()}&amp;lid={$editlag->getId()}">Ta bort från lag</a></small></td>
	</tr>
	{foreachelse}
	<tr>
		<td>Inga medlemmar ännu.</td>
	</tr>
	{/foreach}
</table>

{if count($opt_invitable) > 0}
<form action="/actions/addmedlemtolag.php" method="post">
	<p><b>Lägg till i lag</b></p>
	<p>
	<input type="hidden" name="lid" value="{$editlag->getId()}" />
	{html_checkboxes name=mid options=$opt_invitable}
	<input type="submit" value="Lägg till" />
	</p>
</form>
{/if}
{/if}
