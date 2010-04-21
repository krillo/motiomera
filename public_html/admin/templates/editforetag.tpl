<h1>Redigera företag</h1>
<p>
	<a href="{$urlHandler->getUrl(Foretag, URL_ADMIN_LIST)}">Tillbaka</a> |
	<a href="{$urlHandler->getUrl(Foretag, URL_VIEW, $foretag->getId())}">Visa företagsprofil</a>
</p>
<form action="{$urlHandler->getUrl(Foretag, URL_ADMIN_SAVE)}" method="post">
	<input type="hidden" name="fid" value="{$foretag->getId()}">
	<table border="0" cellpadding="0" cellspacing="0" class="motiomera_form_table">
		<tr>
			<th>Namn</th>
			<td><input type="text" value="{$foretag->getNamn()}" name="namn" /></td>
		</tr>
		<tr>
			<th>Startdatum</th>
			<td><input type="text" value="{$foretag->getStartdatum()}" name="startdatum" /></td>
		</tr>
		<tr class="mmLastRow">
			<td></td>
			<td><input type="submit" value="Spara" /></td>
		</tr>
	</table>	
</form>