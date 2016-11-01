<?php
/*
interface Serializable 
{
	abstract public string serialize ( void )				- Возращает строковое представление объекта. 
	abstract public void unserialize ( string $serialized )	- Десеарилизует строковое представление объекта $serialized
}

Serializable - интерфейс для индивидуальной сериализации.
Классы, которые реализуют этот интерфейс не поддерживают больше __sleep() и __wakeup(). 
Метод serialize вызывается всякий раз, когда необходима сериализация экземпляру класса. Этот метод не вызывает __destruct() и не имеет никаких побочных действий кроме тех, которые запрограммированы внутри него. 
Когда данные десериализованы, класс известен и соответствующий метод unserialize() вызывается как конструктор вместо вызова __construct(). 
Если вам необходимо вызвать стандартный конструктор, вы можете это сделать в этом методе.
 
В отдичие от __sleep() и __wakeup() позволяет более гибко управлять сериализацией, напр. при работе с наследуемыми классами (см. Serializable_example.php)
*/
/*
$reflection = new ReflectionClass('Serializable');
var_dump($reflection->getProperties());
var_dump($reflection->getConstants());
var_dump($reflection->getMethods());
echo '<hr>';
*/

class MyClass implements Serializable {
    private $data;
    public function __construct() {
        $this->data = "My private data";
    }
	// автоматически!!! вызывается при попытке сериализации объекта этого класса
    public function serialize() {
		echo 'Объект класса '.__CLASS__.' сериализован<br>';
        return serialize($this->data);
    }
	// автоматически!!! вызывается при десериализации объекта этого класса
    public function unserialize($data) {
		echo 'Объект класса '.__CLASS__.' десериализован<br>';
        $this->data = unserialize($data);
    }
    public function getData() {
        return $this->data;
    }
}

$obj = new MyClass;
$ser = serialize($obj);			//сериализуем объект - вызов метода $obj->serialize()
var_dump($ser);

$newobj = unserialize($ser);	//десериализуем объект - вызов метода $obj->unserialize()
var_dump($newobj->getData());

?>
