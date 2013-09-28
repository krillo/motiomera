<h1>Inställningar</h1>

<form action="{$urlHandler->getUrl(Medlem, URL_SAVE)}" method="post" onsubmit="return motiomera_validateInstallningarForm(this); return false;">
	<input type="hidden" id="mmInstallningarFlik" name="tab" value="0" />
	
	{$tabs->printTabBox()}

  <br/>
	<table border="0" cellspacing="0" cellpadding="0" class="motiomera_form_table">
		<tr>
			<th><input type="image" src="/img/icons/SparaInstallningarIcon.gif" alt="Spara inställningar" /></th>
		</tr>
	</table>

</form>