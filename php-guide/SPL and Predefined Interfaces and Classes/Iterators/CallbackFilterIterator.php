<?php
/*
CallbackFilterIterator extends FilterIterator implements Iterator , Traversable , OuterIterator 
{
	__construct ( Iterator $iterator , callable $callback ):
		$iterator - итератор типа  Iterator, к которому применяется фильтр; 
		$callback - Callback-функция, которая должна возвращать TRUE, если текущий элемент прошел фильтр, и FALSE, если элемент отклонен
	
   Перегрузка абстактного метода класса FilterIterator			
	string accept ( void ) - Вызывает callback-функцию и передает ей в качестве аргументов текущее значение current, текущий ключ key и внутренний указатель
			
   Наследуемые методы от FilterIterator ( тот в свою очередь от IteratorIterator)
	Iterator getInnerIterator ( void )	- возвращает внутренний итератор для текущего элемента		
	void rewind ( void )
	bool valid ( void )		
	mixed key ( void )	
	mixed current ( void )
	void next ( void )	
}
Реализация Callback-функции
	param $current   Значение текущего элемента
	param $key       Ключ текущего элемента
	param $iterator  Фильтруемый итератор
	return boolean   TRUE для принятия текущего элемента, иначе - FALSE
function my_callback ($current, $key, $iterator) {
    // Здесь код фильтрации
} 
 
Класс CallbackFilterIterator - это реализация абстрактного класса FilterIterator с использованием внешней Callback-функции в качестве фильтра одномерного итератора.
Он фильтрует элементы переданного в конструктор итератора согласно переданной в конструктор Callback-функции.
При итерации объекта этого класса в foreach каждый такт цикла автоматически вызывается метод accept(), 
который Вызывает callback-функцию и передает ей в качестве аргументов текущее значение current, текущий ключ key и внутренний указатель
Работает в связке с одномерным итератором класса ArrayIterator.
Благодаря такому разделению фильтра и класса фильтрации CallbackFilterIterator можно сделать шаблон: сколько угодно классов-фильтров или класс-набор методов-фильтров (они должны возвращать анонимные функции Closure), 
которые будут передавать нужный фильтр (анонимную функцию Closure) в зависимости от внешних условий в общий класс фильтрации CallbackFilterIterator.

Пример работы с одномерным итератором класса ArrayIterator:
	$array = array();
	$iterator = new ArrayIterator($array);
	$iterator = new CallbackFilterIterator($iterator, $function);
	foreach (iterator as $key=>$value){ };
	unset ($iterator, $array);
// или одной строкой - не создаёт дополнительные переменные-объекты, не надо их потом удалять
	foreach ( new CallbackFilterIterator( new ArrayIterator(array()), $function ) as $key=>$value );
*/
/*
$reflection = new ReflectionClass('CallbackFilterIterator');
var_dump($reflection->getProperties());
var_dump($reflection->getConstants());
var_dump($reflection->getMethods());
echo '<hr>';
*/
// Класс - набор Callback-фильтров
class MyCallbackFilters
{
	public function callbackFilterString() {
		return function ($current, $key, $iterator){
			return is_string($current) & !is_numeric($current);
		};
	}
	public function callbackFilterNumeric() {
		return function ($current, $key, $iterator){
			return is_numeric($current) & !is_string($current);
		};
	}
	public function callbackFilterValueMax($val) {
		return function ($current, $key, $iterator) use ($val) {
			return (is_numeric($current) and $current > $val);
		};
	}
	public function callbackFilterValueMin($val) {
		return function ($current, $key, $iterator) use ($val) {
			return (is_numeric($current) and $current < $val);
		};
	}
}

$arr = array(1,'ddggg',3,4,5,'fgh',7,8,'kjjhg',10);

$iterator = new ArrayIterator($arr);				// создаём одномерный итератор из массива
$filter = new MyCallbackFilters();					// объект фильтров

echo '<p>Только строковые значения: ';
$filterIterator = new CallbackFilterIterator($iterator, $filter->callbackFilterString());
foreach ($filterIterator as $val) echo "$val, ";

echo '<p>Только числовые значения: ';
$filterIterator = new CallbackFilterIterator($iterator, $filter->callbackFilterNumeric());
foreach ($filterIterator as $val) echo "$val, ";

echo '<p>Только числовые значения меньше 6: ';
$filterIterator = new CallbackFilterIterator($iterator, $filter->callbackFilterValueMin(6));
foreach ($filterIterator as $val) echo "$val, ";

echo '<p>Только числовые значения больше 4: ';
$filterIterator = new CallbackFilterIterator($iterator, $filter->callbackFilterValueMax(4));
foreach ($filterIterator as $val) echo "$val, ";