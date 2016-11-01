<?php
/*
 IteratorIterator implements Iterator , Traversable , OuterIterator 
{
	__construct ( Traversable $iterator )
	
  перегружает методы интерфейса OuterIterator	
	Traversable getInnerIterator ( void ) - Возвращает внутренний итератор для текущего элемента
	
  перегружает методы интерфейса Iterator
	void rewind ( void )				— Перемещает указатель в начало массива
	void next ( void )					— Перемещает указатель на следующую запись
	bool valid ( void )					— Проверяет, содержит ли массив еще записи
	mixed key ( void )					— Возвращает ключ текущего элемента массива
	mixed current ( void )				— Возвращает текущий элемент в массиве
}
  
Класс IteratorIterator (одномерный итерирующий итератор) -  итерирует объекты, созданные порождающими одномерными итераторами. Это обёртка одномерных итераторов - итерирующий итератор.
(См. пример работы в Doc.php)
Сам класс не представляет особого интереса, поскольку передаваемые в конструктор итераторы (напр ArrayIterator) уже имеют перегруженный набор методов интерфейса
Iterator плюс большой набор своих, благодаря которым их можно итерировать без IteratorIterator.
Единственно, что добавляет Класс IteratorIterator - это метод getInnerIterator(), который возвращает переданный в конструктор итератор.
В этом и заключается фича: имеется доступ изнутри! класса наследуемых сторонних классов к переданному в конструктор итератору! 
И таким образом, расширяя класс IteratorIterator, можно создать свой собственный функционал, используя доступ к переданному в конструктор итератору через getInnerIterator().
Кроме того, через getIterator() можно вызывать методы переданного итератора ArrayObject->getIterator()->asort().

Работает с одномерными итераторами (напр ArrayIterator) по принципу матрёшки:
	$array = array();
	$iterator = new ArrayIterator($array);
	$iterator = new IteratorIterator($iterator);
	foreach (iterator as $key=>$value){ };
	unset ($iterator, $array);
	// или одной строкой - не создаёт дополнительные переменные-объекты, не надо их потом удалять
	foreach ( new IteratorIterator( new ArrayIterator(array()) ) as $key=>$value ); 
Здесь IteratorIterator для последовательного, пошагового, "правильного" итерирования, но его можно опустить и итерировать напрямую ArrayIterator. 
Он поддерживает логическую цепочку итерирования, но т.к. в передаваемых итераторах уже встроены механизмы итерирования, то это звено цепочки можно убрать. 
Это подобно типизации переменных - можно не указывать, но это вносит путаницу в код. 
*/
/*
$reflection = new ReflectionClass('IteratorIterator');
var_dump($reflection->getProperties());
var_dump($reflection->getConstants());
var_dump($reflection->getMethods());
echo '<hr>';
*/
// толку мало от прямого использования класса IteratorIterator c ArrayIterator :

$values = array('Alex', 'name' => 'sfsfs', 30);
$arrayIterator = new ArrayIterator($values);				// одномерный итератор с массива	
$iteratorIterator = new IteratorIterator($arrayIterator);	// одномерный итерирующий итератор
foreach ($iteratorIterator as $key=>$value) echo "$key => $value <br>";
// а теперь итерируем объект класса ArrayIterator без итерирующего итератора IteratorIterator:
foreach ($arrayIterator as $key=>$value) echo "$key => $value <br>";
// И получаем то же самое. 
// IteratorIterator - для последовательного, пошагового, "правильного" итерирования, но его можно опустить, если не требуется доступа к внутреннему итератору: 
$iter = $iteratorIterator->getInnerIterator();				// возвращает итератор $arrayIterator
var_dump($iter);

/*
 * Вывод: IteratorIterator - для последовательного, пошагового, "правильного" итерирования, но его можно опустить. 
 * Он поддерживает логическую цепочку итерирования, но т.к. в передаваемых итераторах уже встроены механизмы итерирования, то это звено цепочки можно убрать, , если не требуется доступа к внутреннему итератору
 * Это подобно типизации переменных - можно не указывать, но это вносит путаницу в код.
 */

// Расширяемый класс может реализовывать самые разные задачи, используя IteratorIterator и доступ к переданному итератору через getInnerIterator()
class MyIterator extends IteratorIterator
{
	public function count(){
		return count($this->getInnerIterator());
	}	
}

// расширяемый класс даёт больше функциональности, чем IteratorIterator
$myIterator = new MyIterator($arrayIterator);
echo 'count= '.$myIterator->count();	//3

?>