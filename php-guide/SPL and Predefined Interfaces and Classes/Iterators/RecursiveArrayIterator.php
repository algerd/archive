<?php
/*
RecursiveArrayIterator extends ArrayIterator implements RecursiveIterator 
{
  перегрузка методов интерфейса итератора RecursiveIterator
	bool hasChildren ( void )					 - определяет, является ли текущий элемент массивом или объектом
    RecursiveArrayIterator getChildren ( void )  - возвращает итератор для текущего элемента, если этот элемент является массивом (array) или объектом (object)
		
	наследует все методы класса ArrayIterator (см. файл ArrayIterator.php)
}
 
Класс RecursiveArrayIterator позволяет работать не только с одномерными массивами как ArrayIterator, но и с многомерными.
Это возможно благодаря двум методам hasChildren() и getChildren().
В остальном работа с ним аналогична работе с ArrayIterator (см. файл ArrayIterator.php)	
 
Для рекурсивной итерации итераторов класса RecursiveArrayIterator используется рекурсивный итерирующий итератор RecursiveIteratorIterator
(Работает с RecursiveArrayIterator по принципу матрёшки):
	$array = array();
	$iterator = new RecursiveArrayIterator($array);
	$iterator = new RecursiveIteratorIterator($iterator);
	foreach (iterator as $key=>$value);
	unset ($iterator, $array);
// или одной строкой - не создаёт дополнительные переменные-объекты, не надо их потом удалять
	foreach ( new RecursiveIteratorIterator( new RecursiveArrayIterator(array()) ) as $key=>$value ){}; 

Таким же путём прописывается итерация итераторов класса RecursiveArrayIterator специальными итерирующими итераторами:
	foreach ( new RecursiveCallbackFilterIterator( new RecursiveArrayIterator(array()), $function ) as $key=>$value ){};
*/
/*
$reflection = new ReflectionClass('RecursiveArrayIterator');
var_dump($reflection->getProperties());
var_dump($reflection->getConstants());
var_dump($reflection->getMethods());
echo '<hr>';
*/
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
// Рекурсивно выводит элементы итератора $iterator
function traverseStructure($iterator) {   
    while($iterator -> valid()) {
		// Есть ли в текущем элементе итератора подмассив
        if($iterator -> hasChildren()) {
			// Рекурсивно выводит элементы итератора $iterator -> getChildren()
            traverseStructure($iterator -> getChildren());          
        }
        else {
            echo $iterator -> key() . ' : ' . $iterator -> current() .PHP_EOL;   
        }
        $iterator -> next();
    }
} 

$array = array(
    0 => 'a',
    1 => array('subA','subB',array(0 => 'subsubA', 1 => 'subsubB', 2 => array(0 => 'deepA', 1 => 'deepB'))),
    2 => 'b',
    3 => array('subA','subB','subC'),
    4 => 'c'
);

$iterator = new RecursiveArrayIterator($array);
// Рекурсивно выводит элементы итератора $iterator
//iterator_apply($iterator, 'traverseStructure', array($iterator));
traverseStructure($iterator);

echo '<hr>'; ///////////////////////////////////////////////////////////////////////////////////////////////////////////

echo 'Итерация рекурсивным итерирующим итератором RecursiveIteratorIterator: <br>';
$iterator = new RecursiveArrayIterator($array);
$iterator = new RecursiveIteratorIterator($iterator);
foreach ($iterator as $key=>$value){
	$d = $iterator->getDepth();			// показывает степень вложенности текущего элемента итератора (0 это первый уровень)
	echo "depth = $d key = $key value = $value <br>";
}
		





?>