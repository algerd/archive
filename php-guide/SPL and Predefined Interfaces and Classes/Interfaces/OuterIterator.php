<?php
/*
interface OuterIterator extends Iterator 
{
	public Iterator getInnerIterator ( void )	-	Возвращает внутренний итератор для текущего элемента
  Наследуемые методы от Iterator
	abstract public void Iterator::rewind ( void )		
	abstract public boolean Iterator::valid ( void )		
	abstract public scalar Iterator::key ( void )		
	abstract public mixed Iterator::current ( void )
	abstract public void Iterator::next ( void )	
} 
 
Классы интерфейса OuterIterator - это расширение Iterator, они могут быть использованы для перебора итераторов
Он является развитием простого итератора Iterator с добавлением метода getInnerIterator().
Он предоставляет чертёж расширяемого функционала простого итератора. На основе его создан класс IteratorIterator, который
в свою очередь был расширен в FilterIterator и LimitIterator. 
*/
/*
$reflection = new ReflectionClass('OuterIterator');
var_dump($reflection->getProperties());
var_dump($reflection->getConstants());
var_dump($reflection->getMethods());
echo '<hr>';
*/
// Большая часть примера взята с Iterator.php

class MyIterator implements OuterIterator {
	private $count = 0;				// счётчик итераторов
    private $arrit = array();       // здесь хранится массив итераторов
	private $arrcurrent = array();  // здесь хранится массив элементов текущего итератора
   		
    public function __construct($array) {
		foreach ($array as $val){			
			if($val instanceof Iterator) $this->arrit[] = $val;	
		}	
    }
	// Возвращает внутренний итератор
	public function getInnerIterator() {
		var_dump(__METHOD__);
		if (isset($this->arrit[$this->count])) {
			$arrit = $this->arrit[$this->count];
			$this->arrcurrent = (array)$arrit;
			$this->count++;
			return $arrit;
		} else {
			$this->count = 0;
			return false;
		}	
	} 	
    // вызывается в самом начале цикла
    public function rewind() {
        var_dump(__METHOD__);
        // reset() — устанавливает внутренний указатель массива на его первый элемент 
        reset($this->arrcurrent);            // необязательно устанавливать
    }
	
    public function current() {
        var_dump(__METHOD__);
        // current() - возвращает текущий элемент массива      
        return current($this->arrcurrent);   // должен возвращать какое-то значение массива на выход foreach
    }
	
    public function key() { 
        var_dump(__METHOD__);
        // key() возвращает индекс текущего элемента массива     
        return key($this->arrcurrent);       // должен возвращать какое-то значение ключа на выход foreach
    }
    // вызывается в начале каждого последующего прохода перед проверкой в valid()
    public function next() { 
        var_dump(__METHOD__);
        return next($this->arrcurrent);		// можно ничего не возвращть - этот метод для действия перед проверкой в valid
    }
    //осуществляет проверку, если возвращает true - цикл продолжается
    public function valid() {
        // проверка наличия элемента в текущей позиции курсора foreach
        $var = current($this->arrcurrent) !== false;
        var_dump(__METHOD__);
        return $var;        // должен вернуть true чтобы цикл продолжился
    }
}
////////////////////////////////////////////////////////////////////////////////////////////////////

$it1 = new ArrayIterator(array(10, 20, 30));
$it2 = new ArrayIterator(array(40, 50, 60));
$it3 = new ArrayIterator(array(70, 80, 90));

// передаём массив итераторов в итератор класса MyIterator
$outit = new MyIterator(array($it1, $it2, $it3));

// пока возвращается итератор - перебирать итератор $outit
while ($outit->getInnerIterator()) {
	foreach ($outit as $key => $value) echo "key = $key value = $value<br>";
	echo '<hr>';
}

?>