<?
require $_SERVER["DOCUMENT_ROOT"] . "/php/init.php";

if (isset($_POST['aktivitet'])) {
	$aktivitet = Aktivitet::loadById((int)$_POST['aktivitet']);
	echo (int)$aktivitet->getVarde();
}
else
	echo '0';

?>