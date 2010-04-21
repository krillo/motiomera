<?php
	
	require_once($_SERVER["DOCUMENT_ROOT"]."/php/init.php");

	Security::demand(ADMIN);	

	$smarty = new AdminSmarty;
	$smarty->assign("pagetitle", "Provkör påminnelse");

	if (!isset($_GET['qid']) || !is_numeric($_GET['qid'])) {
		throw new Paminnelse_sqlException("Felaktigt SQL-ID angivet", 1);
	}
	
	$query = Paminnelse_sql::loadById($_GET['qid']);
	$yttre_mall = $query->getMeddelandeId() ? Paminnelse_meddelanden::loadById($query->getMeddelandeId()) : '';
	$yttre_mall_code = html_entity_decode($yttre_mall->getMall());

	$medlemsLista = Paminnelse_sql::getAktivaByPaminnelse($query);
	if (!count($medlemsLista) && isset($_GET['fake']) && $_GET['fake']) {
		$medlemsLista = $db->valuesAsArray(
			'SELECT id FROM mm_medlem '
		  . 'WHERE epostBekraftad = 1 '
		  . 'ORDER BY RAND() LIMIT 0, 20');
	}
	
	if (count($medlemsLista)) {
		$theQuery = str_replace(
			array('#medlemslista#', '#sql_id#'),
			array(implode(', ', $medlemsLista), $query->getId()),
			$query->getQuery()
		);

		$allResults = $db->allValuesAsArray($theQuery);
		$resultat = array();

		foreach ($allResults as $key => $data) {

			if (isset($data['epost'])) {
				$key = $data['epost'];
			}
			$title = $query->getTitel();
			$content = $query->getInreMall();
			foreach ($data as $field => $value) {
				$content = str_replace('#'.$field.'#', $value, $content);
				$title = str_replace('#'.$field.'#', $value, $title);
			}
			ob_start();
			eval($yttre_mall_code);
			$resultat[$key]['subject'] = $title;
			$resultat[$key]['text'] = ob_get_clean();
		}
	
		// Clean up to save memory
		unset($allResults, $content, $title, $yttre_mall, $yttre_mall_code);
	} else {
		$theQuery = $query->getQuery();
		$resultat['ingen'] = array('subject' => 'Inga medlemmar', 'text' => 'Inga medlemmar matchades. Det kan bero på att ingen prenumererar på påminnelsen,<br />eller att senaste utskicksdatum hos alla prenumeranter ligger för nära.<br /><br />För att simulera en körning ändå, <a href="'.$urlHandler->getUrl('PaminnelseSQL', 'TrialRunFake', $query->getId()).'">&lt;klicka här&gt;</a>');
	}

	$smarty->assign('query', $theQuery);
	$smarty->assign('resultat', $resultat);

	$smarty->display('paminnelser_provkorsql.tpl');

?>