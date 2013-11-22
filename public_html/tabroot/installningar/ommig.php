<?php global $USER, $opt_kommuner, $sel_kommun, $visningsbild, $unapprovedVisningsbild, $urlHandler; ?>
	<a href="profil.php?mid=<?= $USER->getId() ?>" title="Visa profilen s&arin;g som andra ser den">Visa min profil som andra ser den</a>
	<table border="0" cellspacing="0" cellpadding="0" class="motiomera_form_table">
		<tr>
			<th>Förnamn</th>
			<td><input type="text" name="fnamn" value="<?= $USER->getFNamn() ?>" class="mmTextField" /></td>
		</tr>
		<tr>
			<th>Efternamn</th>
			<td><input type="text" name="enamn" value="<?= $USER->getENamn() ?>" class="mmTextField" /></td>
		</tr>
		<tr>
			<th>Födelseår</th>
			<td>
				<select name="fodelsear">
				<?php
				
					$ar = array(""=>"Välj...");
					for($i = 2008; $i > 1899; $i--)
						$ar[$i] = $i;
					foreach($ar as $key=>$tar){
				?>	
				<option value="<?= $key ?>"<?= ($key == $USER->getFodelsear()) ? ' selected="selected"' : ""; ?>><?= $tar ?></option>
				<?php } ?>				
				</select>
			</td>
		</tr>
		<tr>
			<th>Min blogg</th>
			<th><input type="text" name="rssUrl" value="<?php echo $USER->getRssUrl() ?>" /><br /><i>Här kan du länka in rss flöden från din blogg, Om du inte har någon blogg kan du skapa en på <a href="http://www.blogg.se" title="blogg.se" />blogg.se</a></i></th>
		</tr>
		<tr>
			<th>Beskrivning</th>
			<td><textarea name="beskrivning" cols="40" rows="8" class="mmFontSizeNioNollProcent" ><?= $USER->getBeskrivning() ?></textarea></td>
		</tr>
		<tr>
			<th>Hemort</th>
			<td>
				<select name="kid">
					<?php foreach($opt_kommuner as $key=>$option){ ?>					
					<option value="<?= $key?>"<?= ($key == $sel_kommun) ? ' selected="selected"' : '';?>><?= $option ?></option>
					<?php } ?>
				</select>
			</td>
		</tr>
		<tr>
			<th>Figur</th>
			<td><img id="mmInstallningarAvatar" src="<?= $USER->getAvatar()->getUrl() ?>" alt="" class="mmAvatar" /> <a href="#" onclick="motiomera_valjAvatar(); return false;" title="V&auml;lj en avatar">Välj...</a></td>
		</tr>
	</table>
	
	<h2 class="mmMarginBottom mmMarginTop">Visningsbild</h2>
	
	<div class="mmFloatLeft marginRight20">
		<img src="<?= $visningsbild->getUrl() ?>" alt="Visningsbild" class="motiomera_visningsbild" id="mmInstallningarVisningsbild" />
	</div>
	<a href="#" onclick="motiomera_laddaUppVisningsbild(); return false;" title="Ladda upp en bild">Ladda upp bild</a><br />
