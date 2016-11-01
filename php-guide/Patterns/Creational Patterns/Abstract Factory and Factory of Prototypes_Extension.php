<?php
/*
 * В данном примере мы расширили возможности предыдущего примера:
 * - теперь фабрики создают объекты с определёнными параметрами, передаваемыми в конструкторы в виде массива.
 * Передача данных в виде массива несмотря на свою неинформативность имеет существенный плюс - компактность и гибкость
 * (количество элементов в массиве может быть разным, а вот количество одиночных элементов в конструкторе - строго заданная величина в функции).
 * Этот пример вобрал в себя сразу 3 паттерна и другие фичи:
 *  - Abstract Factory + Сlassic Factory Method - создаёт наборы объектов определённых семейств
 *  - Factory of Prototypes - создаёт хранилище прототипов объектов
 *  - Sungleton(частичная реализация) - встроен в хранилище прототипов для наполнения его из Abstract Factory
 *  - Закрытость фабричных методов и изолированность создания наборов объектов от кода вне фабрики
 *  - Возможность создания образцов объектов в хранилище с определёнными параметрами
 * Но и этот пример недостаточно хорош. Дальнейшее его развитие по пути логического разделения функций исполнено в Build_AbstractFactory_Prototype.php,
 * в котором мы избавились от логичесой путаницы с синглтоном, выделив под его функции целый класс.
 */

interface Terrain{}
abstract class Sea implements Terrain {}
abstract class Forest implements Terrain {}                                                 

// семейство классов Earth
interface Earth {}
class EarthSea extends Sea implements Earth{
    public $arrProperties = array();
    public function __construct(array $arrProperties){ $this->arrProperties = $arrProperties; }
}
class EarthForest extends Forest implements Earth{
    public $arrProperties = array();
    public function __construct(array $arrProperties){ $this->arrProperties = $arrProperties; }
}

// семейство классов Mars
interface Mars {}
class MarsSea extends Sea implements Mars {
    public $arrProperties = array();
    public function __construct(array $arrProperties){ $this->arrProperties = $arrProperties; }
}
class MarsForest extends Forest implements Mars {
    public $arrProperties = array();
    public function __construct(array $arrProperties){ $this->arrProperties = $arrProperties; }
}

/////////////////////////////////// AbstractFactory ////////////////////////////////////////////////
abstract class AbstractFactoryTerrain {      
    abstract protected function createSea();
    abstract protected function createForest(); 
    // конструктор фабрик инициализации параметров фабрик, передаваемых в конструкторы соответствующих классов
    public function __construct (array $arrParamSea, array $arrParamForest){
        $this->arrParamSea = $arrParamSea;
        $this->arrParamForest = $arrParamForest;   
    }
    // создание объектов определённого семейства
    public function createTerrain (TerrainFactoryStoragePrototypes $object) {
         $object->setSea($this->createSea());
         $object->setForest($this->createForest());     
         return $object;
    }
}
// Создать объекты семейства Earth
final class FactoryEarth extends AbstractFactoryTerrain {
    protected function createSea() { return new EarthSea( $this->arrParamSea ); } // Factory Method
    protected function createForest() { return new EarthForest( $this->arrParamForest ); }
}
// Создать объекты семейства Mars
final class FactoryMars extends AbstractFactoryTerrain {
    protected function createSea(){ return new MarsSea( $this->arrParamSea ); } // Factory Method
    protected function createForest(){ return new MarsForest( $this->arrParamForest );}
}

////////////////////////// Фабрика-хранилище прототипов ////////////////////////////////////////////
// Частичная реализация паттерна Синглтон
class TerrainFactoryStoragePrototypes {
    private $sea;       // образец объекта класса Sea
    private $forest;    // образец объекта класса Forest 
    private function __construct() {}
    private function __сlon() {}
    // создать Фабрику-хранилище прототипов определённого семейства, используя фабрику семейства AbstractFactoryTerrain
    public static function createTerrain (AbstractFactoryTerrain $factory) {
		return $factory->createTerrain(new TerrainFactoryStoragePrototypes());
	}
    // создаём образцы объектов в свойствах хранилища
    function setSea (Sea $object) { $this->sea = $object; }
    function setForest (Forest $object) { $this->forest = $object; }
    // возвращаем клоны образцов объектов
    function getSea () { return clone $this->sea; }
    function getForest () { return clone $this->forest; }
}
////////////////////////////////////////////////////////////////////////////////////////////////////
// создаём 2 хранилища образцов объектов семейств Earth и Mars c определёнными параметрами
$earth = TerrainFactoryStoragePrototypes::createTerrain( new FactoryEarth( ['sea' => 'cold'], ['forest' => 1000] ) ); 
$mars = TerrainFactoryStoragePrototypes::createTerrain( new FactoryMars( ['sea' => 'warm'], ['forest' => 0.5]) ); 

/* Берём объекты(клонируем) из хранилищ  разных семейств - фактически мы делаем то же, что в примере Prototype Factory_1,
 * но теперь у нас чёткая структура хранилищ объектов (она имеет жёсткую привязку к семействам и не позволяет вольностей), 
 * из которой мы можем лепить какие-угодно новые семейства объектов. 
 */   
$earthSea = $earth->getSea();
$marsForest = $mars->getForest();
echo '<pre>';
echo 'Объект Sea семейства Earth из хранилища $earth: '; print_r($earthSea);
echo 'Объект Forest семейства Mars из хранилища $mars: '; print_r($marsForest); echo '<hr>';
$earthSea->arrProperties['sea'] = 'warm';
$marsForest->arrProperties['forest'] = 'high';
echo 'Изменённый Объект Sea семейства Earth из хранилища $earth: '; print_r($earthSea);
echo 'Изменённый Объект Forest семейства Mars из хранилища $mars: '; print_r($marsForest); echo '<hr>';
$earthSea2 = $earth->getSea();
$marsForest2 = $mars->getForest();
echo 'Новый Объект Sea семейства Earth из хранилища $earth: '; print_r($earthSea2);
echo 'Новый Объект Forest семейства Mars из хранилища $mars: '; print_r($marsForest2);