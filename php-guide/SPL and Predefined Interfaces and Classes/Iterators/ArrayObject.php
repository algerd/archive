<?php
/*
ArrayObject implements IteratorAggregate , Traversable , ArrayAccess , Serializable , Countable 
{
	const integer STD_PROP_LIST = 1 ;	- Флаг: Свойста объекта получают стандартное поведение, при доступе к объекту как к списку (var_dump, foreach и т.д.).
	const integer ARRAY_AS_PROPS = 2 ;	- Флаг: Записи могут быть доступны как свойства (для чтения и записи).

    __construct ([ mixed $input [, int $flags = 0 [, string $iterator_class = "ArrayIterator" ]]] )
		$input - данные типа array или Object, $flags - флаги, 	$iterator_class - класс, который будет использоваться в качестве итератора объекта ArrayObject. 
	void append ( mixed $value )		- Добавляет новое значение в конец массива. 
	array getArrayCopy ( void )			- Создаёт копию ArrayObject в виде массива
	array exchangeArray ( mixed $input )- Заменяет текущий массив (array) на другой массив (array) или объект (object). 
	string getIteratorClass ( void )	- Возвращает имя класса итератора массива, который будет использоваться при итерации по этому объекту.
	void setIteratorClass ( string $iterator_class ) - Устанавливает имя класса итератора для ArrayObject
	int getFlags ( void )				- Возврашает флаги поведения ArrayObject
	void setFlags ( int $flags )		- Устанавливает флаги поведения (константы этого класса)
			
  - сортировки		
	void asort ( void )		- Сортирует записи по значению			
	void ksort ( void )		- Сортирует записи по ключу		
	void uasort ( callable $cmp_function ) - Сортирует записи по значению, используя пользовательскую функцию
	void uksort ( callable $cmp_function ) - Сортирует записи по ключу, используя пользовательскую функцию
	void natcasesort ( void )- Сортирует массив, используя регистронезависимый алгоритм "natural order"		
	void natsort ( void )	- Сортирует массив, используя алгоритм "natural order"		
	
  Перегрузка методов интерфейса IteratorAggregate			
	ArrayIterator getIterator ( void )	- Создаёт новый итератор из экземпляра ArrayObject. 		
			
  Перегрузка методов интерфейса ArrayAccess			
	mixed offsetGet ( mixed $index )	- Возвращает значение по указанному индексу
	void offsetSet ( mixed $index , mixed $newval ) - Установливает новое значение по указанному индексу		
	bool offsetExists ( mixed $index )	- Проверяет, существует ли указанный индекс при запросе isset()
	void offsetUnset ( mixed $index )	- Удаляет значение по указанному индексу при запросе unset()		
			
  Перегрузка методов интерфейса Serializable			
	void serialize ( void )	- Сериализует ArrayObject	
	void unserialize ( string $serialized )	- Десериализует ArrayObject	
			
  Перегрузка методов интерфейса Countable				
	int count ( void ) - Возвращает количество публичных свойств ArrayObject				
}

Класс ArrayObject - создаёт объект из массива и позволяет работать с массивом как с объектом.
Он реализует большой функционал по работе с массивом как с объектом, превосходя по удобству работу через стандартные функции с простым массивом.
Может работать как ArrayIterator через метод getIterator(). ArrayObject	и ArrayIterator почти близнецы и работа с ними почти не имеет отличий. 
ArrayObject заточен больше на работу с объектом, а ArrayIterator - с итератором. Но ничего не мешает их взаимозаменять.
Настоятельно рекомендуется использовать его или ArrayIterator (для многомерных - RecursiveArrayIterator)  для работы смассивами.

Правильнее использовать ArrayObject только для работы с объектом без итераций. А если требуются работа с итерацией этого объекта,
то его рекомендуется вкладывать в ArrayIterator (a не добираться до итератора через ArrayObject::getIterator()):
	$array = array();
	$arrayObject = new ArrayObject($array);
	$iterator = new ArrayIterator($arrayObject);
	$iteratorIterator = new IteratorIterator($iterator);
	foreach (iteratorIterator as $key=>$value){ };
	unset ($arrayObject, $iterator,$iteratorIterator, $array);
	// или одной строкой - не создаёт дополнительные переменные-объекты, не надо их потом удалять
	foreach ( new IteratorIterator( new ArrayIterator( new ArrayObject(array())) ) as $key=>$value ) {}; 

Такая структура подчёркивает логику кода, и чётко специализирует классы:
	ArrayObject - создание объекта из массива
	ArrayIterator - создание итератора из массива или объекта
	IteratorIterator - создание итерирующего итератора для итераций итератора
Можно и напрямую итерировать ArrayObject без ArrayIterator и IteratorIterator, но это вносит путаницу в код.
Это подобно типизации переменных, можно не указывать, но чтобы код был понятнее и жёстче - лучше указывать.
Для применения наследников-расширений IteratorIterator (напр.LimitIterator) надо явно прописывать:
	foreach ( new LimitIterator( new ArrayIterator( new ArrayObject(array()), $pos, $count )) as $key=>$value ) {}; 
 
Можно работать и с многомерными массивами, применяя для ArrayObject рекурсивные итераторы.
*/
/*
$reflection = new ReflectionClass('ArrayObject');
var_dump($reflection->getProperties());
var_dump($reflection->getConstants());
var_dump($reflection->getMethods());
echo '<hr>';
*/

$array = array('1' =>'one', '2' => 'two', '3' => 'three', 'fff');
$arrayobject = new ArrayObject($array);
$arrayobject->append('Tweety');

// вызываем внутренний итератор.
$iterator = $arrayobject->getIterator();
// но логически правильнее использовать ArrayIterator
// $iterator = new ArrayIterator ($arrayobject);
while($iterator->valid()) {
    echo $iterator->key().' => '.$iterator->current()."\n";
    $iterator->next();
}
echo "<br>";

// или напрямую 
foreach ($iterator as $key => $value)
	echo $key.' =>'.$value ."\n";

$arrayobjectCopy = $arrayobject->getArrayCopy();
var_dump($arrayobjectCopy);


// В остальном примеры использования ArrayObject аналогичны ArrayIterator (см. ArrayIterator.php) 
// За одним исключением: для итераций надо использовать $arrayobject->getIterator()

?>