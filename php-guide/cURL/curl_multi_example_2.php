<?php

$maxCount = 35;
$urls = [];
for ($idCountry = 0; $idCountry < $maxCount; ++$idCountry) {
	$urls[] = 'http://www.managerzone.com/xml/mz_rank.php?sport_id=&country_id=' . $idCountry . '&limit=10';
}

$result = getContentMultiCurl($urls);

echo count($result);
print_r($result);


/**
 * @param array $urls count($urls) < 51
 * @param int $timeLimit
 * @return array без Exception result['<url>'] = ['error' => <error>, 'data' => <data>]
 * @return array c Exception result['<url>'] = [<data>]
 * @throws \Exception
 */
function getContentMultiCurl( array $urls) {
	
	if ( count($urls) === 0 ) return array();
		
	$size = 10;
	$result = [];
	$separateUrls = array_chunk($urls, $size);
	
	foreach ($separateUrls as $partUrls) {
	
		$multi = curl_multi_init();		
		$channels = array();

		foreach ($partUrls as $url) {
			$ch = curl_init();
			curl_setopt($ch, CURLOPT_URL, $url);
			curl_setopt($ch, CURLOPT_HEADER, false);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
			curl_multi_add_handle($multi, $ch); 
			$channels[$url] = $ch;
		}

		$active = 0;	
		do {
			curl_multi_exec($multi, $active);
			curl_multi_select($multi);
			usleep(100);
		} while ($active > 0);

		
		foreach ($channels as $url => $channel) {			
			if ($error = curl_error($channel)) {
				throw new \Exception($error);
			}
			$result[$url] = curl_multi_getcontent($channel);
			curl_multi_remove_handle($multi, $channel);
		}
			
		/*
		// если не нужен Exception, но надо отслеживать ошибки - собираем данные в массив result['<url>'] = ['error' => <error>, 'errno' => <errno>, 'data' => <data>]
		foreach ($channels as $url => $channel) {
			$result[$url]['data'] = 
				!( $result[$url]['error'] = curl_error($channel) ) ? curl_multi_getcontent($channel) : '';

			curl_multi_remove_handle($multi, $channel);
		}
		*/
				
		curl_multi_close($multi);
		$result += $result; 
	}
		
	return $result;
}

