<?php
/*
 FilesystemIterator extends DirectoryIterator implements SeekableIterator , Traversable , Iterator 
{
  flags:
	const integer CURRENT_AS_PATHNAME = 32;	- Заставляет метод current() вернуть путь.
	const integer CURRENT_AS_FILEINFO = 0;	- Заставляет метод current() вернуть экземпляр SplFileInfo.
	const integer CURRENT_AS_SELF = 16;		- Заставляет метод current() вернуть $this (FilesystemIterator).
	const integer CURRENT_MODE_MASK = 240;	- Маскирует current()
	const integer KEY_AS_PATHNAME = 0;		- Заставляет метод key() вернуть путь.
	const integer KEY_AS_FILENAME = 256;	- Заставляет метод key() вернуть имя файла.
	const integer KEY_MODE_MASK = 3840;		- Маскирует key()
	const integer NEW_CURRENT_AND_KEY = 256;- Тоже, что FilesystemIterator::KEY_AS_FILENAME | FilesystemIterator::CURRENT_AS_FILEINFO.
	const integer SKIP_DOTS = 4096;			- Пропускает точечные файлы (. and ..).
	const integer UNIX_PATHS = 8192;		- Заставляет все пути использовать обратный слэш в Unix-стиле, независимо от настроек системы по умолчанию.

	__construct (string $path [, int $flags = FilesystemIterator::KEY_AS_PATHNAME | FilesystemIterator::CURRENT_AS_FILEINFO | FilesystemIterator::SKIP_DOTS ])
		$path - Путь к объекту файловой системы, по которому требуется навигация.
		$flags - константы FilesystemIterator::const			
	int getFlags ( void )		   - Получение флагов настроек объекта
	void setFlags ([ int $flags ]) - Задание настроечных флагов 
			
  Переопределённые методы от DirectoryIterator
	void rewind ( void )	- Переводит указатель на начало директории. 
	string key ( void )		- Возвращает ключ текущего элемента		
!	mixed current ( void )	- Извлекает информацию о текущем файле в соответствии с заданными флагами.
	void next ( void )		- Перемещает указатель на следующий элемент
  Наследует все остальные методы от цепочки классов DirectoryIterator - SplFileInfo - SplFileObject
 }
 
  Класс FilesystemIterator - Итератор файловой системы. Он расширяет итератор директории DirectoryIteratory, добавляя
возможность гибко настроить методы key()=>current() - возвращение нужного элемента итерации в зависимости от флага (DirectoryIterator возвращал
только текущий элемент итератора DirectoryIterator). В остальном это полный аналог итератора DirectoryIteratory и с ним можно
работать как с ArrayIterator, напр:
	(Работает по принципу матрёшки):
	$iterator = new FilesystemIterator($dir, $flags);
	$iterator = new LimitIterator($iterator, $pos, $c);
	foreach (iterator as $key=>$value){};
	unset ($iterator, $array);
// или одной строкой - не создаёт дополнительные переменные-объекты, не надо их потом удалять
	foreach ( new LimitIterator( new FilesystemIterator($dir, $flags), $pos, $c) as $key=>$value ){};  
*/	  	  	  	  	 	  
/*
$reflection = new ReflectionClass('FilesystemIterator');
var_dump($reflection->getProperties());
var_dump($reflection->getConstants());
var_dump($reflection->getMethods());
echo '<hr>';
*/
	  		  
$iterator = new FilesystemIterator(dirname(__FILE__), FilesystemIterator::KEY_AS_PATHNAME);
echo "Ключом является путь к файлу:\n";
foreach ($iterator as $key => $fileinfo) {
    echo $key . '<br>';
}

echo '<hr>';

$iterator->setFlags(FilesystemIterator::KEY_AS_FILENAME);
echo "\nКлючом является имя файла:\n";
foreach ($iterator as $key => $fileinfo) {
    echo $key . '<br>';
}	

echo '<hr>';

// Ограничение выборки элементов
$iterator->setFlags(FilesystemIterator::KEY_AS_FILENAME|FilesystemIterator::CURRENT_AS_PATHNAME);
$iterator = new LimitIterator($iterator, 2, 3);
foreach ($iterator as $key=>$value) {
	echo "key = $key ___ value = $value <br>";
}

