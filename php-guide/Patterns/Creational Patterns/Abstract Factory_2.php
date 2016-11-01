<?php
// http://cpp-reference.ru/patterns/creational-patterns/abstract-factory/
/////////// Абстрактные базовые классы всех возможных видов воинов
// все эти классы войск связаны между собой тем, что они принадлежат какой-то Армии
interface InterfaceArmy{}
abstract class Infantryman implements InterfaceArmy{
	abstract public function getInfantryman();
};
abstract class Archer implements InterfaceArmy{
	abstract public function getArcher();
}
abstract class Horseman implements InterfaceArmy{
	abstract public function getHorseman();
}

// Классы всех видов воинов Римской армии
interface RomanArmy {}
class RomanInfantryman extends Infantryman implements RomanArmy {
	private $_info = 10000;
	public function getInfantryman(){
		return "RomanInfantryman: ".$this->_info;	
	}
}  
class RomanArcher extends Archer implements RomanArmy {
	private $_info = 5000;
	public function getArcher(){
		return "RomanArcher: ".$this->_info;
	}
} 
class RomanHorseman extends Horseman implements RomanArmy {
	private $_info = 3000;
	public function getHorseman(){
		return "RomanHorseman: ".$this->_info;
	}   
}

// Классы всех видов воинов армии Карфагена
interface CarthaginianArmy {}
class CarthaginianInfantryman extends Infantryman implements CarthaginianArmy {
	private $_info = 8000;
	public function getInfantryman(){
		return "CarthaginianInfantryman: ".$this->_info;	
	}
}  
class CarthaginianArcher extends Archer implements CarthaginianArmy {
	private $_info = 3000;
	public function getArcher(){
		return "CarthaginianArcher: ".$this->_info;
	}
} 
class CarthaginianHorseman extends Horseman implements CarthaginianArmy {
	private $_info = 2000;
	public function getHorseman(){
		return "CarthaginianHorseman: ".$this->_info;
	}   
}

//////////////// Абстрактная фабрика для производства войск армий
abstract class ArmyFactory{  
    abstract public function createInfantryman();
    abstract public function createArcher();
    abstract public function createHorseman();
};
// Фабрика для создания воинов Римской армии
class RomanArmyFactory extends ArmyFactory{
	public function createInfantryman() { return new RomanInfantryman; }
	public function createArcher() { return new RomanArcher; }
	public function createHorseman() { return new RomanHorseman; }
};
// Фабрика для создания воинов армии Карфагена
class CarthaginianArmyFactory extends ArmyFactory{
	public function createInfantryman() { return new CarthaginianInfantryman; }
	public function createArcher() { return new CarthaginianArcher; }
	public function createHorseman() { return new CarthaginianHorseman; }
};

/////////////// Класс Армии
class Army {
	public $infantryman;	// объект класса Infantryman
	public $archer;			// объект класса Archer
	public $horseman;		// объект класса Нorseman
}
// Здесь создается армия той или иной стороны и заполняется воинами с помощью Factory
class Game{
	const ROME = 'Roman';
	const CARTHAGEN = 'Carthaginian';
	public function createArmy ($const) {
		$army = new Army();
		$factoryName = $const.'ArmyFactory';
		// Factory
		$factory = new $factoryName();	
		$army->infantryman = $factory->createInfantryman();
		$army->archer = $factory->createArcher();
		$army->horseman = $factory->createHorseman();
		return $army;
    }  
}

$game = new Game();
$romanArmy = $game->createArmy(Game::ROME);
$carthaginianArmy = $game->createArmy(Game::CARTHAGEN);

echo 'Римская армия: <br>';
echo $romanArmy->infantryman->getInfantryman().'<br>';
echo $romanArmy->archer->getArcher().'<br>';
echo $romanArmy->horseman->getHorseman().'<br>';
echo '<hr>';
echo 'Карфагенская армия: <br>';
echo $carthaginianArmy->infantryman->getInfantryman().'<br>';
echo $carthaginianArmy->archer->getArcher().'<br>';
echo $carthaginianArmy->horseman->getHorseman().'<br>';
