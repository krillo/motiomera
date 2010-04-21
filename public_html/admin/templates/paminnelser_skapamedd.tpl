<h1>Skapa yttre mall</h1>
<a href="{$urlHandler->getUrl('Paminnelser', URL_ADMIN_LIST)}">&laquo; Gå tillbaka</a><br /><br />
<form action="{$urlHandler->getUrl(PaminnelseMeddelande, URL_ADMIN_SAVE)}" method="POST">
<fieldset>
	<legend>Namn / Beskrivning</legend>
	<input type="text" name="namn" style="width: 98%">
</fieldset>
<br />
<fieldset>
	<legend>Mallinnehåll (PHP-script)</legend>
	<textarea name="mall" style="width: 100%; height: 200px;">echo $title . '<br />';
echo $content . '<br />';</textarea>
</fieldset><br />
<input type="submit" value="Spara påminnelsen &raquo;" />&nbsp; &nbsp;<input type="reset" value="Återställ" />
</form>
