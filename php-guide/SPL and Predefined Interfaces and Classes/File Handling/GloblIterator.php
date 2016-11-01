<?php
/*
GlobIterator extends FilesystemIterator implements Iterator , Traversable , SeekableIterator , Countable 
{
    __construct ( string $pattern [, int $flags = FilesystemIterator::KEY_AS_PATHNAME | FilesystemIterator::CURRENT_AS_FILEINFO ] )
        $pattern - шаблон директории, по которому будут искаться совпадения
		flags - константы FilesystemIterator::const
    int count ( void ) - Определяет количество директорий и файлов, совпадающих с glob-выражением.
  Унаследованы все методы от цепочки классов FilesystemIterator - DirectoryIterator - SplFileInfo - SplFileObject    
}

Класс GlobIterator - ищет все пути относительно глобального, совпадающие с шаблоном pattern.
формирует на основе найденных результатов итератор типа ArrayIterator согласно правилам, указанным в флагах.
 */
/*
$reflection = new ReflectionClass('GlobIterator');
var_dump($reflection->getProperties());
var_dump($reflection->getConstants());
var_dump($reflection->getMethods());
echo '<hr>';
*/

$iterator = new GlobIterator(dirname(__FILE__).'/*.php', FilesystemIterator::KEY_AS_FILENAME);
echo 'Количество совпадений: '.$iterator->count().'<br>';
foreach ($iterator as $key=>$value) {
	echo "key = $key ___ value = $value <br>";
}

echo '<hr>';

// ограничение выборки
$iterator = new LimitIterator($iterator, 2, 3);
foreach ($iterator as $key=>$value) {
	echo "key = $key ___ value = $value <br>";
}