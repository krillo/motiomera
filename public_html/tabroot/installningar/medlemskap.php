<?php

global $USER;

$level = $USER->getLevel();

if($level > 0) {
	?>
	<p>Ditt <?=$level->getNamn()?>-medlemsskap är aktivt fram t.o.m. <strong><?= $USER->getPaidUntil() ?></strong></p>
	<p><a href="<?= $urlHandler->getUrl("Medlem", URL_BUY) ?>">Förläng ditt medlemsskap &raquo;</a></p>
	<?php
}
else {
	
	?>
	<p>Ditt medlemskonto skapades <?= substr($USER->getSkapad(),0,10); ?></p>
	<p><a href="<?= $urlHandler->getUrl("Medlem", URL_BUY) ?>">Skaffa ett pro-medlemskap &raquo;</a></p>
	<?php
}
?>