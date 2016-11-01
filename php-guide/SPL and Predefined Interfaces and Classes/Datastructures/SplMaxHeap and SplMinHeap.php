<?php
/*
SplMaxHeap extends SplHeap implements Iterator , Countable 
{
  Переопределённый метод от SplHeap
	int compare ( mixed $value1 , mixed $value2 ) - Сравнивает элементы, чтобы отсортировать элементы в куче по убыванию
  
  Наследует все методы от SplHeap
}
Класс SplMaxHeap предоставляет основные функциональные возможности кучи, сохраняя максимальный элемент наверху.
SplMaxHeap реализует абстрактный класс SplHeap, сортируя элементы в куче по убыванию.

--------------------------------------------------------------------------------------------

SplMinHeap extends SplHeap implements Iterator , Countable 
{
  Переопределённый метод от SplHeap
	int compare ( mixed $value1 , mixed $value2 ) - Сравнивает элементы, чтобы отсортировать элементы в куче по возрастанию
  
  Наследует все методы от SplHeap
}
Класс SplMaxHeap предоставляет основные функциональные возможности кучи, сохраняя минимальный элемент наверху.
SplMaxHeap реализует абстрактный класс SplHeap, сортируя элементы в куче по возрастанию.
*/
/*
$reflection = new ReflectionClass('SplMaxHeap');
var_dump($reflection->getProperties());
var_dump($reflection->getConstants());
var_dump($reflection->getMethods());
echo '<hr>';
*/

$heap = new SplMaxHeap(); # Ascending order
$heap->insert('E');
$heap->insert('B');
$heap->insert('D');
$heap->insert('A');
$heap->insert('C');

echo 'Извлекаем '.$heap->extract().'<br>'; // E
echo 'Извлекаем '.$heap->extract().'<br>'; // D

foreach ($heap as $value) {
	echo "$value <br>";
}
echo '<hr>';

$heap = new SplMinHeap();
$heap->insert('E');
$heap->insert('B');
$heap->insert('D');
$heap->insert('A');
$heap->insert('C');

echo 'Извлекаем '.$heap->extract().'<br>'; // A
echo 'Извлекаем '.$heap->extract().'<br>'; // B

foreach ($heap as $value) {
	echo "$value <br>";
}