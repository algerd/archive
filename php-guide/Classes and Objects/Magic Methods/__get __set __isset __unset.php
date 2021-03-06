<?php
/* 
Методы-перехватчики свойств - перехватывают сообщения, посланные неопределённым (т.е. несуществующим) свойствам.
Использовать эти костыли не рекомендуется!!! Если возникает необходимость их применения - это сигнал для исправления кода.
Кроме того, эти методы снижают быстродействие кода. 
Но если без них в коде обойтись нельзя, то их применение надо тщательно документировать!		

		
Все методы перегрузки должны быть объявлены как public. 
	
	public void __set (string $property , mixed $value) - вызывается, когда неопределённому свойству присваивается значение
	public mixed __get (string $property)	- вызывается при обращении к неопределённому свойству
	public bool __isset (string $property)	- вызывается, когда функция isset() вызывается для неопределённого свойства
	public void __unset (string $property)	- вызывается, когда функция unset() вызывается для неопределённого свойства
				
Данные магические методы не будут вызваны в статическом контексте. Поэтому данные методы не должны объявляться статичными.
*/

class PropertyTest 
{   
    private $data = array();	// Место хранения перегружаемых данных 
  
	// запись несуществующего свойства
    public function __set ($name, $value) {
        echo "Установка '$name' в '$value'\n";
        $this->data[$name] = $value;
    }
	// обращение к несуществующему свойству
    public function __get($name) {
        echo "Получение '$name'\n";
        if (array_key_exists($name, $this->data)) {
            return $this->data[$name];
        }

        $trace = debug_backtrace();
        trigger_error(
            'Неопределенное свойство в __get(): ' . $name .
            ' в файле ' . $trace[0]['file'] .
            ' на строке ' . $trace[0]['line'],
            E_USER_NOTICE);
        return null;
    }
	// проверка существования несуществующего свойства
    public function __isset($name) {
        echo "Установлено ли '$name'?\n";
        return isset($this->data[$name]);
    }
	// удаление несуществующего свойства
    public function __unset($name) {
        echo "Уничтожение '$name'\n";
        unset($this->data[$name]);
    }
}

echo "<pre>\n";

$obj = new PropertyTest;

$obj->a = 1;			// запись несуществующего свойства
echo $obj->a . "\n\n";	// обращение к несуществующему свойству

var_dump(isset($obj->a));	// проверка существования несуществующего свойства
unset($obj->a);				// удаление несуществующего свойства
var_dump(isset($obj->a));

?>