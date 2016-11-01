<?php
set_time_limit(120); 
// время начала скрипта
$time_start = time();
// начальное состояние памяти
$start = memory_get_usage();


class Test {
     private $var;
     function __construct( $var ) {
         $this->var = $var;
     }
}

// Блок Array
$array = array();
$arrayIterator = new ArrayIterator([]);
$arrayObject = new ArrayObject([]);

// Блок SPL-Datastructure:
// Array
//$splFixedArray = new SplFixedArray(200001);   // пустой отжирает 4 байта на пустую ячейку, т.е. 0.8 mb
// Map
$splObjectStorage = new SplObjectStorage();
// Linked
$splDoublyLinkedList = new SplDoublyLinkedList();
$splQueue = new SplQueue();
$splStack = new SplStack();
// Heap
$splPriorityQueue = new SplPriorityQueue();
$splMaxHeap = new SplMaxHeap();
$splMinHeap = new SplMinHeap();


    
for ($i = 0; $i <= 200000; $i++) { 
    // Standart Array
    //$array[] = new Test($i);                  //  39.8мb    2s
    //$arrayIterator->append( new Test($i) );   //  39.8мb    2-3s
    //$arrayObject->append( new Test($i) );     //  39.8мb    2-3s
  
    // Array Fixad
    //$splFixedArray[$i] = new Test($i);        //  29.9mb    1s
 
    // Map - ObjectStorage
    //$splObjectStorage->attach(new Test($i));  //  72.3mb    4s
   
    // Linked
    //$splDoublyLinkedList->push(new Test($i)); //  33.9mb    2-3s
    //$splQueue->push(new Test($i));            //  33.9mb    2-3s
    //$splStack->push(new Test($i));            //  33.9mb    2-3s
    
    // Heap
    //$splPriorityQueue->insert(new Test($i), null);//83mb    2-3s
    //$splMaxHeap->insert( new Test($i) );      //   30.2mb   2-3s
    //$splMinHeap->insert( new Test($i) );      //   30.2mb   2-3s
}

//print_r($array);
//print_r($splObjectStorage);

// потребление памяти
echo 'memory = '.(memory_get_usage() - $start).' bytes<br>';
// продолжительность скрипта
echo 'time_script = '.(time() - $time_start).' sec<br>';

/*
1. Самый быстрый на запись и меньше всех потребляет память SplFixedArray(). Но только в случае его заполнения,
   если он пустой, он всё-равно размечает память под ячейки и потому потребляет много памяти по сравнению с пустыми другими хранилищами.
  Следует использовать при значительном числе элементов массива > 1000
    
2. $SplObjectStorage хранит дополнительную инфу (hash) объектов-ключей и резервирует место под значения, поэтому память он отжирает заметно больше.
    Следует использовать только в тех случаях, когда надо сохранить информацию связанную с объектом (объект => инфа)

3. Array, ArrayIterator и ArrayObject одинаково размечают память, поэтому кол-во потребляемой памяти одинаково.
   Array немного выигрывет в быстродействии, но совсем несущественно. По потреблению памяти проигрывают Спискам, Куче и фиксированному массиву,
   но обладают несравнимым преимуществом в удобстве работы. 
    
4. Heap отнимает меньше памяти, но в случае вставок/замен элементов будет отнимать время на пересортировку, но хорош при выборке данных.
    Очень полезен для хранения отсортированных массивов, в которых не требуется часто делать вставки.
    
5. Linked тоже требует время на перезапись связей с соседними элементвми при вставке/замене, но хорош при выборке данных.
    Для решения специфичных задач, где требуется порядок выборки данных.
    
6. SplPriorityQueue содержит дополнительную инфу, что утяжеляет память. Полезен, когда требуется специфичная сортировка.
*/