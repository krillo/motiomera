<?php

header("Content-Type: text/html; charset=utf-8");

require_once($_SERVER["DOCUMENT_ROOT"]."/php/init.php");
$adressbok = Adressbok::loadByMedlem($USER);

if(isset($_GET["showAddLink"]))
	$notin = $adressbok->listKontakterId();
else
	$notin = null;

$medlemmar = Medlem::listBySokord(utf8_encode($_GET["sokord"]), $notin);

$nolink = array();

$adressbok = Adressbok::loadByMedlem($USER);
$kontakter = $adressbok->listKontakter();

foreach($kontakter as $kontakt){
	$nolink[] = $kontakt->getId();
}

if(count($medlemmar) == 0){
	echo "<p>Inga medlemmar matchade sökordet.</p>";
}else{
	
	echo '<table border="0" cellspacing="2" cellpadding="4" class="mmAdressbokTable">';
	$i = 0;
	foreach($medlemmar as $medlem){
	?>
	<tr class="mmAdressbokCell<?php if($i==0){ echo "White"; $i = 1;}else{ echo "Blue"; $i = 0;} ?>1">
		<td style="width: 155px;" class="mmCell1">
		<img src="<?= $medlem->getAvatar()->getUrl() ?>" class="mmAvatarMini" />
		<a href="<?= $urlHandler->getUrl("Medlem", URL_VIEW, $medlem->getId()) ?>" title="Visa profil f&ouml;r <?= $medlem->getANamn() ?>"><?= $medlem->getANamn() ?></a></td>
		<?php  if(!in_array($medlem->getId(), $nolink)){ ?>
			<td class="mmCell2">
				<img src="/img/icons/AdressbokAddIcon.gif" />
				<a href="<?= $urlHandler->getUrl("Adressbok", URL_SAVE, $medlem->getId()) ?>" title="L&auml;gg till denna person i adressboken">Lägg till i Mina vänner</a>
			</td>
		<?php }else{ ?>
			<td></td>
		<?php } ?>
		<!-- td>
		<?=$medlem->getFNamn();?> <?=$medlem->getENamn();?> <?=$medlem->getEpost(); ?>
		</td-->
	</tr>
	<?php } ?>
</table>
<?php } ?>