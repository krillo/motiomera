<?php 
global $urlHandler, $ADMIN, $foretag;
//$helper = new TabBox;
$opt_kommuner = Kommun::listField("namn");
$laghelp = Help::loadById(26);

?>
<a href="javascript:;" onclick="mm_rapportera_show_help(26,<?= $laghelp->getSizeX() ?>,<?= $laghelp->getSizeY() ?>,'topleft')" title="Hj&auml;lp"><img src="/img/icons/FaqCircleRed.gif" alt="Hjälp" class="mmFloatRight" /></a>
<p>
För att göra det enkelt för dig delar MotioMeras system automatiskt in deltagarna i lag. 
Men om du istället helt själv vill bestämma hur lagen ska se ut är det möjligt. 
Nedan kan du ta bort eller lägga till deltagare i lagen och du kan också skapa helt nya lag. 
Du kan också byta namn på lagen och lägga till egna lagsymboler om du vill.

<?php
$withoutLag=$foretag->getMembersWithoutLag();
if($withoutLag>0) 
{
?>
<br /><br />
<span class="mmRed">
	OBS! <?=$withoutLag?> deltagare är ännu ej indelad<?=$withoutLag>1?'e':'';?> i något lag.
</span><br />
<?php
}
?>

</p>

<?php 
	global $FORETAG;
	$lag = $foretag->listLag();
?>
<?php //if(Lag::kanSkapaLag($foretag)){ ?>
<table border="0" cellpadding="0" cellspacing="0">

	<tr class="mmHeight20">
		<td><strong>Lag</strong></td>
		<td>&nbsp;</td>
		<td>&nbsp;</td>
		<td>&nbsp;</td>	
		<td><strong>Hantera</strong></td>
		<td>&nbsp;&nbsp;&nbsp;</td>	
		<td><strong>Ta bort</strong></td>		
	</tr>
	<?php foreach($lag as $thislag){ 
		$antalM = $thislag->getAntalMedlemmar();
	?>
	<tr>
		<td class="mmPaddingRight30"><a href="<?= $urlHandler->getUrl("Lag", URL_VIEW, $thislag->getId())?>" rel="alternate" title="Laget <?=$thislag->getNamn();?>"><?= $thislag->getNamn() ?></a></td>
		<td>&nbsp;</td>
		<td class="mmWidthHundra"><?= $antalM; ?> medlem<?= ($antalM == 1) ? "" : "mar"; ?>&nbsp;</td>
		<td>&nbsp;&nbsp;</td>
		<td class="center"><a href="<?= $urlHandler->getUrl("Lag", URL_EDIT, $thislag->getId())?>" rel="alternate" title="&Auml;ndra laget"><img src="../img/icons/edit.png" alt="Ändra" /></a></td>
		<td>&nbsp;</td>	
		<td class="center"><a href="<?= $urlHandler->getUrl("Lag", URL_DELETE, $thislag->getId())?>" rel="alternate" title="Ta bort laget"><img src="../img/icons/delete.png" alt="Ta Bort" /></a></td>		
	</tr>
	<?php } ?>
	<tr>
		<td><hr size="1" noshade="noshade" /></td><td><hr size="1" noshade="noshade" /></td><td><hr size="1" noshade="noshade" /></td><td><hr size="1" noshade="noshade" /></td><td><hr size="1" noshade="noshade" /></td><td><hr size="1" noshade="noshade" /></td><td><hr size="1" noshade="noshade" /></td>
	</tr>
	
	<tr>
		<td><a href="<?= $urlHandler->getUrl("Lag", URL_CREATE, $foretag->getId()) ?>" title="Skapa ett nytt lag">Skapa nytt lag</a></td>
	</tr>
	<tr>
		<td><a href="<?= $urlHandler->getUrl("Foretag", URL_DELETE_ALL, $foretag->getId()) ?>" rel="alternate" title="Ta bort alla lag">Ta bort alla lag</a></td>
	</tr>
	<tr>
		<td><a href="<?= $urlHandler->getUrl("Lag", URL_RANDOMIZE_TEAMS, $foretag->getId()) ?>" rel="alternate" title="Slumpa nya lag">Slumpa nya lag</a></td>
	</tr>

	
</table>

<?php //} ?>
