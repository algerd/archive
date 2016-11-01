<?php

/**
 * Итератор-контейнер извлечённого из переданных в конструктор урлов контента 
 * Пропускная способность закачки ограничивается скоростью интернета и подбирается параметрами:
 * sizeStream (25-50) и CURLOPT_TIMEOUT (2-5). При больших объёмах скачиваемого контента при высоком 
 * sizeStream (50) и низком CURLOPT_TIMEOUT (2) контент не будет успевать скачиваться и будут попытки 
 * повторного соединения-закачки и так пока не упрёмся в LIMIT_COUNT_CONNECT.
 * Поэтому sizeStream и CURLOPT_TIMEOUT надо устанавливать таким образом, чтобы весь контент скачивался
 * за 1 попытку.
 * При быстром интернете нет вообще смысла устанавливать CURLOPT_TIMEOUT, но если канал ограничен,
 * то закачку надо регулировать с помощью CURLOPT_TIMEOUT, ускоряя возможность повторных соединений при затыках в канале,
 * а иначе при затыке в канале сервер будет ждать установленное в его параметрах время(достаточно большое) и после этого
 * будет совершено повторное соединение для закачки.  
 */ 
class MultiCurlIterator extends ArrayIterator {
	
	/**
	 * Максимальное количество повторных соединений в случае возникновения ошибок 0 и 7
	 */
	const LIMIT_COUNT_CONNECT = 5;
	
	/**
	 * content['<url>'] = [<data>]
	 * @var array
	 */
	private $content = [];  
	
	/**
	 * Число повторных соединений для считывания контента с урлов, к которым не получилось подключиться
	 * @var int
	 */	
	public $countConnect = 0;
	
	/**
	 * Количество потоков в одном соединениии
	 * @var int
	 */
	private $sizeStream = 50;
	
	/**
	 * Массив опций $options по умолчанию для curl_setopt_array($ch, $options)
	 * Чем больший объём скачиваемого контента с одного потока, тем больше CURLOPT_TIMEOUT и меньше $this->sizeStream
	 * CURLOPT_TIMEOUT => 5
	 * CURLOPT_CONNECTTIMEOUT => 5
	 * @var array 
	 */
	public $curlOptions = [
		CURLOPT_HEADER => false,
		CURLOPT_RETURNTRANSFER => true,
    ];

	/**
	 * @param array $urls массив урлов
	 * @param int $sizeStream количество потоков в одном соединении
	 * @param array $options массив опций для curl_setopt_array($ch, $options)
	 */
	public function __construct( array $urls, $sizeStream = null, array $options = [] ) {
		if ((int)$sizeStream) $this->sizeStream = (int)$sizeStream;	
		$this->curlOptions = $options + $this->curlOptions;
		$this->multiCurl($urls);
		parent::__construct($this->content);
	}
	
	/**
	 * @return array
	 */
	public function getContent() {
		return $this->content;
	}

	/**
	 * При обнаружении ошибки 0, 7, 28 у какого-то урла, сохранять этот урл для повторного прохода.
	 * После прохода всех урлов, запускать проход тех урлов, которые были с ошибками. 
	 * И так n-раз (задать количество). Если n > self::LIMIT_COUNT_CONNECT выбрасывть исключение с последней ошибкой.
	 * @param array $urls массив урлов
	 * @throws \Exception
	 */
	public function multiCurl( array $urls ) {
		
		$result = [];
		$errorUrls = [];
		// Разбивает массив урлов на подмассивы с количеством элементов sizeStream
		$separateUrls = array_chunk($urls, $this->sizeStream);

		foreach ($separateUrls as $partUrls) {
			$channels = [];
			$multi = curl_multi_init();				

			foreach ($partUrls as $url) {
				$ch = curl_init($url);
				curl_setopt_array($ch, $this->curlOptions);
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
				if ( $error = curl_error($channel) ) {
					$info = curl_multi_info_read($multi);				
					// собираем урлы с ошибками 0, 7, 28 в массив для повторного соединения (ниже)						
					switch ($info['result']) {
						case 0 :
						case 7 :	
						case 28 :	
							$errorUrls[] = $url;
							break;
						default :
							throw new \Exception($error, $info['result']);
					}
				}
				else {						
					$result[$url] = curl_multi_getcontent($channel);
				}
				curl_multi_remove_handle($multi, $channel);
			}			
			curl_multi_close($multi);
			$this->content += $result; 
		}		
		// если есть ошибки урлов, то делаем повторное соединение, проходим по ним повторно и инкрементируем счётчик попыток-соединений
		if ( count($errorUrls) > 0 ) {
			if ( ++$this->countConnect > self::LIMIT_COUNT_CONNECT ) {
				throw new \Exception($error);
			}
			$this->multiCurl($errorUrls);	
		}
		else {
			//$this->countConnect = 0;
		}
	}
	
}

/*
// Перед использованием MultiCurlIterator в коде, подобрать параметры sizeStream и CURLOPT_TIMEOUT если надо в тестовом режиме (как ниже)
// Пример использования
$maxCount = 255;
$urls = [];
for ($idCountry = 0; $idCountry < $maxCount; ++$idCountry) {
	$urls[] = 'http://www.managerzone.com/xml/mz_rank.php?sport_id=2&country_id=' . $idCountry . '&limit=1';
}
$sizeStream = 50;
$curloptTimeout = 5;
$curloptConnecttimeout = 10;


$content = new MultiCurlIterator(
	$urls
	, 50
	, [CURLOPT_TIMEOUT => $curloptTimeout, CURLOPT_CONNECTTIMEOUT => $curloptConnecttimeout]	
);
echo $content->count().'<br>';
echo $content->countConnect.'<br>';
//echo $content['http://www.managerzone.com/xml/mz_rank.php?sport_id=2&country_id=5&limit=10'];

foreach ($content as $url => $val) {
	echo $url.'<br>';
	echo $val.'<br>';
}

*/