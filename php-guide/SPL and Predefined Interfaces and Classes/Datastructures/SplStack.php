<?php
/* 
SplStack extends SplDoublyLinkedList implements Iterator , ArrayAccess , Countable 
{
	__construct ( void )
	void setIteratorMode ( int $mode ) - Устанавливает режим итератора см.ниже
  Наследует все методы от SplDoublyLinkedList
}
$mode - Поведение итератора (либо одно, либо другое):
	SplDoublyLinkedList::IT_MODE_DELETE (Элементы удаляются итератором)
	SplDoublyLinkedList::IT_MODE_KEEP (Итератор обходит элементы, не удаляя их)
    По умолчанию используется режим 0x2 : SplDoublyLinkedList::IT_MODE_LIFO | SplDoublyLinkedList::IT_MODE_KEEP 

Класс SplStack предоставляет основные функциональные возможности стека, реализованные с использованием двусвязного списка.
Он расширяет универсальный класс двусвязного списка SplDoublyLinkedList, имея свою чёткую специализацию	- формирование стека.	
*/
/*
$reflection = new ReflectionClass('SplStack');
var_dump($reflection->getProperties());
var_dump($reflection->getConstants());
var_dump($reflection->getMethods());
echo '<hr>';
*/		
// Примеры применения аналогичны SplDoublyLinkedList, но только для режима - очередь
$q = new SplStack();
$q->push(1);
$q->push(2);
$q->push(3);
$q->pop();
foreach ($q as $k=>$v) echo "key=$k val=$v <br>"; // LIFO (Last In First Out): 2 1 

?>
		
		
		