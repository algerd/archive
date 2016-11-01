<?php

/**
 * Итератор-контейнер извлечённого из переданных в конструктор урлов контента.
 */
class MultiCurlIterator extends ArrayIterator {
	
	/**
	 * Максимальное количество урлов в одном потоке
	 */
	const SIZE_STREAM = 25;
	
	/**
	 * content['<url>'] = [<data>]
	 * @var array
	 */
	private $content = [];  
	
	/**
	 * Надо сделать: при обнаружении ошибки у какого-то урла, сохранять этот урл для повторного прохода.
	 * После прохода всех урлов, запускать проход тех урлов, которые были с ошибками. И так n-раз (задать количество).
	 * Если и после этого возникает ошибка, то выбрасывть исключение.
	 * 
	 * Разбивает массив урлов на подмассивы с количеством элементов $sizeStream и извлекает контент в потоках.
	 * @param array $urls массив урлов
	 * @param int $sizeStream количество элементов в одном потоке
	 * @return MultiCurl
	 * @throws \Exception
	 */
	public function __construct( array $urls , $sizeStream = self::SIZE_STREAM ) {
			
		$result = [];
		$separateUrls = array_chunk($urls, $sizeStream);

		foreach ($separateUrls as $partUrls) {
			$channels = [];
			$multi = curl_multi_init();				

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
				if ( $error = curl_error($channel) ) {
					$info = curl_multi_info_read($multi);
					throw new \Exception($error, $info['result']);
				}
				$result[$url] = curl_multi_getcontent($channel);
				curl_multi_remove_handle($multi, $channel);
			}
			curl_multi_close($multi);
			$result += $result; 
		}
		$this->content = $result;
		
		parent::__construct($this->content);
	}

}


/*
// Пример использования
$maxCount = 55;
$urls = [];
for ($idCountry = 0; $idCountry < $maxCount; ++$idCountry) {
	$urls[] = 'http://www.managerzone.com/xml/mz_rank.php?sport_id=2&country_id=' . $idCountry . '&limit=10';
}


$content = new MultiCurlIterator($urls);

echo $content->count().'<br>';
echo $content['http://www.managerzone.com/xml/mz_rank.php?sport_id=2&country_id=5&limit=10'];
print_r($content);
*/