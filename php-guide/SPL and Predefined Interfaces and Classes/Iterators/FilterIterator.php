<?php
/*
 abstract FilterIterator extends IteratorIterator implements OuterIterator , Traversable , Iterator 
{
	__construct ( Iterator $iterator )
	abstract bool accept ( void )		- Проверяет, является ли текущий элемент итератора допустимым
	
Унаследованные методы IteratorIterator (а тот всвою очередь перегружает OuterIterator и Iterator)
	Iterator getInnerIterator ( void )	- Возвращает внутренний итератор	
	void rewind ( void )
	bool valid ( void )		
	mixed key ( void )
	void next ( void )
	mixed current ( void )	
}
  
Класс FilterIterator - этот абстрактный итератор фильтрует нежелательные значения. Работает с одномерными итераторвми (напр ArrayIterator)
Этот класс следует расширить для реализации пользовательских фильтров итератора. 
Метод FilterIterator::accept() должен быть реализован в подклассе. Он автоматически запускается при итерации
этого итератора в foreach и осуществляет фильтрацию по условиям, прописанным в нём.
 
Пример работы с одномерным итератором класса ArrayIterator:
	$array = array();
	$iterator = new ArrayIterator($array);
	$iterator = new FilterIterator($iterator); // наследник FilterIterator
	foreach (iterator as $key=>$value){ };
	unset ($iterator, $array);
// или одной строкой - не создаёт дополнительные переменные-объекты, не надо их потом удалять
	foreach ( new FilterIterator( new ArrayIterator(array())) as $key=>$value );
*/
/*
$reflection = new ReflectionClass('FilterIterator');
var_dump($reflection->getProperties());
var_dump($reflection->getConstants());
var_dump($reflection->getMethods());
echo '<hr>';
*/
class EvenFilter extends FilterIterator
{
	public function __construct (Iterator $it) {
		parent::__construct($it);
	}

	function accept () {
		if($this->current() % 2 != 0){	// более явно с вызовом внутреннего итератора итератора $this->getInnerIterator()->current()
			return false;
		}
		return $this->current();	
	}
}

$numbers = range(212345, 212377);

// Фильтр-итератор фильтрует переданный итератот-массив на чётность
$even = new EvenFilter(new ArrayIterator($numbers));

foreach($even as $value){
	echo $value.' is even.<br />';
}

echo '<hr>';
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

class UserFilter extends FilterIterator
{
    private $userFilter;
   
    public function __construct(Iterator $iterator , $filter) {
        parent::__construct($iterator);
        $this->userFilter = $filter;
    }
   
    public function accept () {
        $user = $this->getInnerIterator()->current();
		// Бинарно-безопасное сравнение строк без учета регистра
        if (strcasecmp($user['name'],$this->userFilter) == 0) return false;      
        return true;
    }
}

$array = array(
	array('name' => 'Jonathan','id' => '5'),
	array('name' => 'Abdul' ,'id' => '22')
);

$object = new ArrayIterator($array);
$iterator = new UserFilter($object,'jonathan'); // Фильтр-итератор отфильтровывает передаваемую строку из итератора-массива, используя Бинарно-безопасное сравнение строк
foreach ($iterator as $result) {
    echo $result['name'];
}

?>