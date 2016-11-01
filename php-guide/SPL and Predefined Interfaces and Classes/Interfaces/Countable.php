<?php
/*
interface Countable
{
	abstract public int count ( void ) - Количество элементов объекта
} 
 
Countable - это интерфейс-счётчик элементов. 
Классы, которые реализуют интерфейс Countable, могут быть использованы с функцией количества элементов объекта count().
*/
/*
$reflection = new ReflectionClass('Countable');
var_dump($reflection->getProperties());
var_dump($reflection->getConstants());
var_dump($reflection->getMethods());
echo '<hr>';
*/
class myCounter implements Countable 
{
	private $count = 0;
	private $array = array();
	
	public function __construct(array $arr) {
		$this->array = $arr;
		$this->count = count($arr);
	}
			
    public function count () {
		return (int)$this->count;
    }
}

$arr = array(2,3,6,3,5,3,2,7,9,5);

// теперь в этом объекте будет хранится счётчик элементов массива. 
// И в случае очередного подсчёта элементов массива, не надо снова вызывать count(), а достаточно обратиться к обэекту-счётчику
$obj = new myCounter($arr);

echo "В массиве {$obj->count()} элементов";

?>
