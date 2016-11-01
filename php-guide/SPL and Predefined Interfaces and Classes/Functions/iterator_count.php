<?php
/*
int iterator_count ( Traversable $iterator )
	$iterator - Итератор, в котором производится подсчет.
	Возвращаемые значения: Количество элементов в итераторе (iterator).

iterator_count - Подсчитывает количество элементов в итераторе. Аналогичен count() для массива.
*/

$iterator = new ArrayIterator(array('recipe'=>'pancakes', 'egg', 'milk', 'flour'));
var_dump(iterator_count($iterator));

echo '<hr>';

// Пример, показывающий отличия в приминении iterator_count() и count()
$array = array(1 => 'foo', 2 => 'bar');

foreach ($array as $key => $value){
    echo "count(): $key: $value (", count($array), ")<br>";
}

$iterator = new ArrayIterator($array);
foreach ($iterator as $key => $value){
    echo "iterator_count(): $key: $value (", iterator_count($iterator), ")<br>";
}

/*
outputs:
1: foo (2)
2: bar (2)
1: foo (2)
*/