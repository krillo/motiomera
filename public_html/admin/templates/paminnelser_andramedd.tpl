<h1>Ändra yttre mall</h1>
<a href="{$urlHandler->getUrl('Paminnelser', URL_ADMIN_LIST)}">&laquo; Gå tillbaka</a><br /><br />
<form action="{$urlHandler->getUrl(PaminnelseMeddelande, URL_ADMIN_SAVE, $meddelande->getId())}" method="POST">
<fieldset>
	<legend>Namn / Beskrivning</legend>
	<input type="text" name="namn" style="width: 98%" value="{$meddelande->getNamn()}">
</fieldset>
<br />
<fieldset>
	<legend>Mallinnehåll (PHP-script)</legend>
	<textarea name="mall" style="width: 100%; height: 200px;">{$meddelande->getMall()}</textarea>
</fieldset><br />
<input type="submit" value="Spara påminnelsen &raquo;" />&nbsp; &nbsp;<input type="reset" value="Återställ" />
</form>
