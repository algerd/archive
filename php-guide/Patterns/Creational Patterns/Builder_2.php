<?php

// Классы всех возможных родов войск
class Infantryman {}
class Archer {}  
class Horseman {} 
class Catapult {};  
class Elephant {};

////////////////////// Чертёж Армия //////////////////////////////////
// Класс, содержащий все типы боевых единиц
class Army{
  private $_infantryman;
  private $_archer;
  private $_horseman;
  private $_catapult;
  private $_elephant;
  
  public function setInfantryman (Infantryman $infantryman) { $this->_infantryman = $infantryman; }
  public function setArcher (Archer $archer) { $this->_archer = $archer; }
  public function setHorseman (Horseman $horseman) { $this->_horseman = $horseman; }
  public function setCatapult (Catapult $catapult) { $this->_catapult = $catapult; }
  public function setElephant (Elephant $elephant) { $this->_elephant = $elephant; } 
  
  public function getInfantryman() { return $this->_infantryman; }
  public function getArcher() { return $this->_archer; }
  public function getHorseman() { return $this->_horseman; }
  public function getCatapult() { return $this->_catapult; }
  public function getElephant() { return $this->_elephant; }   
}
/////////////////// Строитель Builder ////////////////////////////////
// Фактически это абстрактная фабрика, где ArmyBuilder - это Absract Factory, а методы build() - это Factory Method
abstract class ArmyBuilder {  
    protected $_army;
    public function createArmy(){
        $this->_army = new Army;
        // Этот блок перенесён сюда из директора, тем самым замкнув всё строительство на билдерах
        $this->buildInfantryman();       
        $this->buildArcher();    
        $this->buildHorseman();    
        $this->buildCatapult();    
        $this->buildElephant();
    }
    public function getArmy(){
        return $this->_army;
    }
    abstract protected function buildInfantryman();
    abstract protected function buildArcher();
    abstract protected function buildHorseman();
    abstract protected function buildCatapult();
    abstract protected function buildElephant(); 
}
// Римская армия имеет все типы боевых единиц кроме боевых слонов
class RomanArmyBuilder extends ArmyBuilder {   
    protected function buildInfantryman() { $this->_army->setInfantryman(new Infantryman()); }
    protected function buildArcher() { $this->_army->setArcher(new Archer()); }
    protected function buildHorseman() { $this->_army->setHorseman(new Horseman()); }   
    protected function buildCatapult() { $this->_army->setCatapult(new Catapult()); }
    protected function buildElephant(){}
} 
// Армия Карфагена имеет все типы боевых единиц кроме катапульт
class CarthaginianArmyBuilder extends ArmyBuilder {   
    protected function buildInfantryman() { $this->_army->setInfantryman(new Infantryman()); }
    protected function buildArcher() { $this->_army->setArcher(new Archer()); } 
    protected function buildHorseman() { $this->_army->setHorseman(new Horseman()); }
    protected function buildElephant() { $this->_army->setElephant(new Elephant()); } 
    protected function buildCatapult(){}
}
////////////////////////// Директор Director //////////////////////////////////////////
class ArmyDirector {  
    private $_armyBuilder;
    public function setArmyBuilder(ArmyBuilder $armyBuilder){
        $this->_armyBuilder = $armyBuilder;
    }
    public function createArmy(){
        $this->_armyBuilder->createArmy();
        /* Этот блок перенесён в createArmy() абстрактного строителя, отдав все функции строительства билдерам и закрыв их видимость с остального кода, 
           а директор должен только принимать заказ, отдавать распоряжение на строительство и отдавать результат строительства
        $this->_armyBuilder->buildInfantryman();       
        $this->_armyBuilder->buildArcher();    
        $this->_armyBuilder->buildHorseman();    
        $this->_armyBuilder->buildCatapult();    
        $this->_armyBuilder->buildElephant();
         */
    }
    public function getArmy(){
        return $this->_armyBuilder->getArmy();
    }
}

//////////////////////////// Client //////////////////////////////////
echo '<pre>';

$director = new ArmyDirector();
$director->setArmyBuilder(new RomanArmyBuilder);
$director->createArmy();
$romanArmy = $director->getArmy();
print_r($romanArmy);

$director->setArmyBuilder(new CarthaginianArmyBuilder);
$director->createArmy();
$carthaginianArmy = $director->getArmy();
print_r($carthaginianArmy);
