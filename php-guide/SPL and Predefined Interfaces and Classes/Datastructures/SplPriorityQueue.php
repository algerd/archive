<?php
/*
 SplPriorityQueue implements Iterator , Countable 
{
    __construct ( void )
    int compare (mixed $priority1 , mixed $priority2) -  Сравнивает приоритеты для корректного помещения элементов в очередь 
        Результат сравнения, положительное число, когда priority1 больше priority2, 
        0 если они равны, и отрицательное число в остальных случаях. 
    void insert (mixed $value , mixed $priority) - Добавляет элемент в очередь и пересортирует ее. $priority - приоритет элемента
    mixed extract ( void )              - Извлекает узел из начала очереди и пересортирует ее
    mixed top ( void )                  - Возвращает узел находящийся в начале очереди
    bool isEmpty ( void )               - Проверяет, является ли очередь пустой
    void recoverFromCorruption ( void ) - Восстанавливает корректное состояние очереди
    void setExtractFlags ( int $flags ) - Задает режим извлечения узлов, см ниже flags 
        
  Переопределённые методы интерфейса Iterator - позволяют итерировать  			
	void rewind ( void )		
	bool valid ( void )	
	mixed key ( void )		
	mixed current ( void )		
	void next ( void )	             
  Переопределённые методы интерфейса Countable
    int count ( void )	      
}
flags - Определяет, что будет извлекаться методами SplPriorityQueue::current(), SplPriorityQueue::top() и SplPriorityQueue::extract():
    SplPriorityQueue::EXTR_DATA (0x00000001): Извлекать данные
    SplPriorityQueue::EXTR_PRIORITY (0x00000002): Извлекать приоритет
    SplPriorityQueue::EXTR_BOTH (0x00000003): Извлекать данные и приоритет в виде массива
    По умолчанию работает режим SplPriorityQueue::EXTR_DATA.

Класс SplPriorityQueue обеспечивает основные функциональные возможности приоритетной очереди, реализованный при помощи динамической кучи.
Класс SplPriorityQueue - это гибрид очереди SplQueue и кучи SplHeap.
Фактически он формирует очередь (двухсвязанный список см.SplDoublyLinkedList), отсортированную по заданному приоритету в insert($value, $priority) в порядке убывания приоритета.
Порядок сортировки определяется в методе compare(). 
/*
$reflection = new ReflectionClass('SplPriorityQueue');
var_dump($reflection->getProperties());
var_dump($reflection->getConstants());
var_dump($reflection->getMethods());
echo '<hr>';
*/	

$queue = new SplPriorityQueue();
$queue->insert('A',3);
$queue->insert('B',6);
$queue->insert('C',1);
$queue->insert('D',2);

echo "COUNT->".$queue->count()."<BR>";

//mode of extraction
$queue->setExtractFlags(SplPriorityQueue::EXTR_BOTH);

//Go to TOP
//$queue->top();

while($queue->valid()){
    print_r($queue->current());
    echo "<BR>";
    $queue->next();
}
if($queue->isEmpty()) echo 'Хранилище пусто!';