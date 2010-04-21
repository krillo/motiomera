<?php global $USER?>
	<table border="0" cellspacing="0" cellpadding="0" class="motiomera_form_table">
		<tr>
		<th></th>
		<th>Välj svarsalternativ</th>
		<th>Skriv ditt eget svar</th>
		</tr>
<?php /*DOLT /JL
		<tr>
			<th>Kundnummer</th>
			<td>
				<input type="text" name="customerId" value="<?= $USER->getCustomerId() ?>" class="mmTextField" />
			</td>
		</tr>
*/?>		<?php
		
		$profilDatas = ProfilData::listAll();
		
		foreach($profilDatas as $key=>$profilData) {
		
			$profilDataVals = ProfilDataVal::listByprofilData($profilData);
			
			$vald_profilDataVal = $USER->getProfilDataVal($profilData->getId());
			$profilDataText = $USER->getProfilDataText($profilData->getId());
			?>
			<tr>
				<th><?=$profilData->getNamn();?></th>
				<td><select name="profilData[<?=$profilData->getId()?>]"<?=strlen($profilDataText)>0?' disabled':''?> id="profilDataSelect_<?=$profilData->getId()?>">
				<option value="0"> --- Ej valt --- </option>
				<?php
				foreach($profilDataVals as $pkey=>$profilDataVal) {
					?><option value="<?=$profilDataVal->getId()?>"<?=$profilDataVal->getId()==$vald_profilDataVal?'selected="selected"':"";?>><?=$profilDataVal->getVarde()?></option><?
				}
				?>
				</select></td>
				<? if (strlen($profilDataText)>0): ?>

				<? endif; ?>
				<td><input type="text" name="profilDataFritext[<?=$profilData->getId()?>]" maxlength="40" value="<?=$profilDataText?>" onkeyup="if (this.value.length == 0){document.getElementById('profilDataSelect_<?=$profilData->getId()?>').disabled='';} else {document.getElementById('profilDataSelect_<?=$profilData->getId()?>').disabled='true'; document.getElementById('profilDataSelect_<?=$profilData->getId()?>').selectedIndex=0; };" /></td>
			</tr>
				<?
		}
			
		?>
	</table>