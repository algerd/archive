<?php
/*
AppendIterator extends IteratorIterator implements OuterIterator , Traversable , Iterator
{
	void append ( Iterator $iterator )	- Добавляет итератор
	ArrayIterator getArrayIterator ( void )	- Возвращает класс ArrayIterator, содержащий добавленные итераторы. 
	int getIteratorIndex ( void )		- Возвращает индекс текущего внутреннего итератора. 
		
  Унаследованные методы IteratorIterator (а тот всвою очередь перегружает OuterIterator и Iterator)		
	Iterator getInnerIterator (void)	- Возвращает внутренний итератор
	void rewind ( void )				- Перемещает указатель в начало массива
	void next ( void )					- Перемещает указатель на следующую запись
	bool valid ( void )					- Проверяет, содержит ли массив еще записи
	mixed key ( void )					- Возвращает ключ текущего элемента массива
	mixed current ( void )				- Возвращает текущий элемент в массиве
}

Класс AppendIterator - итерирующий итератор, который выполняет несколько итераторов один за другим.
Он как бы склеивает итераторы с помощью метода добавления итератора append (Iterator $iterator).
Это ещё одно полезное расширение класса IteratorIterator.	
*/
/*
$reflection = new ReflectionClass('AppendIterator');
var_dump($reflection->getProperties());
var_dump($reflection->getConstants());
var_dump($reflection->getMethods());
echo '<hr>';
*/

$array_a = new ArrayIterator(array('a', 'b', 'c'));
$array_b = new ArrayIterator(array('d', 'e', 'f'));

$iterator = new AppendIterator();
$iterator->append($array_a);
$iterator->append($array_b);

foreach ($iterator as $key=>$value) echo "$key => $value <br>";

var_dump($iterator->getArrayIterator());

?>