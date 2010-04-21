<?php

require_once($_SERVER["DOCUMENT_ROOT"]."/php/init.php");

/**
Nedanst�ende datum f�r f�rskjutning - f�rskjuter medlemskap f�r medlemmar i f�retag vars t�vling 
										slutar/slutat $foretagstavling_end_from till $new_subsdate_end.
										F�rskjuter bara datumet framm�t (medlemmar med l�ngre subscription beh�ller sitt l�ngre medlemskap
*/

$foretagstavling_end_from = '2008-10-26'; 
$new_subsdate_end = '2009-01-15';

/* -------------------------------------------- */

$sql = 'SELECT id 
		FROM mm_foretag 
		WHERE DATE_ADD(startdatum, INTERVAL '.Foretag::TAVLINGSPERIOD_DAGAR.' DAY) >= \''.$foretagstavling_end_from.'\'';


$qry = mysql_query($sql);

if(mysql_num_rows($qry)>0) {
	echo 'F�retag som p�verkas ('.mysql_num_rows($qry).' st) : <br />';

	$foretag = array();
	$foretagsids = array();

	while($res = mysql_fetch_assoc($qry)) {
		$foretagsids[] = $res['id'];
	}
	
	$sql = 'SELECT medlem_id 
			FROM '.Foretag::KEY_TABLE.' 
			WHERE foretag_id IN ('.implode(', ',$foretagsids).')';
	
	$qry = mysql_query($sql);

	if(mysql_num_rows($qry)>0) {

	
		$medlemmar_id = array();
		while($res = mysql_fetch_assoc($qry)) {
			if(is_numeric($res['medlem_id']) && !empty($res['medlem_id']))
				$medlemmar_id[] = $res['medlem_id'];
			
		}
		echo 'Medlemmar som p�verkas ('.count($medlemmar_id).' st)<br />';

		$counter = 0;
		foreach($medlemmar_id as $medlem_id) {

			try { 
				$medlem = Medlem::loadById($medlem_id); 
			}
			catch (Exception $e) { } //ingen medlem

			if(!empty($medlem)) {
				if($medlem->getPaidUntil()<$new_subsdate_end) {
					$medlem->setPaidUntil($new_subsdate_end);
					$medlem->commit();
					++$counter;	
				}

			}
		}
	}

	echo 'Totalt '.$counter.' medlemmars subscriptions f�rskj�ts framm�t.<br />';
}
?>