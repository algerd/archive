<?php
/*
Часто требуется, чтобы при итерации в foreach обходились не все публичные свойства объекта, а только определённые.
В этом случае удобно поместить этот набор свойств в массив и передать его в объект класса массива-итератора ArrayIterator($array)
в методе getIterator ().
Тем самым мы задаём что итеририровать при передаче объекта в foreach
*/

// Простой класс с каким-то набором своих свойств и методов
class MyCollection implements IteratorAggregate
{
	// здесь хранится массив свойств, которые требуется проитерировать в foreach
	private $_array = array();		
	// в конструкторе можно инициализировать передаваемый массив извне, но можно собирать массив и через другие методы в зависимости от целей
	public function __construct($array){
		$this->_array = $array;		
	}
	// !!! этот метод и определяет какой итератор будет запущен в foreach
	public function getIterator (){
		// возвращает объект-массив-итератор, который будет запущен в foreach
		return new ArrayIterator($this->_array);
	}
}

$arr = array(56, 36, 'login'=>'alex', 'root');
// это простой объект
$obj = new MyCollection($arr);
// итерируется не весь $obj, а только new ArrayIterator($this->_array) 
foreach($obj as $key=>$method){
	echo $key.' -> '.$method.'<br />';
}
echo '<hr>';

?>
