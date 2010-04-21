<?php
global $USER, $urlHandler;

$adressbok = Adressbok::loadByMedlem($USER);
$kontakter = $adressbok->listKontakter();

?>
<br />
<table border="0" cellspacing="2" cellpadding="4" class="mmAdressbokTable">
<?php if(empty($kontakter)){ ?>

	<tr>
		<td>Du har inga kontaker registrerade än</td>
	</tr>

<?php }else{ ?>

	<?php foreach($kontakter as $kontakt){ ?>
	<tr class="mmAdressbokCell<?php if($i==0){ echo "White"; $i = 1;}else{ echo "Blue"; $i = 0;} ?>1">
		<td class="mmCell1">
			<img src="<?= $kontakt->getAvatar()->getUrl(); ?>" alt="" class="mmAvatarMini" />
			<a href="<?= $urlHandler->getUrl("Medlem", URL_VIEW, $kontakt->getId()) ?>" title="Profil f&ouml;r <?= $kontakt->getANamn(); ?>"><?= $kontakt->getANamn() ?></a>
		</td>
		<td class="mmCell1">
			<img src="/img/icons/SkickaMeddelandeIcon_BlueBG.gif" alt="Skicka meddelande" />
			<a href="#" onclick="motiomera_mail_send('<?= $kontakt->getId() ?>'); return false;" title="Skicka ett meddelande till den h&auml;r personen">
				Skicka ett meddelande
			</a>
		</td>
		<td class="mmWidth165 mmCell2">
			<img src="/img/icons/AdressbokDeleteIcon2_BlueBG.gif" alt="" />
			<a href="<?= $urlHandler->getUrl("Adressbok", URL_DELETE, $kontakt->getId()) ?>" title="Ta bort den h&auml;r personen ur adressboken">
				Ta bort som vän
			</a>
		</td>
	</tr>
	<?php } ?>

<?php } ?>
</table>