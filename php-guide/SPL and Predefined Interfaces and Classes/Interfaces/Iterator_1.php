<?php
/*
interface Iterator extends Traversable 
{     
    abstract public void rewind ( void )    вызывается в самом начале цикла (в начале первого прохода)      
    abstract public void next ( void )      вызывается в самом начале каждого последующего прохода для действий перед проверкой  
    abstract public boolean valid ( void )  осуществляет проверку 
    abstract public mixed current ( void )  возвращает значения элемента массива
    abstract public scalar key ( void )     возвращает ключ элемента массива    
} 

Итератор подобен блоку каких-то действий (проверки, присвоения, расчёты, циклы и т.д.) внутри {...} цикла foreach
И если таких действий внутри foreach много, то для облегчения кода foreach лучше использовать итератор. Как говорится,
мухи (действия) отдельно, а котлеты (foreach) отдельно.
Все пользовательские функции обработки массивов целесообразно делать с помощью spl-объектов
 
Интерфейс Iterator - основа для создания классов с возможностью их итерации (перебора их свойств и методов) в цикле foreach.
При вызове итератора интерфейса Iterator в foreach автоматически происходит перебор его методов по кругу rewind->valid->key->current->next->valid->...
*/
/*
$reflection = new ReflectionClass('Iterator');
var_dump($reflection->getProperties());
var_dump($reflection->getConstants());
var_dump($reflection->getMethods());
echo '<hr>';
*/
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
////////////////////////////////////////////////////////////////////////////////////////////////////

$values1 = array(10,20,30);
// создаём объект-итератор с массивом $values1 (объект обработки массива $values1)
$it = new MyIterator($values1);
// запускаем итерацию
foreach ($it as $key => $value)
    echo "key = $key value = $value<br>";

echo '<hr>';

$values2 = array('a'=> 'aaa','b'=>'bbb','c'=>'ccc');   
// создаём объект-итератор с массивом $values2 (объект обработки массива $values2)
$it = new MyIterator($values2);
// запускаем итерацию
foreach ($it as $key => $value)
    echo "key = $key value = $value<br>";