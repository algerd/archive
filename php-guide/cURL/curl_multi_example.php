<?php

$maxCount = 20;	// TINYINT 255
$urls = [];
for ($idCountry = 0; $idCountry < $maxCount; ++$idCountry) {
	$urls[] = 'http://www.managerzone.com/xml/mz_rank.php?sport_id=&country_id=' . $idCountry . '&limit=1';
}

print_r(getContentMultiCurl($urls));


function getContentMultiCurl(array $urls) {

	$multi = curl_multi_init();
	$channels = array();

	foreach ($urls as $url) {	
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_HEADER, false);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_multi_add_handle($multi, $ch); 
		$channels[$url] = $ch;
	}

	$active = 0;
	/*
	do {
		$mrc = curl_multi_exec($multi, $active);
	} while ($mrc == CURLM_CALL_MULTI_PERFORM);

	while ($active && $mrc == CURLM_OK) {
		if (curl_multi_select($multi) == -1) {
			usleep(100);
		} 
		do {
			$mrc = curl_multi_exec($multi, $active);
		} while ($mrc == CURLM_CALL_MULTI_PERFORM);
	}
	*/
	
	do {
		curl_multi_exec($multi, $active);
		curl_multi_select($multi);
		usleep(100);
	} while ($active > 0);

	
	
	$result = [];
	foreach ($channels as $channel) {
		$result[] = curl_multi_getcontent($channel);
		curl_multi_remove_handle($multi, $channel);
	}
	curl_multi_close($multi);
	
	return $result;
}