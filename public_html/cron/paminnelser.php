<?php

	require_once( $_SERVER['DOCUMENT_ROOT'] . '/php/init.php' );

	$allaPaminnelser = Paminnelse_sql::listAll();
	
	if (DEBUG) {
		echo "--- DEBUG INFORMATION -----------------\n";
	}
	
	foreach ($allaPaminnelser as $paminnelse) {
		$medlemsLista = Paminnelse_sql::getAktivaByPaminnelse($paminnelse);
		if (count($medlemsLista)) {
			$query = str_replace(
				array('#medlemslista#', '#sql_id#'),
				array(implode(', ', $medlemsLista), $paminnelse->getId()),
				$paminnelse->getQuery()
			);
			
			$resultat = array();
			$yttre_mall = $paminnelse->getMeddelandeId() ? Paminnelse_meddelanden::loadById($paminnelse->getMeddelandeId()) : '';
			$yttre_mall_code = html_entity_decode($yttre_mall->getMall());			
			
			$res = mysql_query($query) or die(mysql_error() . "\n" . $query);
			while ($data = mysql_fetch_assoc($res)) {
				if (isset($data['epost'])) {
					$key = $data['epost'];
					$title = $paminnelse->getTitel();
					$content = $paminnelse->getInreMall();
					foreach ($data as $field => $value) {
						$content = str_replace('#'.$field.'#', $value, $content);
						$title = str_replace('#'.$field.'#', $value, $title);
					}
					ob_start();
					eval($yttre_mall_code);
					$resultat[$data['epost']]['text'] = ob_get_clean();
					$resultat[$data['epost']]['subject'] = $title;
				}
			}
			if (count($resultat)) {
				if (DEBUG) {
					echo "- Sending emails about reminder '" . $paminnelse->getNamn() . "': ";
					echo count($resultat)." email(s).\n";
				}
				foreach ($resultat as $to => $email) {
					if (mail($to, $email['subject'], $email['text'])) {
						if (DEBUG) {
							echo "- Sent!\n";
							echo "--------------------------\n";
						}
					}
				}
			}
		}
	}
	
	if (DEBUG) {
		echo "\n\n";
	}
?>