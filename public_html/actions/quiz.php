<?php
	include $_SERVER["DOCUMENT_ROOT"] . "/php/init.php";
	foreach ($_GET as $key => $value) {
		echo $key . ' ' . $value;
		echo '<br>';
	}

	header("Location: /pages/grupper.php");

?>