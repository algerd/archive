<?php
/*
SplFixedArray implements Iterator , ArrayAccess , Countable
{
    __construct ([ int $size = 0 ] ) - 
    int setSize ( int $size )   - Изменяет размер массива   
    int getSize ( void )        - Получает размер массива
    static SplFixedArray fromArray (array $array [,bool $save_indexes = true]) - Импортирует PHP-массив в объект класса SplFixedArray, $save_indexes = true - сохранить числовые индексы массива
    array toArray ( void )      - Возвращает обычный PHP-массив со значениями фиксированного массива
    void __wakeup ( void )      - Возвращает массив после ансериализации
        
  Переопределённые методы интерфейса ArrayAccess - позволяют работать как с массивом			
	void offsetSet ( mixed $index , mixed $newval )		
	mixed offsetGet ( mixed $index )
	bool offsetExists ( mixed $index )
	void offsetUnset ( mixed $index )	      
  Переопределённые методы интерфейса Iterator - позволяют итерировать  			
	void rewind ( void )		
	bool valid ( void )	
	mixed key ( void )		
	mixed current ( void )  - Возвращает текущий элемент массива	
	void next ( void )							
  Переопределённые методы интерфейса Countable
    int count ( void )			    
}
Класс SplFixedArray (непрерывный ограниченный массив) обеспечивает базовую функциональность, предоставляемый массивами. 
Он хранит данные в непрерывном виде, т.е. он заполняет все элементы массива до максимального индекса значениями null, кроме элементов с данными.
Главное различие между SplFixedArray и обычным массивом PHP в том, что SplFixedArray имеет фиксированную длину, 
а в качестве индексов могут выступать только целочисленные значения. Это значительно (в 2-3 раза) сокращает потребление памяти на массив.
Преимущество данных ограничений заключается в более быстрой обработке массива. 
Кроме того, SplFixedArray - это итератор и к нему можно применять все итерирующие итераторы по аналогии с ArrayIterator.
    Работа с объектами класса SplFixedArray идёт как с простым числовым массивом, который имеет ограничение на максимальный числовой индекс массива.
Но это всё равно остаётся объектом и с ним можно работать как с итератором подобно итератору ArrayIterator
 */
/*
$reflection = new ReflectionClass('SplFixedArray');
var_dump($reflection->getProperties());
var_dump($reflection->getConstants());
var_dump($reflection->getMethods());
echo '<hr>';
*/


// Инициализация массива фиксированной длиной
$array = new SplFixedArray(6);

$array[1] = 2;
$array[4] = "foo";

var_dump($array[0]); // NULL
var_dump($array[1]); // int(2)
var_dump($array["4"]); // string(3) "foo"
var_dump($array[5]); // NULL
// Увеличение размера массива до 10
$array->setSize(10);

$array[9] = "asdf";

// Сокращаем размер массива до 2-х
$array->setSize(2);

// Следующий код вызывает исключение RuntimeException: Index invalid or out of range
try {
    var_dump($array["non-numeric"]);
} catch(RuntimeException $re) {
    echo "RuntimeException: ".$re->getMessage()."<br>";
}
try {
    var_dump($array[-1]);
} catch(RuntimeException $re) {
    echo "RuntimeException: ".$re->getMessage()."<br>";
}
try {
    var_dump($array[5]);
} catch(RuntimeException $re) {
    echo "RuntimeException: ".$re->getMessage()."<br>";
}

echo '<hr>';

// преобразуем массив в объект с фиксированной величиной элементов (импортируем массив в объект с сохранением ключей массива - по умолчанию)
$arr = SplFixedArray::fromArray([2,4,5=>5,4,6,7,8,23=>43]);
// добавление новых индексов, превышающих максимальной при импорте массива вызовет ошибку
try {
   //$arr->setSize(31); - не вызовет ошибки
   $arr[30] = 30;
} catch(RuntimeException $re) {
    echo "RuntimeException: ".$re->getMessage()."<br>";
}
// работа как с итератором - ограничение выборки
$iterator = new LimitIterator($arr, 3, 10);
foreach ($iterator as $k => $v) {
    echo "key=$k val=$v <br>";  
}