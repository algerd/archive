<?php
/*
int iterator_apply ( Traversable $iterator , callable $function [, array $args ] )
	$iterator - объект класса Traversable для перебора.
	$function - Функция обратного вызова, которая применяется к каждому элементу.
		Функция должна возвращать TRUE для того, чтобы продолжать процесс итерации над iterator. 
		$args - Массив аргументов для передачи в функцию обратного вызова.
 
iterator_apply - вызывает функцию для каждого элемента в итераторе. Позволяет аналогично foreach обходить итератор,
				передавая кажый элемент итератора в callback функцию. Более гибко и изящно заменяет стандартный foreach.

int iterator_apply( Traversable $iterator , callable $function [, array $args ] )

Он работает на подобие foreach($iterator as $var), в теле которого будет указываемая в iterator_apply функция.
!!! Поэтому для итераторов предпочтительнее использовать iterator_apply() вместо foreach !!!

*/

function print_caps(Iterator $iterator, $empty) {
    echo $iterator->key().' => '.strtoupper($iterator->current()).'<br>';
    return TRUE;
}

$empty = 0; // пустая переменная для демонстрации передачи аргументов
$it = new ArrayIterator(array("Apples", "Bananas", "Cherries"));
iterator_apply($it, "print_caps", array($it, $empty));

echo '<hr>';

// Аналог iterator_apply  - foreach, в теле которого будет указываемая в iterator_apply функция:
foreach ($it as $key => $var){
	echo $key.' => '.strtoupper($var).'<br>';
}

?>