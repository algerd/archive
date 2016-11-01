<?php
/*
abstract RecursiveFilterIterator extends FilterIterator implements Iterator , Traversable , OuterIterator , RecursiveIterator 
{
	__construct ( RecursiveIterator $iterator )
	void getChildren ( void ) - Возвращает дочерние элементы внутреннего итератора в виде объекта RecursiveFilterIterator
	void hasChildren ( void ) - Проверяет, есть ли у текущего элемента внутреннего итератора дочерние элементы 
			
  Наследуемые методы от FilterIterator
	abstract bool accept ( void )	- Проверяет, является ли текущий элемент итератора допустимым
	Iterator getInnerIterator ( void )		
	mixed current ( void )
	mixed key ( void )
	void next ( void )
	void rewind ( void )
	bool valid ( void )
}
 
Класс RecursiveFilterIterator - Этот абстрактный итератор отфильтровывает нежелательные значения для RecursiveIterator.
Этот класс следует расширять для реализации пользовательских фильтров. 
Метод RecursiveFilterIterator::accept() необходимо реализовывать в подклассе. Он автоматически запускается при итерации
этого итератора в foreach и осуществляет фильтрацию по условиям, прописанным в нём.
Чтобы применить рекурсивно фильтр ко всем элементам рекурсивного итератора,
надо в переопределённом методе accept() сделать проверку на наличие итератора в дочернем элементе и прописать условие проверки к текущему элементу:
	return $this->hasChildren() || условие к $this->current() (см. пример);

Пример работы с многомерным итератором-массивом класса RecursiveArrayIterator:
	$array = array();
	$iterator = new RecursiveArrayIterator($array);
	$iterator = new MyRecursiveFilterIterator($iterator); // наследник RecursiveFilterIterator
	$iterator = new RecursiveIteratorIterator($iterator);
	foreach (iterator as $key=>$value){ };
	unset ($iterator, $array);
// или одной строкой - не создаёт дополнительные переменные-объекты, не надо их потом удалять
	foreach ( new RecursiveIteratorIterator (new MyRecursiveFilterIterator( new RecursiveArrayIterator(array()))) as $key=>$value ){};
*/
/*
$reflection = new ReflectionClass('RecursiveFilterIterator');
var_dump($reflection->getProperties());
var_dump($reflection->getConstants());
var_dump($reflection->getMethods());
echo '<hr>';
*/
// Фильтр со встроенным в accept() условием фильтрации
class TestsOnlyFilter extends RecursiveFilterIterator {
    public function accept() {
        // текущий элемент пройдет фильтр, если имеет дочерние элементы или
        // его значение начинается со строки "test"
        return $this->hasChildren() || (strpos($this->current(), "test") !== FALSE);
    }
}

$array    = array("test1", array("taste2", "st3", "test4"), "test5");
$iterator = new RecursiveArrayIterator($array);
$filter   = new TestsOnlyFilter($iterator);
$filter = new RecursiveIteratorIterator($filter);
foreach($filter as $key => $value){
    $depth = $filter->getDepth();		// показывает степень вложенности текущего элемента итератора (0 это первый уровень)
	echo "depth=$depth k=$key v=$value <br>";
}

echo '<hr>';

// Фильтр с передаваемым извне в переопределяемый конструктор условием фильтрации
class StartsWithFilter extends RecursiveFilterIterator 
{
    protected $word;
	// переопределяем конструктор
    public function __construct (RecursiveIterator $rit, $word) {
        $this->word = $word;
		// надо обязательно подгружать родительский конструктор
        parent::__construct($rit);
    }
	// проверяет есть ли у текущего элемента итератора - дочерний элемент в виде итератора и выполнение условий фильтрации
    public function accept() {
        return $this->hasChildren() OR strpos($this->current(), $this->word) === 0;
    }
    // возвращает в переопределённый конструктор итератор дочернего элемента в виде объекта RecursiveFilterIterator и фильтр
    public function getChildren() {
        return new self($this->getInnerIterator()->getChildren(), $this->word);
    }
}

$array = array("test1", array("taste2", "st3", "test4"), "test5");
$iterator = new RecursiveArrayIterator($array);
$filter = new StartsWithFilter($iterator, "test");	// отобрать элементы, содержащие "test"
$filter = new RecursiveIteratorIterator($filter);
foreach($filter as $key => $value){
    $depth = $filter->getDepth();		// показывает степень вложенности текущего элемента итератора (0 это первый уровень)
	echo "depth=$depth k=$key v=$value <br>";
}