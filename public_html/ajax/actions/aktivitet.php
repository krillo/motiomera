<?php
if (isset($_POST['activity']) && !empty($_POST['activity']))
{
	require $_SERVER["DOCUMENT_ROOT"] . "/php/init.php";
	echo json_encode(Steg::getSvarghetsGradForAktivitet(trim($_POST['activity']), true));
}
?>
