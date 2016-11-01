<?php
/*
LimitIterator extends IteratorIterator implements OuterIterator , Traversable , Iterator 
{
	__construct (Iterator $iterator [, int $offset = 0 [, int $count = -1 ]]) - $offset-номер позиции элемента массиве, $count - число элементов, начиная с $offset   
	int seek (int $position)		- Перемещает итератор на заданную позицию в пределах заданного в конструкторе лимита
	int getPosition (void)		- Возвращает текущую позицию
			
  Унаследованные методы IteratorIterator (а тот всвою очередь перегружает OuterIterator и Iterator)		
	Iterator getInnerIterator (void)	- Возвращает внутренний итератор
	void rewind (void)
	bool valid (void)
	mixed key (void)		
	mixed current (void)		
	void next (void)			
} 
 
Класс LimitIterator - позволяет сделать перебор ограниченного количества элементов в переданном ему итераторе.
Это очень полезное расширение класса IteratorIterator.
В конструктор передаётся итератор-массив, позиция с которой будет выборка и число элементов выборки, начиная с переданной позиции.
Работает аналогично стандартному SQL-выражению LIMIT $offset, $count
		
Пример работы с одномерным итератором класса ArrayIterator:
	$array = array();
	$iterator = new ArrayIterator($array);
	$iterator = new LimitIterator($iterator, $pos, $count);
	foreach (iterator as $key=>$value){ };
	unset ($iterator, $array);
// или одной строкой - не создаёт дополнительные переменные-объекты, не надо их потом удалять
	foreach ( new LimitIterator( new ArrayIterator(array()), $pos, $count ) as $key=>$value );
*/
/*
$reflection = new ReflectionClass('LimitIterator');
var_dump($reflection->getProperties());
var_dump($reflection->getConstants());
var_dump($reflection->getMethods());
echo '<hr>';
*/
$array_it = new ArrayIterator(array('apple','banana','cherry','damson','elderberry'));

// первые три фрукта
$limit_it = new LimitIterator($array_it, 1, 2);
foreach ($limit_it as $fruit) {
    echo $limit_it->getPosition().' - '.$fruit."<br>";
}

echo "<hr>";

// с третьего фрукта и до конца (смещение начинается с нуля)
$limit_it = new LimitIterator($array_it, 2);

foreach ($limit_it as $fruit) {
    echo $limit_it->getPosition().' - '.$fruit."<br>";
}
?>