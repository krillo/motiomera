<?php global $USER, $opt_access, $sel_access, $urlHandler; ?>
	<table border="0" cellspacing="0" cellpadding="0" class="motiomera_form_table">
	<tr>
		<td><a onclick="if(confirm('Du kommer att ta bort din användare om du godkänner detta!')){}else{return false;}" href="<?= $urlHandler->getUrl(Medlem, URL_DELETE, $USER->getId())?>">Avregistrera dig</a></td>
	</tr>
		<tr>
			<th>E-postadress</th>
			<td><input type="text" name="epost" value="<?= $USER->getEpost()?>" class="mmTextField" /></td>
		</tr>
		<tr>
			<th>Profil&aring;tkomst</th>
			<td>
				<select name="atkomst">
					<?php foreach($opt_access as $value=>$option){?>
						<option value="<?= $value ?>"<?= ($sel_access == $value) ? ' selected="selected"' : '';?>><?= $option ?></option>
					<?php } ?>
				</select>
			</td>
		</tr>
		<tr>
			<th>Motiomeramail</th>
			<td>
				<select name="blockmail">
					<option value="false"<? echo $USER->getMotiomeraMailBlock()=='false'?' selected':''; ?>>Ta emot Motiomermail från alla</option>
					<option value="true"<? echo $USER->getMotiomeraMailBlock()=='true'?' selected':''; ?>>Ta bara emot Motiomeramail från mina vänner</option>
				</select>
		</tr>
		<tr>
			<th>Nollställning</th>
			<td>
				<a href="<? echo $urlHandler->getUrl(Medlem, URL_RESET_STEG); ?>" onclick="return confirm('Vill du verkligen nollställa dina rapporterade steg och din rutt?');">Nollställ steg och rutt och välj ny startkommun</a>
		</tr>
		<tr>
			<th>L&ouml;senord</th>
			<td>
				<input type="hidden" name="andraLosen" id="andraLosen" value="0" />
				<div id="motiomera_andra_losenord_link">
					<a href="#" onclick="getById('andraLosen').value='1';getById('motiomera_andra_losenord_link').style.display='none'; getById('motiomera_andra_losenord_field').style.display='block';return false;" title="&Auml;ndra ditt l&ouml;senord">&Auml;ndra l&ouml;senord</a>
				</div>
				<div id="motiomera_andra_losenord_field">
					<input type="password" name="losen" class="mmTextField" /><br />
					<input type="password" name="losen2" class="mmTextField" /> <small class="grey">(Upprepa)</small><br />
					<a href="#" onclick="getById('andraLosen').value='0';getById('motiomera_andra_losenord_link').style.display='block'; getById('motiomera_andra_losenord_field').style.display='none';return false;" title="&Aring;ngra">&Aring;ngra</a>
				</div>
			</td>
		</tr>
		<tr>
			<th>Skicka påminnelsemail</th>
			<td>
				<?php
				$notifications = Paminnelse_sql::listAll();
				$aktiva = Paminnelse_sql::getAktivaIDn($USER);
				foreach ($notifications as $key => $notification)
				{
					?>
					<input type="checkbox" name="notifications[<?php echo $notification->getId() ?>]" id="notification_<?php echo $notification->getId() ?>"<?php if (in_array($notification->getId(), $aktiva)) { ?> checked="checked"<?php } ?>> <label for="notification_<?php echo $notification->getId(); ?>"><?php echo $notification->getNamn(); ?></label><br />
					<?php
				}
				?>
			</td>
		</tr>
	</table>
