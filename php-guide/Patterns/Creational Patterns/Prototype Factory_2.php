<?php
/*
 * Пример взят с Abstract Factory_2 (поубираем лишние методы и все свойства сделаем публичными, чтобы код был понятнее)
 * Благодаря клонированию сохраняются в клонах значения свойств клонируемых объектов, что добавляет гибкость, но и накладывает ограничения. 
 * Недостатки паттерна Prototype:
 * Каждый тип создаваемого продукта должен реализовывать операцию клонирования clone(). 
 * В случае, если требуется глубокое копирование объекта (объект содержит ссылки или указатели на другие объекты), 
 * это может быть непростой задачей.
 */
// Дополнительный класс - командир
class Commander{
    public $name;
    public function __construct($name){ $this->name = $name; }
}

interface InterfaceArmy{}
abstract class Infantryman implements InterfaceArmy{}
abstract class Archer implements InterfaceArmy{}
abstract class Horseman implements InterfaceArmy{}

// Классы всех видов воинов Римской армии
interface RomanArmy {}
class RomanInfantryman extends Infantryman implements RomanArmy {
    public $commander;      // объект командир
	public $info;           // количество воинов
    public function __construct($info, $name){
        $this->info = $info;
        $this->commander = new Commander ($name);        
    }
    public function __clone(){
		// следует прописать клонирование всех свойств-объектов класса, который будет прототипом. Это большой минус, ограничивающий приминение этого шаблона
        //$this->commander = clone $this->commander; // закомментирован для показа работы прототипа без клонирования свойств-объектов     
    }        
}  
class RomanArcher extends Archer implements RomanArmy {} 
class RomanHorseman extends Horseman implements RomanArmy {}

// Классы всех видов воинов армии Карфагена
interface CarthaginianArmy {}
class CarthaginianInfantryman extends Infantryman implements CarthaginianArmy {}  
class CarthaginianArcher extends Archer implements CarthaginianArmy {} 
class CarthaginianHorseman extends Horseman implements CarthaginianArmy {} 

// Фабрика прототипов
class ArmyFactory {
    public $infantryman;
    private $archer;
    private $horseman;

    function __construct( Infantryman $infantryman, Archer $archer, Horseman $horseman ) {
        $this->infantryman = $infantryman;
        $this->archer = $archer;
        $this->horseman = $horseman;
    }
    function getInfantryman () {
        return clone $this->infantryman;
    }
    function getArcher () {
        return clone $this->archer;
    }
    function getHorseman () {
        return clone $this->horseman;
    }
}
//
$romanArmy = new ArmyFactory( new RomanInfantryman(1000, 'Cesar'), new RomanArcher(), new RomanHorseman());
$carthaginianArmy = new ArmyFactory( new CarthaginianInfantryman(), new CarthaginianArcher(), new CarthaginianHorseman());
$barbarianArmy = new ArmyFactory( new RomanInfantryman(5000, 'Xerxes'), new CarthaginianArcher(), new CarthaginianHorseman());

print_r($barbarianArmy->getInfantryman());
print_r($barbarianArmy->getArcher());
print_r($barbarianArmy->getHorseman());

echo 'Демонстрация работы клонирования: <br>';
$barbarianInfantryman = $barbarianArmy->getInfantryman();
echo 'Прототип $barbarianInfantryman до изменения свойств его оригинала:' ; print_r($barbarianInfantryman);
echo '<br>';
// меняем свойства прототипа $barbarianArmy->infantryman
$barbarianArmy->infantryman->commander->name = 'John';
$barbarianArmy->infantryman->info = 1;
// для сравнения надо раскомментировать __clone()
echo 'Прототип $barbarianInfantryman после изменения свойств его оригинала:' ; print_r($barbarianInfantryman);
?>
