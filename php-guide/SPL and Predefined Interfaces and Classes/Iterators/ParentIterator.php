<?php
/*
ParentIterator extends RecursiveFilterIterator implements RecursiveIterator , OuterIterator , Traversable , Iterator 
{
	__construct ( RecursiveIterator $iterator )
 
	bool hasChildren ( void )	- Проверяет, есть ли у текущего элемента внутреннего итератора дочерние элементы 
	ParentIterator getChildren ( void ) - Возвращает дочерние элементы внутреннего итератора в виде объекта ParentIterator
 
	bool accept ( void )	- Проверяет, является ли текущий элемент итератора допустимым	
	void next ( void )
	void rewind ( void )
}
 
ParentIterator - Это реализация(расширение) абстрактного класса RecursiveFilterIterator.
Он показывает только те элементы, которые имеют потомков. 
Он итерируется итерирующим итератором RecursiveIteratorIterator.

Пример работы с многомерным итератором-массивом класса RecursiveArrayIterator (матрёшка):
	$array = array();
	$iterator = new RecursiveArrayIterator($array);	
	$iterator = new ParentIterator($iterator);
	$iterator = new RecursiveIteratorIterator($iterator);
	foreach (iterator as $key=>$value){ };
	unset ($iterator, $array);
// или одной строкой - не создаёт дополнительные переменные-объекты, не надо их потом удалять
	foreach (new RecursiveIterator ( new ParentIteratorIterator ( new RecursiveArrayIterator(array()))) as $key=>$value ){};
*/
/*
$reflection = new ReflectionClass('ParentIterator');
var_dump($reflection->getProperties());
var_dump($reflection->getConstants());
var_dump($reflection->getMethods());
echo '<hr>';
*/
$array    = ["test1", 'b'=>["taste2", 'a'=>[1,2,'c'=>[56, 35],4,5], "st3", "test4"], "test5"];
$iterator = new RecursiveArrayIterator($array);
$iterator   = new ParentIterator($iterator);
$iterator = new RecursiveIteratorIterator($iterator, RecursiveIteratorIterator::CHILD_FIRST);

foreach($iterator as $key => $value){
	$depth = $iterator->getDepth();		// показывает степень вложенности текущего элемента итератора (0 это первый уровень)
	echo "depth=$depth k=$key v: ";
	print_r($value);
	echo '<br>';
}


?>

