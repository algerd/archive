<?php

abstract class Army {
    abstract function getForceArmy();
}

class Unit extends Army {
    private $force = 1;
    public function getForceArmy(){
        return $this->force;
    }
}

/////////////////// Декораторы //////////////////////////
abstract class UnitDecorator extends Army {
    private $_unit;     // объект декорируемого класса
    public function __construct( Army $unit ) {
        $this->_unit = $unit;
    }
    // перегружаем декорируемый метод
    public function getForceArmy(){ return $this->_unit->getForceArmy(); }   
}  

class InfantrymanDecorator extends UnitDecorator {
    public function getForceArmy() { return parent::getForceArmy() + 10; }
}
class ArcherDecorator extends UnitDecorator {
    public function getForceArmy() { return parent::getForceArmy() + 8; }
}
class CavalryDecorator extends UnitDecorator {
    public function getForceArmy() { return parent::getForceArmy() + 15; }
}
class ElephantDecorator extends UnitDecorator {
    public function getForceArmy() { return parent::getForceArmy() + 25; }
}

/////////////////////////////////// Client ////////////////////////////////////////

// без обёртки
echo 'ForceUnit: '.(new Unit)->getForceArmy().'<br>';

// одна обёртка
echo 'ForceArcher: '.(new ArcherDecorator(new Unit))->getForceArmy().'<br>';

// обёртка в обёртке в обёртке... Это как матрёшка.
echo 'Force: '.
	(new InfantrymanDecorator(new ArcherDecorator(new CavalryDecorator(new ElephantDecorator(new Unit())))))->getForceArmy();
 
?>
