<?php
	require_once($_SERVER["DOCUMENT_ROOT"]."/php/init.php");
	$query = 'SELECT DISTINCT medlem_id as id FROM mm_foretagsnycklar WHERE medlem_id != "NULL" GROUP BY foretag_id ORDER BY count(foretag_id) DESC';
	$usersFromUniqueCompanies = $db->allValuesAsArray($query);
	
	$contestPageUrl = 'http://motiomera.se/pages/tavlingsresultat.php?id=';
	foreach ($usersFromUniqueCompanies as $user)
	{
		$cURL = curl_init();
		curl_setopt($cURL, CURLOPT_URL, $contestPageUrl.$user['id']);
		curl_setopt($cURL, CURLOPT_RETURNTRANSFER, 1);
		curl_exec($cURL);
		curl_close($cURL);
	}
?>