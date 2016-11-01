<?php
/*
 * Есть набор типовых шаблонов методов, которые часто встречаются в классах 
 * Напр. ввод-вывод приватных свойств класса с типовыми именами
 * Можно даже сделать целую библиотеку трейт-шаблонов, которые потом будут подключаться в классах.
 * Это сильно разгрузит код классов, но при этом усложнит их понимание и тестирование. 
 * А засчёт увеличения переходов к папкам снизится производительность (подобно частым инклудам)
 */
trait SetGetName
{
    public function getName(){
        return $this->name;
    }
    private function setName($name){
        echo $this->name = $name;
    }
}

trait SetGetAge
{
    public function getAge(){
        return $this->age;
    }
    private function setAge($age){
        echo $this->name = $age;
    }
}
/*
 * И тогда классы будут подключать шаблоны методов как простые функции
 */

class People {
	use SetGetName, SetGetAge;
	private $name;
	private $age;			
}
class Animal {
	use SetGetName, SetGetAge;
	private $name;
	private $age;			
}
class Book {
	use SetGetName;
	private $name;
}

/*
 * А можно делать шаблоны свойств c методами их ввода и вывода:
 */
trait PropertyName
{
	private $name;
    public function getName(){
        return $this->name;
    }
    private function setName($name){
        echo $this->name = $name;
    }
}
trait PropertyAge
{
	private $age;
    public function getAge(){
        return $this->age;
    }
    private function setAge($age){
        echo $this->age = $age;
    }
}
// вместо 14 строк в классе на 2 свойства мы получаем только 1 строку! И чем больше таких свойств тем больше выигрыш (10 свойств = 70 строк -> 1строка)
class User
{
	use PropertyName, PropertyAge;
	public function __construct ($name, $age){
		$this->name = $name;
		$this->age = $age;
	}  
}

$user1 = new User('Alex', 30);
echo $user1->getName().'<br>';
echo $user1->getAge().'<br>';