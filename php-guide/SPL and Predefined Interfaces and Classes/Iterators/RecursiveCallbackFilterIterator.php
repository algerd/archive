<?php
/*
RecursiveCallbackFilterIterator extends CallbackFilterIterator implements OuterIterator , Traversable , Iterator , RecursiveIterator 
{
	__construct ( RecursiveIterator $iterator , string $callback )
		$iterator - итератор типа  RecursiveIterator, к которому применяется фильтр; 
		$callback - Callback-функция, которая должна возвращать TRUE, если текущий элемент прошел фильтр, и FALSE, если элемент отклонен

  Перегружает методы интерфейса RecursiveIterator:
	RecursiveCallbackFilterIterator getChildren ( void ) -  Выбирает дочерние элементы внутреннего итератора, подходящие под условия фильтра.
		Прежде чем вызывать метод getChildren(), необходимо удостовериться, что внутренний итератор содержит дочерние элементы. Сделать это можно с помощью метода RecursiveCallbackFilterIterator::hasChildren(). 
	bool hasChildren ( void ) - Проверяет, содержит ли текущий элемент внутреннего итератора дочерние элементы 
			
  Наследуемые методы от CallbackFilterIterator ( ->FilterIterator->IteratorIterator ):
	string CallbackFilterIterator::accept ( void ) - Вызывает callback-функцию и передает ей в качестве аргументов текущее значение current, текущий ключ key и внутренний указатель
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
 
Класс RecursiveCallbackFilterIterator - это расширение CallbackFilterIterator для фильтрации многомерных итераторов (в него передаётся многомерный итератор класса RecursiveIterator). 
Он реализует абстрактный класс FilterIterator с использованием внешней Callback-функции в качестве фильтра.
Он фильтрует элементы переданного в конструктор итератора согласно переданной в конструктор Callback-функции.
При итерации объекта этого класса в foreach каждый такт цикла автоматически вызывается метод accept(), 
который Вызывает callback-функцию и передает ей в качестве аргументов текущее значение current, текущий ключ key и внутренний указатель

Работает в связке с многомерным итератором класса RecursiveArrayIterator и его итератором RecursiveIteratorIterator: (принцип матрёшки):
	$array = array();
	$iterator = new RecursiveArrayIterator($array);
	$iterator = new RecursiveCallbackFilterIterator ($iterator, callback $filter);
	$iterator = new RecursiveIteratorIterator($iterator);
	foreach (iterator as $key=>$value);
	unset ($iterator, $array);
  //или одной строкой - не создаёт дополнительные переменные-объекты, не надо их потом удалять
	foreach (new RecursiveIteratorIterator(new RecursiveCallbackFilterIterator(new RecursiveArrayIterator(array()), callback $filter)) as $key=>$value); 
 
См. файл CallbackFilterIterator.php, RecursiveArrayIterator.php, RecursiveIteratorIterator.php	 
*/
/*
$reflection = new ReflectionClass('RecursiveCallbackFilterIterator');
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
			// есть ли в элементе итератор (массив)
			if ($iterator->hasChildren()) return TRUE;
			return is_string($current) & !is_numeric($current);
		};
	}
	public function callbackFilterNumeric() {
		return function ($current, $key, $iterator){
			// есть ли в элементе итератор (массив)
			if ($iterator->hasChildren()) return TRUE;
			return is_numeric($current) & !is_string($current);
		};
	}
	public function callbackFilterValueMax($val) {
		return function ($current, $key, $iterator) use ($val) {
			// есть ли в элементе итератор (массив)
			if ($iterator->hasChildren()) return TRUE;
			return (is_numeric($current) and $current > $val);
		};
	}
	public function callbackFilterValueMin($val) {
		return function ($current, $key, $iterator) use ($val) {
			// есть ли в элементе итератор (массив)
			if ($iterator->hasChildren()) return TRUE;
			return (is_numeric($current) and $current < $val);
		};
	}
}

// функция запуска цикла вывода массива
function getForeach($recursIteratorIterator) {
	foreach ($recursIteratorIterator as $key=>$value) {
		$depth = $recursIteratorIterator->getDepth();		// показывает степень вложенности текущего элемента итератора (0 это первый уровень)
		echo "depth=$depth k=$key v=$value <br>";
	}
}

$array = array(
    0 => 1,
    1 => array('subA', 5,array(0 => 'subsubA', 1 => 10, 2 => array(0 => '2', 1 => 'deepB'))),
    2 => 'b',
    3 => array('11','subB',3),
    4 => 'bnm'
);
$filter = new MyCallbackFilters();					// объект фильтров
$iterator = new RecursiveArrayIterator($array);		// рекурсивный итератор

echo '<p>Только строковые значения: <br>';
$filterIterator = new RecursiveCallbackFilterIterator($iterator, $filter->callbackFilterString());	// помещаем рекурсивный итератор в рекурсивный фильтр-итератор
$recursIteratorIterator = new RecursiveIteratorIterator($filterIterator);		// момещаем рекурсивный фильтр-итератор в итерирующий рекурсивный итератор
getForeach($recursIteratorIterator);

echo '<p>Только числовые значения: <br>';
getForeach(new RecursiveIteratorIterator(new RecursiveCallbackFilterIterator($iterator, $filter->callbackFilterNumeric())));  // матрёшка итераторов в аргументе функции

echo '<p>Числовые значения меньше 6: <br>';
getForeach(new RecursiveIteratorIterator(new RecursiveCallbackFilterIterator($iterator, $filter->callbackFilterValueMin(6))));// матрёшка итераторов в аргументе функции

echo '<p>Числовые значения больше 4: <br>';
getForeach(new RecursiveIteratorIterator(new RecursiveCallbackFilterIterator($iterator, $filter->callbackFilterValueMax(4))));// матрёшка итераторов в аргументе функции

?>