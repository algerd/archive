<?php
/*
SplQueue extends SplDoublyLinkedList implements Iterator , ArrayAccess , Countable 
{
	__construct ( void )			
	void enqueue ( mixed $value )	- Добавляет элемент в очередь
	mixed dequeue ( void )			- Удаляет элемент из очереди
	void setIteratorMode ( int $mode ) - Устанавливает режим итератора см.ниже
  Наследует все методы от SplDoublyLinkedList
}
 $mode - Поведение итератора (либо одно, либо другое):
	SplDoublyLinkedList::IT_MODE_DELETE (Элементы удаляются итератором)
	SplDoublyLinkedList::IT_MODE_KEEP (Итератор обходит элементы, не удаляя их)
	По умолчанию используется режим: SplDoublyLinkedList::IT_MODE_FIFO | SplDoublyLinkedList::IT_MODE_KEEP 

Класс SplQueue предоставляет основные функциональные возможности очереди, реализованные с использованием двусвязного списка.
Он расширяет универсальный класс двусвязного списка SplDoublyLinkedList, имея свою чёткую специализацию	- формирование очереди.	
*/
/*
$reflection = new ReflectionClass('SplQueue');
var_dump($reflection->getProperties());
var_dump($reflection->getConstants());
var_dump($reflection->getMethods());
echo '<hr>';
*/		 
// Примеры применения аналогичны SplDoublyLinkedList, но только для режима - очередь
$q = new SplQueue();
$q->push(1);
$q->push(2);
$q->push(3);
$q->push(4);
$q->enqueue(5);
$q->enqueue(6);
$q->enqueue(7);
$q->enqueue(8);

$q->pop();			// убирает элемент с конца очереди 
$q->dequeue();		// убирает элемент с начала очереди
unset($q[3]);		// удаляет произвольный элемент, смещая всю очередь
$q->add(3, 'add');

echo 'top:'.$q->top().' ';
echo 'bottom: '.$q->bottom().'<br>';

foreach ($q as $k=>$v) 
	echo "key=$k val=$v <br>"; // FIFO(First In First Out) 

$empty = new SplQueue();
if ( !$empty->isEmpty() ) $empty->pop(); 

?>