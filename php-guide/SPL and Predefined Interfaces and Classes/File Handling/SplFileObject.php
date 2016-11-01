<?php
/*
SplFileObject extends SplFileInfo implements RecursiveIterator , Traversable , Iterator , SeekableIterator 
{
  flags
	const integer DROP_NEW_LINE = 1 ;	- Удаляет символы переноса в конце строки.
	const integer READ_AHEAD = 2 ;		- Читает при использовании функций rewind/next.
	const integer SKIP_EMPTY = 6 ;		- Пропускает пустые строки с файле.
	const integer READ_CSV = 8 ;		- Читает строки в формате CSV.

	__construct ( string $filename [, string $open_mode = "r" [, bool $use_include_path = false [, resource $context ]]] )
 		- $open_mode - Режим открытия файла.(См. док по fopen()), $use_include_path - Если установлено в TRUE, имя файла также ищется в include_path
	void setFlags ( int $flags ) - установить флаги flags (напр. SplFileObject::DROP_NEW_LINE)
	int getFlags ( void )		 - получить флаги flags
	void setMaxLineLen ( int $max_len ) - Устанавливает максимальную длину строки		
	int getMaxLineLen ( void ) - Получает максимальную длину строки
	bool eof ( void )		- Проверяет, достигнут ли конец файла
	bool fflush ( void )	- Сбрасывает буфер вывода в файл
	string fgetc ( void )	- Читает символ из файла
	string fgets ( void )	- Читает строку из файла
	string fgetss ([ string $allowable_tags ] ) - Получение строки из файла с очисткой от HTML тэгов
	bool flock ( int $operation [, int &$wouldblock ] ) - Портируемая блокировка файла
	int fpassthru ( void )	- Выводит все оставшееся содержимое файла в выходной поток
	mixed fscanf ( string $format [, mixed &$... ] )	- Разбор строки файла в соответствии с заданным форматом
	int fseek ( int $offset [, int $whence = SEEK_SET ] ) - Перевод файлового указателя на заданную позицию
	array fstat ( void )	- Получает информацию о файле
	int ftell ( void )		- Определение текущей позиции файлового указателя
	bool ftruncate ( int $size ) - Обрезает файл до заданной длины
	int fwrite ( string $str [, int $length ] ) - Запись в файл	
  csv:
 	void setCsvControl ([ string $delimiter = "," [, string $enclosure = "\"" [, string $escape = "\\" ]]]) - Устанавливает символы разделителя и ограничителя для CSV
	array getCsvControl ( void ) - Получает символы разделителя и ограничителя для CSV
	fgetcsv ([ string $delimiter = "," [, string $enclosure = "\"" [, string $escape = "\\" ]]] ) - Получение строки файла и ее разбор в соответствии с CSV разметкой
	int fputcsv ( string $fields [, string $delimiter [, string $enclosure ]] ) - Выводит поля массива в виде строки CSV

  Переопределённые методы интерфейса Iterator
	void rewind ( void )		- Перевод файлового указателя в начало файла
	bool valid ( void )			- Проверяет, достигнут ли конец файла (EOF)		
	int key ( void )			- Получение номера строки		
	mixed current ( void )		- Получение текущей строки файла		
	void next ( void )			- Читает следующую строку			
  Переопределённые методы интерфейса SeekableIterator
	void seek ( int $line_pos )	- Перевод файлового указателя на заданную строку
  Переопределённые методы интерфейса RecursiveIterator
	bool hasChildren ( void )	- Класс SplFileObject не имеет наследников, поэтому метод всегда возвращает FALSE. 
	void getChildren ( void )	- Метод-заглушка					
  	
  Наследует все методы SplFileInfo
}
Класс SplFileObject предоставляет объектно-ориентированный интерфейс для файлов. Он работает с файлом построчно.
Он имеет полный функционал работы с файлами и наследует информационный интерфейс от родителя SplFileInfo.
Кроме того, он имеет переопределённые методы интерфейсов Iterator и RecursiveIterator, что делает  
возможным работу с ним как с итератором. По сути он представляет собой ArrayIterator, который 
может работать с итерирующими итераторами (методы RecursiveIterator - заглушки).
Для расширенной итерации объекта класса SplFileObject используют различные итерирующий итераторы, напр:
	(Работает по принципу матрёшки):
	$fileObject = new SplFileObject($filename);
	$iterator = new LimitIterator($fileObject, $pos, $c);
	foreach (iterator as $key=>$value);
	unset ($iterator, $array);
// или одной строкой - не создаёт дополнительные переменные-объекты, не надо их потом удалять
	foreach ( new LimitIterator( new SplFileObject($filename), $pos, $c) as $key=>$value ){}; 
*/
/*
$reflection = new ReflectionClass('SplFileObject');
var_dump($reflection->getProperties());
var_dump($reflection->getConstants());
var_dump($reflection->getMethods());
echo '<hr>';
*/

$file = new SplFileObject(__FILE__);

/*
// построчный вывод файла 
foreach($file as $line) {
	echo $line.'<br>';
}
*/
/*
// другой способо построчного вывода
while($file->valid()){
    echo $file->current().'<br>';
    $file->next();
}
*/


// ограниченный вывод строк 
$iterator = new LimitIterator($file, 10, 20);
// построчный вывод файла 
foreach($iterator as $line) {
	echo $line.'<br>';
}
