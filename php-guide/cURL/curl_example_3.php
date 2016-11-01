<?php

$maxCount = 5;	// TINYINT 255
$urls = [];
for ($idCountry = 0; $idCountry < $maxCount; ++$idCountry) {
	$urls[] = ['url' => 'http://www.managerzone.com/xml/mz_rank.php?sport_id=&country_id=' . $idCountry . '&limit=100'];
}

$result = multiCurl($urls);

echo count($result);
print_r($result);


function multiCurl( array $res, $options="" ) {

	if (count($res)<=0) return False;

	$handles = array();
	
	// add default options
	if (!$options) { 
		$options = array(
			CURLOPT_HEADER=>0,
			CURLOPT_RETURNTRANSFER=>1,
		);
	}	
	// add curl options to each handle
	foreach($res as $k=>$row){
		$ch{$k} = curl_init();
		$options[CURLOPT_URL] = $row['url'];
		$opt = curl_setopt_array($ch{$k}, $options);
		var_dump($opt);
		$handles[$k] = $ch{$k};
	}

	$mh = curl_multi_init();

	// add handles
	foreach($handles as $k => $handle){
		$err = curl_multi_add_handle($mh, $handle);            
	}

	$running_handles = null;
	do {
	  curl_multi_exec($mh, $running_handles);
	  curl_multi_select($mh);
	} while ($running_handles > 0);

	foreach($res as $k=>$row){
		$res[$k]['error'] = curl_error($handles[$k]);
		if(!empty($res[$k]['error']))
			$res[$k]['data']  = '';
		else
			$res[$k]['data']  = curl_multi_getcontent($handles[$k]);
		// close current handler
		curl_multi_remove_handle($mh, $handles[$k]);
	}
	curl_multi_close($mh);
	
	return $res;
}