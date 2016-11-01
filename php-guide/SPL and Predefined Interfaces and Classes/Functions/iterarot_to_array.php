<?php
/*
array iterator_to_array ( Traversable $iterator [, bool $use_keys = true ] )
	$iterator -Копируемый итератор.
	$use_keys - Следует ли использовать ключи элементов итератора как индексы.
	Возвращаемые значения: Массив (array), содержащий элементы итератора (iterator).
		
iterator_to_array — Копирует итератор в массив		
Копирует элементы итератора в массив. Фактически это обратное преобразование итератора в массив.
*/
		
$iterator = new ArrayIterator(array('recipe'=>'pancakes', 'egg', 'milk', 'flour'));
var_dump(iterator_to_array($iterator, true));
var_dump(iterator_to_array($iterator, false));

