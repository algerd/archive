<?php
/*
 RecursiveDirectoryIterator extends FilesystemIterator implements Traversable , Iterator , SeekableIterator , RecursiveIterator 
{
	const integer FOLLOW_SYMLINKS = 512;	- Заставляет метод hasChildren() следовать символическим ссылкам.
	
	__construct ( string $path [, int $flags = FilesystemIterator::KEY_AS_PATHNAME | FilesystemIterator::CURRENT_AS_FILEINFO ] )
		$path - Путь к объекту файловой системы(к директории), по которому требуется навигация.
		flags - константы FilesystemIterator::const и RecursiveDirectoryIterator::const
			
	bool hasChildren ([bool $allow_links = false]) - Определяет, является ли текущий элемент директорией
	mixed getChildren ( void )		- Если текущий элемент является директорией (hasChildren()=true), метод возвращает для нее итератор
	string getSubPath ( void )		- Возвращает путь к поддиректории
	string getSubPathname ( void )	- Возвращает путь к поддиректории и имя файла
	
    Унаследованы все методы от цепочки классов FilesystemIterator - DirectoryIterator - SplFileInfo - SplFileObject
}
Класс RecursiveDirectoryIterator предоставляет интерфейс для рекурсивного перебора каталогов файловой системы. В отличие от DirectoryIterator он 
вобрал в себя методы тонкой работы класса FilesystemIterator по возвращению требуемого результата от key()=>current() в зависимости от флага. 
RecursiveDirectoryIterator - рекурсивный итератор директории по аналогии с RecursiveArrayIterator и работать с ним надо соответ-
ствующими рекурсивными итерирующими итераторами (напр. RecursiveIteratorIterator):
	(Работает с RecursiveDirectoryIterator по принципу матрёшки):
	$array = array();
	$iterator = new RecursiveDirectoryIterator($dir);
	$iterator = new RecursiveIteratorIterator($iterator);
	foreach (iterator as $key=>$value){};
	unset ($iterator, $array);
// или одной строкой - не создаёт дополнительные переменные-объекты, не надо их потом удалять
	foreach ( new RecursiveIteratorIterator( new RecursiveDirectoryIterator($dir) ) as $key=>$value ){}; 

Таким же путём прописывается итерация итераторов класса RecursiveArrayIterator специальными итерирующими итераторами:
	foreach ( new RecursiveCallbackFilterIterator( new RecursiveDirectoryIterator($dir), $function ) as $key=>$value ){};
*/	
/*
$reflection = new ReflectionClass('RecursiveDirectoryIterator');
var_dump($reflection->getProperties());
var_dump($reflection->getConstants());
var_dump($reflection->getMethods());
echo '<hr>';
*/	
	
	
$iterator = new RecursiveDirectoryIterator('./');
$iterator = new RecursiveIteratorIterator($iterator);
foreach ($iterator as $key => $value) {
  $d = $iterator->getDepth();			// показывает степень вложенности текущего элемента итератора (0 это первый уровень)
  echo "depth=$d k=$key v=$value <br>";
}	

echo '<hr>';

// Фильтр со встроенным в accept() рекурсивным условием фильтрации if($this->hasChildren())
class RecursiveRegexFilter extends RecursiveRegexIterator 
{
	// переопределяем метод, чтобы можно было рекурсивно применять фильтр в accept() ко всем элементам массива (в том числе дочерним)
    public function accept() {
        // текущий элемент пройдет фильтр, если имеет дочерние элементы или он соответствует регулярному выражению
        return $this->hasChildren() || parent::accept();
    }
}
$iterator = new RecursiveDirectoryIterator('../');
$iterator = new RecursiveRegexFilter($iterator, '/^.+\.txt$/i', RegexIterator::MATCH);
$iterator = new RecursiveIteratorIterator($iterator);
foreach ($iterator as $key => $value) {
  $d = $iterator->getDepth();			// показывает степень вложенности текущего элемента итератора (0 это первый уровень)
  echo "depth=$d k=$key v=$value <br>";
}
	