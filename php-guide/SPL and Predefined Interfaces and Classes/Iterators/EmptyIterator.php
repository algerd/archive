<?php
/*
EmptyIterator implements Iterator , Traversable 
{
	void current ( void )
	void key ( void )
	void next ( void )
	void rewind ( void )
	void valid ( void )
}
Класс EmptyIterator для пустого итератора. Он создаёт пустую переменную требуемого типа - итератор класса Iterator.
Это аналогично пустому массиву $arr = array() или пустому классу $obj = new stdClass().
Это может пригодиться для каких-то проверок типа или наследования.
*/

$iterator = new EmptyIterator(); // здесь хранится объект типа Iterator
?>