<h1>Skapa påminnelse</h1>
<a href="{$urlHandler->getUrl('Paminnelser', URL_ADMIN_LIST)}">&laquo; Gå tillbaka</a><br /><br />
<script language="javascript">
{literal}
	$(document).ready(function(){paminnelseSQL()});
{/literal}
</script>
<form action="{$urlHandler->getUrl(PaminnelseSQL, URL_ADMIN_SAVE)}" method="POST">
	<div style="position: absolute; left: 590px; width: 150px;">
		<h2>Medlemslista</h2>
		<p>SQL-scriptet måste dubbelkolla mot #medlemslistan# för att fungera.</p>
		<div id="medlemslistaSet" style="color: #0a0; display: none;">
			Medlemslistan är inkluderad.
		</div>
		<div id="medlemslistaUnset" style="color: #C00">
			Medlemslistan saknas!<br />
			Lägg till liknande:<br />
			... WHERE mm_medlem.id IN (#medlemslista#) AND ...
		</div>
		<h2>Dubletter</h2>
		<p>Om det finns dubletter kommer bara den sista av flera att användas</p>
		<div id="doubles" style="color: #C00"></div>
	</div>
<fieldset>
	<legend>Namn / Beskrivning</legend>
	<input type="text" name="namn" style="width: 98%">
</fieldset><br />
<fieldset>
	<legend>Antal dagar mellan utskick</legend>
	<input type="text" name="dagar_mellan_utskick" />
</fieldset><br />
<fieldset>
	<legend>SQL-fråga</legend>
	<textarea name="query" style="width: 100%; height: 200px;"></textarea>
</fieldset><br />
<div id="shortcuts" style="position: absolute; left: 590px; width: 200px;">
	<h2>Genvägar</h2>
	<ul>
	</ul>
</div>
<fieldset>
	<legend>Titel</legend>
	<input type="text" name="titel" style="width: 98%" />
</fieldset><br />
<fieldset>
	<legend>Inre mall</legend>
	<textarea name="inre_mall" id="inre_mall" style="width: 100%; height: 200px;"></textarea>
</fieldset><br />
<fieldset>
	<legend>Yttre mall</legend>
	<select name="meddelande_id">
		<option value="">-- Inget val --</option>
		{foreach from=$yttre_mallar item=mall}
		<option value="{$mall->getId()}">{$mall->getNamn()}</option>
		{/foreach}
	</select>
</fieldset><br />
<input type="submit" value="Spara påminnelsen &raquo;" />&nbsp; &nbsp;<input type="reset" value="Återställ" />
</form>
