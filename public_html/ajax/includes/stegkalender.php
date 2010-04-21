<?php


require_once($_SERVER["DOCUMENT_ROOT"]."/php/init.php");
$medlem = Medlem::loadById($_POST["mid"]);

$steg = Steg::listByMedlem($medlem);


setlocale(LC_TIME, "sv_SE.ISO8859-1");

?>

<h3><?php echo date("j", strtotime($_POST["datum"])) ?> <?php echo Misc::getManadFromDate($_POST["datum"]) ?> <?php echo date("Y", strtotime($_POST["datum"])) ?></h3>
<table border="0" cellpadding="0" cellspacing="0">
	<tr>
		<th class="mmWidthAttaNoll">Aktivitet</th>
		<th>Antal</th>
	</tr>
<?
foreach($steg as $thisSteg){
	if(substr($thisSteg->getDatum(), 0, 10) == $_POST["datum"]){
	?>
		<tr>
			<td><?php echo $thisSteg->getAktivitet()->getNamn() ?></td>
			<td><?php echo number_format($thisSteg->getAntal(), 0, "", " ") ?> <?php echo $thisSteg->getAktivitet()->getEnhet() ?><?php if ($thisSteg->getAktivitet()->getEnhet() == 'minuter') { ?> (<?php echo $thisSteg->getAktivitet()->getVarde() * $thisSteg->getAntal(); ?> steg)<?php } ?></td>
		</tr>
	<?php
	}
}

?>
</table>