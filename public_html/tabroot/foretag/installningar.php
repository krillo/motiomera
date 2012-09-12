<?php
$settingshelp = Help::loadById(27);
?>
<a href="javascript:;" onclick="mm_rapportera_show_help(27,<?= $settingshelp->getSizeX() ?>,<?= $settingshelp->getSizeY() ?>,'topleft')" title="Hj&auml;lp"><img src="/img/icons/FaqCircleRed.gif" alt="Hjälp" class="mmFloatRight" /></a>
<p>
Här kan du ändra lösenordet till denna administrationssida, samt ladda upp er företagslogotyp. Den kommer sen att visas på företagssidan.
</p>


<form action="<?= $urlHandler->getUrl(Foretag, URL_SAVE, $foretag->getId()) ?>" method="post">
	<table border="0" cellpadding="0" cellspacing="0" class="motiomera_form_table">
		<?php if(isset($ADMIN) && $ADMIN->isTyp(ADMIN)){?>
		<tr>
			<th>Namn</th>
			<td><input type="text" name="namn" value="<?php if (isset($foretag)) echo $foretag->getNamn(); ?>" /></td>
		</tr>
		<tr>
			<th>Användarnamn</th>
			<td><input type="text" name="anamn" value="<?php if (isset($foretag)) echo $foretag->getANamn(); ?>" /></td>
		</tr>
		<tr>
			<th>Startdatum</th>
			<td><input type="text" name="startdatum" value="<?php if (isset($foretag)) echo $foretag->getStartDatum(); ?>" /></td>
		</tr>
		<tr>
			<th>Antal veckor</th>
			<td><input type="text" name="veckor" value="<?php if (isset($foretag)) echo $foretag->getVeckor(); ?>" /></td>
		</tr>
		<?php } ?>	
		<tr>
			<th>Lösenord</th>
			<td>
				<input type="hidden" name="andraLosen" id="andraLosen" value="0" />
				<div id="motiomera_andra_losenord_link">
					<a href="#" onclick="getById('andraLosen').value='1';getById('motiomera_andra_losenord_link').style.display='none'; getById('motiomera_andra_losenord_field').style.display='block';return false;" title="&Auml;ndra lösenordet till administrationssidan">Ändra lösenord till administrationssidan</a>
				</div>
				<div id="motiomera_andra_losenord_field">
					<input type="password" name="losenord" class="mmTextField" /><br />
					<input type="password" name="losenord2" class="mmTextField" /> <small class="grey">(Upprepa)</small><br />
					<a href="#" onclick="getById('andraLosen').value='0';getById('motiomera_andra_losenord_link').style.display='block'; getById('motiomera_andra_losenord_field').style.display='none';return false;" title="&Aring;ngra">Ångra</a>
				</div>
			</td>
		</tr>
		<tr>
			<th>Företagsbild</th>
			<td>
				<?
				global $FORETAG;
				$foretagsbildlink = CustomForetagsbild::getImgUrlIfValidFile($foretag->getId());
				if ($foretagsbildlink!=null)
					echo '<img src="'. $foretagsbildlink .'" /><br />';
				?>
				<a href="#" onclick="motiomera_laddaUppForetagsbild(<?=(isset($_GET["fid"])?$_GET["fid"]:"")?>); return false;" title="Ladda upp en logotyp">Ladda upp f&ouml;retagslogo f&ouml;r visning på f&ouml;retagssidan</a>
				<br /><br />
				F&ouml;retagsloggan måste vara i formaten <?=CustomLagbild::getAllowedFormatsString();?>  och av storlek <?=CustomLagbild::WIDTH."x".CustomLagbild::HEIGHT;?> pixlar.<br />
			</td>
		</tr>
		<tr>
			<td></td>
			<td>
				<input type="submit" value="Spara" />
				<?php if(isset($ADMIN) && $ADMIN->isTyp(ADMIN) && isset($foretag)){ ?><a href="<?= $urlHandler->getUrl(Foretag, URL_DELETE, $foretag->getId())?>" onclick="var q = confirm('Är du säker på att du vill ta bort företaget?'); return q;" title="Ta bort f&ouml;retaget">Ta bort</a><?php } ?>
			</td>
		</tr>
	</table>
</form>