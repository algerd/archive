<?php
/*
DirectoryIterator extends SplFileInfo implements Iterator , Traversable , SeekableIterator 
{	 
	__construct ( string $path ) $path - Путь к директории
	 
  Унаследованные от SplFileInfo методы:
	string __toString ( void )	- Возвращает имя файла текущего элемента (current()) итератора
  is:
	bool isDir ( void )			- Определяет, является ли текущий элемент DirectoryIterator обычным файлом
	bool isDot ( void )			- Определяет, является ли текущий элемент DirectoryIterator
	bool isExecutable ( void )	- Определяет, является ли текущий элемент DirectoryIterator исполняемым
	bool isFile ( void )		- Определяет, является ли текущий элемент DirectoryIterator обычным файлом
	bool isLink ( void )		- Определяет, является ли текущий элемент DirectoryIterator символической ссылкой
	bool isReadable ( void )	- Определяет, доступен ли текущий элемент DirectoryIterator для чтения
	bool isWritable ( void )	- Определяет, доступен ли текущий элемент DirectoryIterator для записи
  get:	
	int getATime ( void )		- Возвращает время последнего доступа к текущему элементу 
	string getBasename ([ string $suffix ])- Возвращает имя файла (без расширения) текущего элемента DirectoryIterator
	int getCTime ( void )		- Возвращает время последнего изменения i-узла текущего элемента DirectoryIterator
	string getExtension ( void )- Возвращает расширение файла
	string getFilename ( void ) - Возвращает имя файла текущего элемента DirectoryIterator
	int getGroup ( void )		- Возвращает идентификатор группы текущего элемента DirectoryIterator
	int getInode ( void )		- Возвращает inode текущего элемента DirectoryIterator
	int getMTime ( void )		- Возвращает время последнего изменения текущего элемента DirectoryIterator
	int getOwner ( void )		- Возвращает индентификатор владельца текущего элемента DirectoryIterator
	string getPath ( void )		- Возвращает путь к текущему элементу DirectoryIterator без имени файла
	string getPathname ( void )	- Возвращает путь и имя файла текущего элемента DirectoryIterator
	int getPerms ( void )		- Возвращает набор прав для текущего элемента DirectoryIterator item
	int getSize ( void )		- Возвращает размер текущего элемента DirectoryIterator
	string getType ( void )		- Определяет тип текущего элемента DirectoryIterator	
		
  Переопределённые от Iterator методы	
	void rewind ( void )		- Устанавливает указатель на первый элемент DirectoryIterator
	bool valid ( void )			- Проверяет, является ли текущий элемент DirectoryIterator допустимым файлом
	string key ( void )			- Возвращает ключ текущего элемента DirectoryIterator
	DirectoryIterator current ( void )- Создаёт новый итератор директорий по пути
	void next ( void )			- Перемещает указатель на следующий элемент DirectoryIterator
  Переопределённые от SeekableIterator  методы	
	void seek ( int $position )	- Перемещает указатель DirectoryIterator на определённую позицию	
}
Класс DirectoryIterator предоставляет простой интерфейс для просмотра содержимого каталогов файловой системы. 
Он унаследовал все методы SplFileInfo, но применительно к директории.   
Благодаря наследованию от Iterator, DirectoryIterator представляет собой полноценный итератор (подобно ArrayIterator), но 
применительно к файлам директории как элементам массива. И ему доступны все расширения одномерный итерирующих итераторов, напр:  
 (Работает по принципу матрёшки):
	$iterator = new DirectoryIterator($dir);
	$iterator = new LimitIterator($iterator, $pos, $c);
	foreach (iterator as $key=>$value){};
	unset ($iterator, $array);
// или одной строкой - не создаёт дополнительные переменные-объекты, не надо их потом удалять
	foreach ( new LimitIterator( new DirectoryIterator($dir), $pos, $c) as $key=>$value ){};  
 */
/*
$reflection = new ReflectionClass('DirectoryIterator');
var_dump($reflection->getProperties());
var_dump($reflection->getConstants());
var_dump($reflection->getMethods());
echo '<hr>';
*/

$dir = new DirectoryIterator(dirname(__FILE__));
foreach ($dir as $fileinfo) {
    if (!$fileinfo->isDot()) {
		
        var_dump($fileinfo->getFilename());
    }
}

echo '<hr>';

$dirIterator = new DirectoryIterator(dirname(__FILE__));
// Только выполнение сравнения (фильтрация) для текущей записи (смотрите preg_match()).
$regexIterator = new RegexIterator($dirIterator, '/^(Doc\.txt)/', RegexIterator::MATCH);
foreach ($regexIterator as $key=>$value) {
	echo "k=$key v=$value <br>";
}

echo '<hr>';

$dirIterator = new DirectoryIterator(dirname(__FILE__));
// Ограничение выборки элементов
$iterator = new LimitIterator($dirIterator, 2, 3);
foreach ($iterator as $key=>$value) {
	echo "k=$key v=$value <br>";
}

echo '<hr>';
$fileInfo = new DirectoryIterator(dirname(__FILE__));

$fileProps = array();
$fileProps['path'] = $fileInfo->getPath();
$fileProps['filename'] = $fileInfo->getFilename();
$fileProps['pathname'] = $fileInfo->getPathname();
$fileProps['perms'] = $fileInfo->getPerms();
$fileProps['inode'] = $fileInfo->getInode();
$fileProps['size'] = $fileInfo->getSize();
$fileProps['owner'] = $fileInfo->getOwner();
$fileProps['group'] = $fileInfo->getGroup();
$fileProps['atime'] = $fileInfo->getATime();
$fileProps['mtime'] = $fileInfo->getMTime();
$fileProps['ctime'] = $fileInfo->getCTime();
$fileProps['type'] = $fileInfo->getType();
$fileProps['isWritable'] = $fileInfo->isWritable();
$fileProps['isReadable'] = $fileInfo->isReadable();
$fileProps['isExecutable'] = $fileInfo->isExecutable();
$fileProps['isFile'] = $fileInfo->isFile();
$fileProps['isDir'] = $fileInfo->isDir();
$fileProps['isLink'] = $fileInfo->isLink();
$fileInfo->seek(5);
$fileProps['__string'] = 'Файл '.$fileInfo->current();

var_dump($fileProps);