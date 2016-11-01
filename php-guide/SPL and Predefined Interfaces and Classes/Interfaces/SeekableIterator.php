<?php
/*
interface SeekableIterator extends Iterator 
{
	abstract public void seek ( int $position ) - Перемещает итератор к заданной позиции. 
			
  Наследуемые методы от Iterator
	public void rewind ( void )     вызывается в самом начале цикла (в начале первого прохода)      
    public void next ( void )       вызывается в самом начале каждого последующего прохода для действий перед проверкой  
    public boolean valid ( void )   осуществляет проверку 
    public mixed current ( void )   возвращает значения элемента массива
    public scalar key ( void )      возвращает ключ элемента массива    
} 
 
Интерфейс SeekableIterator - это расширение интерфейса Iterator, которое 
добавляет возможность перемещения итератора к заданной позиции.
*/
/*
$reflection = new ReflectionClass('SeekableIterator');
var_dump($reflection->getProperties());
var_dump($reflection->getConstants());
var_dump($reflection->getMethods());
echo '<hr>';
*/
class MySeekableIterator implements SeekableIterator {

    private $position = 0;
    private $array = array();
	
	public function __construct($arr) {
        $this->position = 0;
		$this->array = $arr;
    }
	// устанавливаем позицию итератора
	public function seek($position) {
      $this->position = $position;
      
      if (!$this->valid()) {
          throw new OutOfBoundsException("invalid seek position ($position)");
      }
    }
	/* Перегружаем методы интерфейса Iterator */   
    public function rewind() {
        $this->position = 0;
    }

    public function current() {
        return $this->array[$this->position];
    }

    public function key() {
        return $this->position;
    }

    public function next() {
        ++$this->position;
    }

    public function valid() {
        return isset($this->array[$this->position]);
    }
}

$arr = array("first element", "second element", "third element", "fourth element");
try {
    $it = new MySeekableIterator($arr);
    echo $it->current(), "<br>";
    
    $it->seek(2);
    echo $it->current(), "<br>";
    
    $it->seek(1);
    echo $it->current(), "<br>";
    
    $it->seek(10);    
} catch (OutOfBoundsException $e) {
    echo $e->getMessage();
}

?>