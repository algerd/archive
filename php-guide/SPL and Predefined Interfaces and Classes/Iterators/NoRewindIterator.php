<?php
/*
NoRewindIterator extends IteratorIterator 
{	
  Все методы переопределены\унаследованы от IteratorIterator	
	__construct ( Iterator $iterator )
	mixed current ( void )
	iterator getInnerIterator ( void )
	mixed key ( void )
	void next ( void )
	void rewind ( void )
	bool valid ( void )
} 
 
Класс NoRewindIterator - это итератор IteratorIterator, который не может быть перемотан. Он как бы замораживает позицию итерации.
Это реализуется за счёт перегруженного метода rewind(), который не функционирует.

Пример работы с одномерным итератором класса ArrayIterator:
	$array = array();
	$iterator = new ArrayIterator($array);
	$iterator = new NoRewinIterator($iterator); // наследник FilterIterator
	foreach (iterator as $key=>$value){ };
	unset ($iterator, $array);
// или одной строкой - не создаёт дополнительные переменные-объекты, не надо их потом удалять
	foreach ( new NoRewinIterator( new ArrayIterator(array())) as $key=>$value );
*/
/*
$reflection = new ReflectionClass('NoRewindIterator');
var_dump($reflection->getProperties());
var_dump($reflection->getConstants());
var_dump($reflection->getMethods());
echo '<hr>';
*/		
$fruits = array("лимон", "апельсин", "яблоко", "груша");

$noRewindIterator = new NoRewindIterator(new ArrayIterator($fruits));

echo $noRewindIterator->current() . "\n"; // лимон
$noRewindIterator->next();
// возврат итератора в начало (ничего не должно случиться)
$noRewindIterator->rewind();
echo $noRewindIterator->current() . "\n"; // апельсин

echo'<br>';
foreach ($noRewindIterator as $value) echo $value. "\n"; 