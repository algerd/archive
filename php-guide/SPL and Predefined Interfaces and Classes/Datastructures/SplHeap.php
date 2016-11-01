<?php
/*
abstract SplHeap implements Iterator , Countable 
{
	__construct ( void )
	abstract int compare (mixed $value1 , mixed $value2) -  Сравнивает элементы, чтобы во время сортировки корректно разместить их в куче
		Метод должен возвращать положительное значение, когда value1 больше value2, 
		0 если они равны, и отрицательное в остальных случаях. 	
	void insert ( mixed $value )- Вставляет элемент в кучу и пересортирует ее		
	mixed extract ( void )		- Извлекает узел из кучи и пересортирует ее 
	mixed top ( void )			- Возвращает узел находящийся на вершине кучи
	bool isEmpty ( void )		- Проверка, пуста ли куча
	void recoverFromCorruption ( void ) - Восстанавливает корректное состояние кучи
	
  Переопределённые методы интерфейса Iterator - позволяют итерировать  			
	void rewind ( void )		
	bool valid ( void )	
	mixed key ( void )		
	mixed current ( void )		
	void next ( void )							
  Переопределённые методы интерфейса Countable
    int count ( void )			
}
Абстрактный класс SplHeap - чертёж реализации структуры хранения данных "куча".
Все элементы сортируются в хранилище согласно своим значениям. 
При этом для сравнения используется внедренный метод сравнения compare() , который является общим для всей кучи. 
При вставлении нового элемента в кучу, хранилище автоматически пересортируется. 
Извлечение элементов происходит в порядке отсортированного хранилища.
*/
/*
$reflection = new ReflectionClass('SplHeap');
var_dump($reflection->getProperties());
var_dump($reflection->getConstants());
var_dump($reflection->getMethods());
echo '<hr>';
*/

class JupilerLeague extends SplHeap
{
    public function compare($array1, $array2){
        $values1 = array_values($array1);
        $values2 = array_values($array2);
        if ($values1[0] === $values2[0]) return 0;
        return $values1[0] < $values2[0] ? -1 : 1;
    }
}

// Let's populate our heap here (data of 2009)
$heap = new JupilerLeague();
$heap->insert(array ('AA Gent' => 15));
$heap->insert(array ('Anderlecht' => 20));
$heap->insert(array ('Cercle Brugge' => 11));
$heap->insert(array ('Charleroi' => 12));
$heap->insert(array ('Club Brugge' => 21));
$heap->insert(array ('G. Beerschot' => 15));
$heap->insert(array ('Kortrijk' => 10));
$heap->insert(array ('KV Mechelen' => 18));
$heap->insert(array ('Lokeren' => 10));
$heap->insert(array ('Moeskroen' => 7));
$heap->insert(array ('Racing Genk' => 11));
$heap->insert(array ('Roeselare' => 6));
$heap->insert(array ('Standard' => 20));
$heap->insert(array ('STVV' => 17));
$heap->insert(array ('Westerlo' => 10));
$heap->insert(array ('Zulte Waregem' => 15));

// For displaying the ranking we move up to the first node
$heap->top();
// извлекаем элементы с кучи
foreach ($heap as $val){
	$arr = each($val);
	echo "$arr[0] : $arr[1] <br>";
}

if($heap->isEmpty()) echo 'Хранилище пусто!';
