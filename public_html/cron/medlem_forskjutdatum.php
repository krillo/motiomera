<?php

require_once($_SERVER["DOCUMENT_ROOT"]."/php/init.php");

/**
Nedanst�ende datum f�r f�rskjutning - f�rskjuter medlemskap alla medlemmar f�r medlemmar 
										F�rskjuter bara datumet framm�t (medlemmar med l�ngre subscription beh�ller sitt l�ngre medlemskap
*/


//* Detta datum f�rskjuter medlemmars subscriptiontid f�r ALLA medlemmar
$new_subsdate_end = '2009-01-15';

/* -------------------------------------------- */

$sql = 'SELECT id FROM '.Medlem::TABLE.' 
			WHERE paidUntil < "'.$new_subsdate_end.'"';

$qry = mysql_query($sql);
echo 'Medlemmar som kommer p�verkas ('.mysql_num_rows($qry).' st)<br /><br />';

while($res = mysql_fetch_assoc($qry)) {
	$medlem = Medlem::loadById($res['id']);

	$medlem->setPaidUntil($new_subsdate_end);
	$medlem->commit();
}

echo 'F�rskjutning klar.';

?>