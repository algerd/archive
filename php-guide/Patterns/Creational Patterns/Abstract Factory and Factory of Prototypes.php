<?php

interface Terrain{}
abstract class Sea implements Terrain {}
abstract class Plains implements Terrain {}
abstract class Forest implements Terrain {}                                                 

// семейство классов Earth
interface Earth {}
class EarthSea extends Sea implements Earth{}
class EarthPlains extends Plains implements Earth{}
class EarthForest extends Forest implements Earth{}

// семейство классов Mars
interface Mars {}
class MarsSea extends Sea implements Mars {}
class MarsPlains extends Plains implements Mars {}
class MarsForest extends Forest implements Mars {}

// семейство классов Venus
interface Venus {}
class VenusSea extends Sea implements Venus {}
class VenusPlains extends Plains implements Venus {}
class VenusForest extends Forest implements Venus {}

/////////////////////////////////// AbstractFactory ////////////////////////////////////////////////
abstract class AbstractFactoryTerrain { 
   abstract protected function createSea();
   abstract protected function createPlains();
   abstract protected function createForest();
   // создание объектов определённого семейства
   public function createTerrain (TerrainFactoryStoragePrototypes $object) {
        $object->setSea($this->createSea());
        $object->setPlains($this->createPlains());
        $object->setForest($this->createForest());     
        return $object;
   }
}
// Создать объекты семейства Earth
final class FactoryEarth extends AbstractFactoryTerrain {
    protected function createSea() { return new EarthSea(); } // Factory Method
    protected function createPlains() { return new EarthPlains(); }
    protected function createForest() { return new EarthForest();}
}
// Создать объекты семейства Mars
final class FactoryMars extends AbstractFactoryTerrain {
    protected function createSea(){ return new MarsSea(); } // Factory Method
    protected function createPlains(){ return new MarsPlains();}
    protected function createForest(){ return new MarsForest();}
}
// Создать объекты семейства Venus
final class FactoryVenus extends AbstractFactoryTerrain {
    protected function createSea(){ return new VenusSea();} // Factory Method
    protected function createPlains(){ return new VenusPlains();}
    protected function createForest(){ return new VenusForest();}
}

////////////////////////// Фабрика-хранилище прототипов ////////////////////////////////////////////
class TerrainFactoryStoragePrototypes {
    private $sea;       // образец объекта класса Sea
    private $plains;    // образец объекта класса Plains
    private $forest;    // образец объекта класса Forest 
    //
    private function __construct() {}
    // создать Фабрику-хранилище прототипов определённого семейства, используя фабрику семейства AbstractFactoryTerrain
    public static function createTerrain (AbstractFactoryTerrain $factory){
		return $factory->createTerrain(new TerrainFactoryStoragePrototypes());
	}
    // создаём образцы объектов
    function setSea (Sea $object) { $this->sea = $object; }
    function setPlains (Plains $object) { $this->plains = $object; }
    function setForest (Forest $object) { $this->forest = $object; }
    // возвращаем клоны образцов объектов
    function getSea () { return clone $this->sea; }
    function getPlains () { return clone $this->plains; }
    function getForest () { return clone $this->forest; }
}
////////////////////////////////////////////////////////////////////////////////////////////////////
// создаём 3 хранилища образцов объектов
$earth = TerrainFactoryStoragePrototypes::createTerrain( new FactoryEarth($array) ); 
$mars = TerrainFactoryStoragePrototypes::createTerrain( new FactoryMars($array) ); 
$venus = TerrainFactoryStoragePrototypes::createTerrain( new FactoryVenus($array) ); 

/* Берём объекты из хранилищ (клонируем) разных семейств - фактически мы делаем то же, что в примере Prototype Factory_1,
 * но теперь у нас чёткая структура хранилищ объектов (она имеет жёсткую привязку к семействам и не позволяет вольностей), 
 * из которой мы можем лепить какие-угодно новые семейства объектов. 
 */   
$earthSea = $earth->getSea();
$marsPlains = $mars->getPlains();
$venusForest = $venus->getForest();
print_r($earthSea);
print_r($marsPlains);
print_r($venusForest);