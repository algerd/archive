<?php
/*
 * Пример реализации пользовательской функции, работающей с массивом, через итератор.
 * foreach пробегает по итератору в прелелах от $_start до $_end и каждое текущее значение $this->_cur возводит в квадрат
 * Можно было бы сделать всё в foreach, но в случае повторного foreach в другом месте код пришлось бы повторить.
 * Тогда можно было бы сделать через функцию, но внутри функции пришлось бы делать foreach и создавать возвращаемый массив, который пришлось бы снова прогонять через foreach для вывода.
 * А через итератор и foreach чистый, и дополнительные массивы и foreachы не громоздим. Да и код выглядит изящнее.
 */

class NumberSquared implements Iterator
{
    private $_start;    // начальное значение 
    private $_end;      // текущее значение
    private $_cur;      // конечное значение
 
    public function __construct($s, $e){
        $this->_start = $s;
        $this->_end = $e;  
    }
    public function rewind() {
        $this->_cur = $this->_start;
    }
    public function key() {
        return $this->_cur;
    }
    // возводим в квадрат текущее значение
    public function current() {
        return pow($this->_cur, 2);
    }
    public function next() {
        return $this->_cur++;
    } 
    // если текущее значение меньше конечного - продолжаем цикл
    public function valid() {
        return $this->_cur <= $this->_end;
    }
}

// создаём итератор и задаём ему условия итерации (в нашем случае пределы от 2 до 5) 
$nums = new NumberSquared(2, 5);
// запускаем итерацию - чистый красивый код вывода
foreach ($nums as $a => $b){
    echo "Квадрат числа $a = $b <br>";
}
?>