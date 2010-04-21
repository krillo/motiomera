<?php

$stegSenasteVeckan = Steg::getTotalSteg(date("Y-m-d", time()-(60*60*24*7)));
$this->assign("stegSenasteVeckan", $stegSenasteVeckan);

$forraVeckan = date("Y-m-d H:i:s", strtotime(date("Y-m-d"))-(60*60*24*7));

$topplista = new Topplista();
$topplista->addParameter(Topplista::PARAM_START, $forraVeckan);
$this->assign("topplista", $topplista);


?>