<?php

require_once($_SERVER["DOCUMENT_ROOT"]."/php/init.php");

/**
Nedanstående datum för förskjutning - förskjuter medlemskap alla medlemmar för medlemmar 
										Förskjuter bara datumet frammåt (medlemmar med längre subscription behåller sitt längre medlemskap
*/


//* Detta datum förskjuter medlemmars subscriptiontid för ALLA medlemmar
$new_subsdate_end = '2009-01-15';

/* -------------------------------------------- */

$sql = 'SELECT id FROM '.Medlem::TABLE.' 
			WHERE paidUntil < "'.$new_subsdate_end.'"';

$qry = mysql_query($sql);
echo 'Medlemmar som kommer påverkas ('.mysql_num_rows($qry).' st)<br /><br />';

while($res = mysql_fetch_assoc($qry)) {
	$medlem = Medlem::loadById($res['id']);

	$medlem->setPaidUntil($new_subsdate_end);
	$medlem->commit();
}

echo 'Förskjutning klar.';

?>