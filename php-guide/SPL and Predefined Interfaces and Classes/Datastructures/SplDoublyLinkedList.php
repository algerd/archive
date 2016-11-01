<?php
/*
SplDoublyLinkedList implements Iterator , ArrayAccess , Countable 
{
	__construct ( void )
	void setIteratorMode ( int $mode ) - Устанавливает режим итерации (смотри ниже)	
	int getIteratorMode ( void ) - Возвращает режим итерации
	void add ( mixed $index , mixed $newval ) - Добавляет новое значение $newval в $index, смещая все предыдущие значения перед $index
	void push ( mixed $value )	- Помещает элемент в конец двусвязного списка		
	mixed pop ( void )			- Удаляет (выталкивает) узел, находящийся в конце двусвязного списка		
	void unshift ( mixed $value ) - Вставляет элемент в начало двусвязного списка		
	mixed shift ( void )		- Удаляет узел, находящийся в начале двусвязного списка
	mixed top ( void )			- Получает узел, находящийся в конце двусвязного списка		
	mixed bottom ( void )		- Получает узел, находящийся в начале двусвязного списка		
	void prev ( void )			- Перемещает итератор к предыдущему элементу	
	bool isEmpty ( void )		- Проверяет, является ли двусвязный список пустым
		
  Переопределённые методы интерфейса ArrayAccess - позволяют работать как с массивом			
	void offsetSet ( mixed $index , mixed $newval )		
	mixed offsetGet ( mixed $index )
	bool offsetExists ( mixed $index )
	void offsetUnset ( mixed $index )					
  Переопределённые методы интерфейса Iterator - позволяют итерировать  			
	void rewind ( void )		
	bool valid ( void )	
	mixed key ( void )		
	mixed current ( void )		
	void next ( void )		
  Переопределённые методы интерфейса Serializable - позволяют сериализовать   
    string serialize ( void )                 
    void unserialize ( string $serialized )		
  Переопределённые методы интерфейса Countable
    int count ( void )		
}
Режимы setIteratorMode($mode). Значение $mode: 
    Существуют два ортогональных набора режимов, которые могут быть установлены:
        Направление итерации (одно из двух):
            SplDoublyLinkedList::IT_MODE_LIFO (Стек)
            SplDoublyLinkedList::IT_MODE_FIFO (Очередь)
        Поведение итератора (одно из двух):
            SplDoublyLinkedList::IT_MODE_DELETE (Элементы удаляются итератором)
            SplDoublyLinkedList::IT_MODE_KEEP (Итератор обходит элементы, не удаляя их)
    По умолчанию используется режим: SplDoublyLinkedList::IT_MODE_FIFO | SplDoublyLinkedList::IT_MODE_KEEP

Класс SplDoublyLinkedList обеспечивает основные функциональные возможности двусвязного списка.
Двусвязного списка - это список элементов, к которым можно получить доступ либо с начала списка, либо с конца.
Внутрь списка, подобно массиву, залезть и изменить элемент нельзя.  Добавление и удаление элементов возможно либо с начала списка,
либо с конца. Если добавлять в середину списка, то все элементы будут смещены.
  Разновидности списка:
- очередь: доступ к элементам в том порядке, в каком элементы помещались в список(в очередь)
- стек: доступ к элементам в обратном порядке, в каком элементы помещались в список(в стек)		
*/
/*
$reflection = new ReflectionClass('SplDoublyLinkedList');
var_dump($reflection->getProperties());
var_dump($reflection->getConstants());
var_dump($reflection->getMethods());
echo '<hr>';
*/		
//Сравнение работы Очереди FIFO(First In First Out) и Стека LIFO(Last In First Out)

$list = new SplDoublyLinkedList();
// добавляем в список значения
$list->push('a');
$list->push('b');
$list->push('c');
$list->push('d');

//print_r($list);

echo "FIFO (First In First Out) :\n";
$list->setIteratorMode(SplDoublyLinkedList::IT_MODE_FIFO);
for ($list->rewind(); $list->valid(); $list->next()) {
    echo $list->current()."\n";		// Result FIFO (First In First Out):: a b c d
}
echo "<br>LIFO (Last In First Out) :\n";
$list->setIteratorMode(SplDoublyLinkedList::IT_MODE_LIFO);
for ($list->rewind(); $list->valid(); $list->next()) {
    echo $list->current()."\n";		// Result LIFO (Last In First Out): d c b a
}

$list->add(2 , 'gg');
echo "<br> Добавили в середину очереди перед 2-м индексом: ";
$list->setIteratorMode(SplDoublyLinkedList::IT_MODE_FIFO);
for ($list->rewind(); $list->valid(); $list->next()) {
    echo $list->current()."\n";		// Result FIFO (First In First Out):: a gg b c d
}

$list->unshift('unshift');
$list->pop();
echo "<br> Добавили в начало очереди 'unshift' и вытолкнули с очереди последнее значение 'd':<br>";
foreach ($list as $k=>$v) echo "key=$k val=$v \n"; // FIFO: unshift a gg b c
//print_r($list);
?>