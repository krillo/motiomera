<?php 
if(!defined("INIT"))
	include $_SERVER["DOCUMENT_ROOT"]  . "/php/init.php";	
?>
<br />
<table border="0" cellpadding="0" cellspacing="0">

<?php
global $USER, $urlHandler, $adressbok;

$olastaforfragningar = $adressbok->listForfragningar(Adressbok::FF_OLASTA);
$lastaforfragningar = $adressbok->listForfragningar(Adressbok::FF_LASTA);
?>
<?php if(count($olastaforfragningar) > 0){ ?>
	<tr>
		<th colspan="2">
			Olästa förfrågningar
		</td>
	</tr>
<?php }
foreach($olastaforfragningar as $medlem){
?>
	<tr>
		<td class="mmWidthTvaHundraPixlar">
			<a href="<?= $urlHandler->getUrl("Medlem", URL_VIEW, $medlem->getId()) ?>" title="Visa profil f&ouml;r <?= $medlem->getANamn() ?>"><?= $medlem->getANamn() ?></a>
		</td>
		<td>
		<a href="<?= $urlHandler->getUrl("Adressbok", URL_SAVE, $medlem->getId()) ?>" title="L&auml;gg till i adressboken">L&auml;gg till</a>
		</td>
	</tr>
	<?php if(count($lastaforfragningar) > 0){ ?>
	<tr>
		<td colspan="2">&nbsp;</td>
	</tr>
	<tr>
		<th colspan="2">
			Lästa förfrågningar
		</td>
	</tr>
	<?php } ?>
<?php } ?>

<?php foreach($lastaforfragningar as $medlem){ ?>
	<tr>
		<td class="mmWidthTvaHundraPixlar">
			<a href="<?= $urlHandler->getUrl("Medlem", URL_VIEW, $medlem->getId()) ?>" title="Visa profil f&ouml;r <?= $medlem->getANamn() ?>"><?= $medlem->getANamn() ?></a>
		</td>
		<td>
		<a href="<?= $urlHandler->getUrl("Adressbok", URL_SAVE, $medlem->getId()) ?>" title="L&auml;gg till i adressboken">L&auml;gg till</a>
		</td>
	</tr>	

<?php } ?>

</table>
<form action="/actions/sendinvitemail.php" method="post">
	<h1>Bjud in dina vänner!</h1>
	<p>
		Vill du bjuda in dina vänner till MotioMera? Skriv vännernas
		e-postadresser nedan och klicka på "<i>Skicka inbjudan</i>".
	</p>
	<fieldset>
		<legend>Epost-adresser</legend>
		<p><input type="text" name="email[]" value="" id="email[]"></p>
		<p><input type="text" name="email[]" value="" id="email[]"></p>
		<p><input type="text" name="email[]" value="" id="email[]"></p>
		<p><input type="text" name="email[]" value="" id="email[]"></p>
		<p><input type="text" name="email[]" value="" id="email[]"></p>
	</fieldset>
	<p>
		<input type="submit" value="Skicka inbjudan!">
	</p>
</form>
<?php
	$adressbok->clearForfragningar();
?>