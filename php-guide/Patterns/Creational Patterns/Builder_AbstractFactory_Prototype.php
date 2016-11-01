<?php
/*
 * Сравним этот гибридный шаблон с примером Abstract Factory and Factory of Prototypes.php:
 * - фактически они идентичны, за исключением вынесения из хранилища прототипов статического
 * метода создания хранилища в отдельный класс - Director и соответственно разгрузив метод create() в
 * абстрактнй фабрике ввода-возвращения объекта. Это казалось небольшое изменение на самом деле сделал
 * код более структурированным и изолировала классы от несвойственной им работы. С билдером код выглядит
 * понятнее и последовательнее, его легче проектировать и тестировать. Плата за это - код удлинняется, но
 * это мелочь по сравнению с плюсами.
 *  Builder - мегаполезный и мега важный шаблон!!! Это скелет фактически любого проекта на ООП!
 */

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

////////////////////////// Чертёж: Фабрика-хранилище прототипов ////////////////////////////////////
class TerrainStoragePrototypes {
    private $sea;       // образец объекта класса Sea
    private $plains;    // образец объекта класса Plains
    private $forest;    // образец объекта класса Forest 
    // создаём образцы объектов
    public function setSea (Sea $object) { $this->sea = $object; }
    public function setPlains (Plains $object) { $this->plains = $object; }
    public function setForest (Forest $object) { $this->forest = $object; }
    // возвращаем клоны образцов объектов
    public function getSea () { return clone $this->sea; }
    public function getPlains () { return clone $this->plains; }
    public function getForest () { return clone $this->forest; }
}
////////////////////////////Builder: AbstractFactory ///////////////////////////////////////////////
abstract class AbstractFactoryTerrainBuilder { 
    protected $_terrainStoragePrototypes;    // объект чертежа TerrainStoragePrototypes
    // Создаёт объект чертежа TerrainStoragePrototypes и наполняет его свойствами
    public function createTerrain () {
        $this->_terrainStoragePrototypes = new TerrainStoragePrototypes();
        $this->buildSea();
        $this->buildPlains();
        $this->buildForest();     
    }
    public function getTerrainStoragePrototypes(){
        return $this->_terrainStoragePrototypes;
    }        
    abstract protected function buildSea();
    abstract protected function buildPlains();
    abstract protected function buildForest();
}
// Создать объекты семейства Earth
final class FactoryEarthBuilder extends AbstractFactoryTerrainBuilder {
    protected function buildSea() { $this->_terrainStoragePrototypes->setSea(new EarthSea()); } // Factory Method
    protected function buildPlains() { $this->_terrainStoragePrototypes->setPlains(new EarthPlains()); }
    protected function buildForest() { $this->_terrainStoragePrototypes->setForest(new EarthForest()); }
}
// Создать объекты семейства Mars
final class FactoryMarsBuilder extends AbstractFactoryTerrainBuilder {
    protected function buildSea(){ $this->_terrainStoragePrototypes->setSea(new MarsSea()); } // Factory Method
    protected function buildPlains(){ $this->_terrainStoragePrototypes->setPlains(new MarsPlains()); }
    protected function buildForest(){ $this->_terrainStoragePrototypes->setForest(new MarsForest()); }
}
// Создать объекты семейства Venus
final class FactoryVenusBuilder extends AbstractFactoryTerrainBuilder {
    protected function buildSea(){ $this->_terrainStoragePrototypes->setSea(new VenusSea()); } // Factory Method
    protected function buildPlains(){ $this->_terrainStoragePrototypes->setPlains(new VenusPlains()); }
    protected function buildForest(){ $this->_terrainStoragePrototypes->setForest(new VenusForest()); }
}
///////////////////////// Директор Director //////////////////////////////////////////
class DirectorTerrain {
    private $_terrainBuilder;
    public function setTerrainBuilder(AbstractFactoryTerrainBuilder $terrainBuilder){
        $this->_terrainBuilder = $terrainBuilder;
    }
    public function createTerrain(){
        $this->_terrainBuilder->createTerrain();
    }
    public function getTerrain(){
        return $this->_terrainBuilder->getTerrainStoragePrototypes();
    }   
}
//////////////////////////// Client //////////////////////////////////
echo '<pre>';

$director = new DirectorTerrain();
$director->setTerrainBuilder(new FactoryEarthBuilder());
$director->createTerrain();
$earth = $director->getTerrain();
print_r($earth);

$director->setTerrainBuilder(new FactoryMarsBuilder());
$director->createTerrain();
$mars = $director->getTerrain();
print_r($mars);

$director->setTerrainBuilder(new FactoryVenusBuilder());
$director->createTerrain();
$venus = $director->getTerrain();
print_r($venus);

// клонируем объекты из хранилищ прототипов:
$earthSea = $earth->getSea();
$marsPlains = $mars->getPlains();
$venusForest = $venus->getForest();

// Если потребуется через FactoryVenusBuilder() передавать аргумерны для конструкторов строящихся классов,
// то следует это делать через массивы по аналогии с Abstract Factory_Factory of Prototypes_Extension.php
