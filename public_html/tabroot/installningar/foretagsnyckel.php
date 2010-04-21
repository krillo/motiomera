<?php global $USER?>
	<table border="0" cellspacing="0" cellpadding="0" class="motiomera_form_table">
		<tr>
			<th>Företagsnyckel</th>
			<td>
				Du som har fått MotioMera-medlemskapet via ditt företag anmäler dig här. Fyll i den kod du fick i välkomstbrevet och klicka på Spara inställningar.<br /><br />
				<?php
					$nyckel = $USER->getForetagsnyckel(true);
				?>
				<input type="hidden" name="foretagsnyckelOld" value="<?= $nyckel ?>" />
				<input type="text" name="foretagsnyckel" <?= ($nyckel) ? ' value="' . $nyckel . '" disabled="disabled"' : ''; ?> class="mmTextField" onfocus="getById('mmForetagsnyckelError').style.display='none';" onblur="if(this.value != '') mm_ajaxValidera('mmForetagsnyckelError', 'foretagsnyckel', this.value);" /> <span class="mmRed hide" id="mmForetagsnyckelError">Ogiltig företagsnyckel</span>
			</td>
		</tr>
		
	</table>