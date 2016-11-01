<?php
/*
interface IteratorAggregate extends Traversable 
{
    abstract public Traversable getIterator ( void ) - возвращает внешний итератор, использующий интерфейсы Iterator или Traversable
    { return объект класса, наследник интерфейса Iterator }
}
 
Интерфейс IteratorAggregate служит для создания внешнего итератора. 
Он построен по принципу шаблонов DI (Aggregate), когда объект класса от Iterator помещается в объект-контейнер от класса IteratorAggregate через getIterator().
Это контейнер-обёртка Итератора (Iterator) с возможностью вызова определённого внешнего итератора через getIterator().
При вызове объекта этого класса в foreach он будет автоматически итерирован в следующем порядке:
- сначала запустится getIterator(), который вернёт итератор
- затем запустится возвращённый итератор, которым будет итерироваться переданный в него массив
При этом в getIterator() через switch или другие инструкции в зависимости от передаваемых данных может выбираться
какой-то определённый итератор из набора разных итераторов.
  
Распространена передача в качестве итератора объект класса new ArrayIterator($array), тем самым задавая какой
массив будет проитерирован при вызове объекта в foreach:

class MyCollection implements IteratorAggregate{
	function getIterator (){
	   return new ArrayIterator($array)
}
*/
/*
$reflection = new ReflectionClass('IteratorAggregate');
var_dump($reflection->getProperties());
var_dump($reflection->getConstants());
var_dump($reflection->getMethods());
echo '<hr>';
*/
////////////////////////////////////////////////////////////////////////////////////////////////////

// Используем итератор из файла Iterator.php
class MyIterator implements Iterator{
    private $array = array();       // здесь хранится обрабатываемый массив
    
    public function __construct($array){
        if (is_array($array)) {
            $this->array = $array;  // инициализирум массив
        }
    }
    // вызывается в самом начале цикла
    public function rewind() {
        var_dump(__METHOD__);
        // reset() — устанавливает внутренний указатель массива на его первый элемент 
        reset($this->array);            // необязательно устанавливать
    }
    public function current() {
        var_dump(__METHOD__);
        // current() - возвращает текущий элемент массива      
        return current($this->array);   // должен возвращать какое-то значение массива на выход foreach
    }
    public function key() { 
        var_dump(__METHOD__);
        // key() возвращает индекс текущего элемента массива     
        return key($this->array);       // должен возвращать какое-то значение ключа на выход foreach
    }
    // вызывается в начале каждого последующего прохода перед проверкой в valid()
    public function next() { 
        var_dump(__METHOD__);
        // производим здесь какие-либо действия перед проверкой в valid()
        // next()- возвращает текущий элемент массива и сдвигает его внутренний указатель на одну позицию
        return next($this->array);   // можно ничего не возвращть - этот метод для действия перед проверкой в valid
    }
    //осуществляет проверку, если возвращает true - цикл продолжается
    public function valid() {
        // проверка наличия элемента в текущей позиции курсора foreach
        $var = current($this->array) !== false;
        var_dump(__METHOD__);
        return $var;        // должен вернуть true чтобы цикл продолжился
    }
}

// Класс-обёртка итератора 
class MyCollection implements IteratorAggregate
{
    private $_array = array();  // здесь будет храниться массив
    private $_count = 0;        // позиция в массиве
    
    // задаём внешний итератор
    public function getIterator() {       
        return new MyIterator($this->_array);   // передаём в итератор MyIterator массив $this->_array
    }
    // добавляем элемент в массив
    public function addArray($value){
        $this->_array[$this->_count] = $value;
        $this->_count++;
        return $this;    // возвращаем объект для последовательного добавления элементов без дополнительного обращения к объекту
    }      
}

// создаём объект коллекции добавляемых элементов
$obj = new MyCollection();
// добавляем элементы
$obj->addArray('Alex')->addArray('John')->addArray(123344)->addArray('root')->addArray('aaa@mail.ru');

// запускаем итератор
foreach ($obj as $key => $value)
    echo "key = $key value = $value<br>";
