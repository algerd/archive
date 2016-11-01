<?php
/*
InfiniteIterator extends IteratorIterator implements OuterIterator , Traversable , Iterator 
{
  наследует все методы с IteratorIterator	
	IteratorIterator::__construct ( Traversable $iterator )
	Traversable IteratorIterator::getInnerIterator ( void )	
	void IteratorIterator::rewind ( void )
	bool IteratorIterator::valid ( void )
	scalar IteratorIterator::key ( void )		
	mixed IteratorIterator::current ( void )	
	void IteratorIterator::next ( void )	
}
 
Класс InfiniteIterator позволяет сделать бесконечный перебор итератора без необходимости вручную перебирать итератор до момента достижения его конца.
Передаваемый в конструктор итератор будет бесконечно перезапускаться в foreach, пока не выполниться заданное извне итератора условие на break
По устройству - это IteratorIterator с бесконечным перезапуском.
*/
/*
$reflection = new ReflectionClass('InfiniteIterator');
var_dump($reflection->getProperties());
var_dump($reflection->getConstants());
var_dump($reflection->getMethods());
echo '<hr>';
*/
$obj = new stdClass();
$obj->Mon = "Monday";
$obj->Tue = "Tuesday";
$obj->Wed = "Wednesday";
$obj->Thu = "Thursday";
$obj->Fri = "Friday";
$obj->Sat = "Saturday";
$obj->Sun = "Sunday";

$infinate = new InfiniteIterator(new ArrayIterator($obj));
foreach ( new LimitIterator($infinate, 0, 14) as $value ) {
    print($value.PHP_EOL.'<br>');
}

?>