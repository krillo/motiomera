<h1>Ändra påminnelse</h1>
<a href="{$urlHandler->getUrl('Paminnelser', URL_ADMIN_LIST)}">&laquo; Gå tillbaka</a><br /><br />
<script language="javascript">
{literal}
	$(document).ready(function(){paminnelseSQL()});
{/literal}
</script>
<form action="{$urlHandler->getUrl(PaminnelseSQL, URL_ADMIN_SAVE, $query->getId())}" method="POST">
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
	<input type="text" name="namn" value="{$query->getNamn()}" style="width: 98%">
</fieldset><br />
<fieldset>
	<legend>Antal dagar mellan utskick</legend>
	<input type="text" name="dagar_mellan_utskick" value="{$query->getDagarMellanUtskick()}" />
</fieldset><br />
<fieldset>
	<legend>SQL-fråga</legend>
	<textarea name="query" style="width: 100%; height: 200px;">{$query->getQuery()}</textarea>
</fieldset><br />
<div id="shortcuts" style="position: absolute; left: 590px; width: 200px;">
	<h2>Genvägar</h2>
	<ul>
	</ul>
</div>
<fieldset>
	<legend>E-postmeddelandets ämnesrad (<em>Data infogas som #namn#</em>)</legend>
	<input type="text" name="titel" style="width: 98%" value="{$query->getTitel()}" />
</fieldset><br />
<fieldset>
	<legend>E-postmeddelandets textmall (<em>Data infogas som #namn#</em>)</legend>
	<textarea name="inre_mall" id="inre_mall" style="width: 100%; height: 200px;">{$query->getInreMall()}</textarea>
</fieldset><br />
<fieldset>
	<legend>Yttre mall</legend>
	<select name="meddelande_id">
		<option value="">-- Inget val --</option>
		{foreach from=$yttre_mallar item=mall}
		<option value="{$mall->getId()}"{if $mall->getId() == $query->getMeddelandeId()} selected="selected"{/if}>{$mall->getNamn()}</option>
		{/foreach}
	</select>
</fieldset><br />
{if $superAdmin}
<input type="submit" value="Spara ändring &raquo;" />{else}<span class="mmPaminnelseNone">Du har inte rättighet att spara!</span>{/if}&nbsp; &nbsp;<input type="reset" value="Återställ" />
</form>
