<?php
/*
SplFileInfo 
{
	__construct ( string $file_name ) - Создаёт новый объект SplFileInfo для указанного имени файла. $file_name - путь к файлу
	SplFileObject openFile ([ string $open_mode = r [, bool $use_include_path = false [, resource $context = NULL ]]] )
		- Создает SplFileObject object файла. Для этого объекта доступны методы классов SplFileObject и SplFileInfo		
          $open_mode - Режим открытия файла.(См. док по fopen()), $use_include_path - Если установлено в TRUE, имя файла также ищется в include_path
	void __toString ( void )	- Возвращает путь к файлу в виде строки
  is:
	bool isDir ( void )			- Сообщает, является ли файл каталогом
	bool isExecutable ( void )	- Сообщает, является ли файл исполняемым
	bool isFile ( void )		- Сообщает, ссылается ли объект на обычный файл
	bool isLink ( void )		- Сообщает, является ли файл ссылкой
	bool isReadable ( void )	- Сообщает, является ли файл доступным для чтения
	bool isWritable ( void )	- Сообщает, является ли элемент доступным для записи			
  get:	
	int getATime ( void )		- Получает время последнего обращения к файлу
	string getBasename ([ string $suffix ])- Получает базовое имя файла
	int getCTime ( void )		- Возвращает время последнего изменения индексного дескриптора файла
	string getExtension ( void )- Получает расширение файла
	SplFileInfo getFileInfo ([ string $class_name ])- Получает объект SplFileInfo для файла
	string getFilename ( void )	- Получает имя файла
	int getGroup ( void )		- Получает группу файла
	int getInode ( void )		- Получение индексного узла для файла
	string getLinkTarget ( void )- Получение пути по ссылке
	int getMTime ( void )		- Получает время последнего изменения
	int getOwner ( void )		- Определяет владельца файла
	string getPath ( void )		- Получение пути без имени файла
	SplFileInfo getPathInfo ([ string $class_name ])- Получение объекта SplFileInfo для заданного пути
	string getPathname ( void )	- Определение пути к файлу
	int getPerms ( void )		- Получает список разрешений для файла
	string getRealPath ( void )	- Определяет абсолютный путь к файлу
	int getSize ( void )		- Получает размер файла
	string getType ( void )		- Получает тип файла
  set	
	void setFileClass ([ string $class_name ])- Задает имя класса, который будет использоваться для открытия файлов методом SplFileInfo::openFile
	void setInfoClass ([ string $class_name ])- Задает имя класса, объекты которого будут создаваться методами getFileInfo и getPathInfo
	
}
Класс SplFileInfo предлагает высокоуровневый объектно-ориентированный интерфейс к информации об отдельном файле.
Этот класс предлагает набор информационных методов о файле. Если требуются методы работы над файлом как над объектом (чтение, запись и т.д.),
то надо создать объект с помощью метода SplFileInfo::openFile() или же создать объект напрямую через new SplFileObject().
 */
/*
$reflection = new ReflectionClass('SPLFileInfo');
var_dump($reflection->getProperties());
var_dump($reflection->getConstants());
var_dump($reflection->getMethods());
echo '<hr>';
*/

$fileInfo = new SPLFileInfo(__FILE__);

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
$fileProps['__string'] = 'Файл '.$fileInfo;

var_dump($fileProps);

// срздаём объект класса SplFileObject со всеми его методами
$objectfile = $fileInfo->openFile();
// список всех доступных методов $objectfile(SplFileObject) - методы классов SplFileObject и SplFileInfo
$reflection = new ReflectionClass($objectfile);
var_dump($reflection->getMethods());