<?php
/*
interface RecursiveIterator extends Iterator {

	public RecursiveIterator getChildren ( void )	- Возвращает итератор для текущего элемента
	public bool hasChildren ( void )				- Определяет, можно ли для текущего элемента создать итератор

  Наследуемые от Iterator методы
	abstract public void rewind ( void )
	abstract public void next ( void )
	abstract public boolean valid ( void )
	abstract public scalar key ( void )
	abstract public mixed current ( void )	
} 
 
Это расширение интерфейса Iterator, которое добавляет возможность работы с многомерными массивами.
Классы, реализующие RecursiveIterator, могут быть использованы для рекурсивного перебора итераторов.
Они работают по аналогии с Iterator (см. Iterator.php), но в отличие от него может итерировать многомерные
массивы благодаря двум методам 	hasChildren	() и getChildren()
*/
/*
$reflection = new ReflectionClass('RecursiveIterator');
var_dump($reflection->getProperties());
var_dump($reflection->getConstants());
var_dump($reflection->getMethods());
echo '<hr>';
*/

class MyRecursiveIterator implements RecursiveIterator
{
    private $_data;
    private $_position = 0;
	
    public function __construct(array $data) {
        $this->_data = $data;
    }
	// Проверяет есть ли в текущем элементе подмассив
    public function hasChildren() {
        return is_array($this->_data[$this->_position]);
    }
	// Выводит подмассив текущего элемента
    public function getChildren() {
        echo '<pre>';
        print_r($this->_data[$this->_position]);
        echo '</pre>';
    }
	
	public function rewind() {
        $this->_position = 0;
    }
	
	public function next() {
        $this->_position++;
    }
	
    public function valid() {
        return isset($this->_data[$this->_position]);
    }
	 
    public function key() {
        return $this->_position;
    }
	
    public function current() {
        return $this->_data[$this->_position];
    }
}
////////////////////////////////////////////////////////////////////////////////////////////////////

$arr = array(0, 1, 2, 3, 4, 5 => array(10, 20, 30), 6, 7, 8, 9 => array(1, 2, 3));
$mri = new MyRecursiveIterator($arr);

foreach ($mri as $c => $v) {
	// Проверяет есть ли в элементе подмассив
    if ($mri->hasChildren()) {
        echo "$c has children: <br />";
		// Выводит подмассив
        $mri->getChildren();
    } else {
        echo "$v <br />";
    }
}
?>