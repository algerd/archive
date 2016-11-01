<?php

// Вынесем в трейт одинаковые свойства для классов Sea, Plains, Forest свойства и методы, чтобы облегчить понимание кода
trait Properties {
	private $arrProperties = array();
	private $animal;	// объект класса Animal
	
    public function __construct( array $arrProperties ){ 
		$this->arrProperties = $arrProperties; 
		$this->animal = new Animal( $arrProperties['name'] );
	}
	// следует прописать клонирование всех свойств-объектов класса, который будет прототипом-клоном
	public function __clone() { $this->animal = clone $this->animal; }
	public function getAnimal() { return $this->animal; }	
}

////////// Набор классов, объекты которых войдут в хранилище прототипов TerrainStoragePrototypes
interface Terrain{}
abstract class Sea implements Terrain {}
abstract class Plains implements Terrain {}
abstract class Forest implements Terrain {}   

interface Earth {}
class EarthSea extends Sea implements Earth { use Properties; }
class EarthPlains extends Plains implements Earth { use Properties; } 
class EarthForest extends Forest implements Earth { use Properties; }

// семейство классов Mars
interface Mars {}
class MarsSea extends Sea implements Mars { use Properties; }
class MarsPlains extends Plains implements Mars { use Properties; } 
class MarsForest extends Forest implements Mars { use Properties; }

// Дополнительный класс - свойство для Sea, Forest, Plains
class Animal{
    public $name;
    public function __construct( $name ){ $this->name = $name; }	
}

////////////////////////// Чертёж: Фабрика-хранилище прототипов ////////////////////////////////////
class TerrainStoragePrototypes {
    private $sea;       // образец объекта класса Sea
	private $forest;    // образец объекта класса Forest
    private $plains;    // образец объекта класса Plains  
    // создаём образцы объектов
    public function setSea( Sea $object ) { $this->sea = $object; }
    public function setPlains( Plains $object ) { $this->plains = $object; }
    public function setForest( Forest $object ) { $this->forest = $object; }
    // возвращаем клоны образцов объектов
    public function getSea() { return clone $this->sea; }
    public function getPlains() { return clone $this->plains; }
    public function getForest() { return clone $this->forest; }
}

////////////////////////////Builder: AbstractFactory ///////////////////////////////////////////////
abstract class AbstractFactoryTerrainBuilder { 
    protected $_terrainStoragePrototypes;    // объект чертежа TerrainStoragePrototypes
    //protected $arrParamSea;			// параметры класса	Sea	
	//protected $arrParamPlains;		// параметры класса Plains
	//protected $arrParamForest;		// параметры класса Forest
	
	// Создаёт объект чертежа TerrainStoragePrototypes и наполняет его свойствами
    public function createTerrain( array $arrParamSea, array $arrParamPlains, array $arrParamForest ) {
        $this->_terrainStoragePrototypes = new TerrainStoragePrototypes();
        $this->buildSea( $arrParamSea );
        $this->buildPlains( $arrParamPlains );
        $this->buildForest( $arrParamForest );     
    }
    public function getTerrainStoragePrototypes(){
        return $this->_terrainStoragePrototypes;
    }        
    abstract protected function buildSea(array $arrParamSea);
    abstract protected function buildPlains(array $arrParamPlains);
    abstract protected function buildForest( array $arrParamForest);
}
// Создать объекты семейства Earth
final class FactoryEarthBuilder extends AbstractFactoryTerrainBuilder {
    protected function buildSea( array $arrParamSea ) { $this->_terrainStoragePrototypes->setSea( new EarthSea( $arrParamSea )); }	// Factory Method
    protected function buildPlains( array $arrParamPlains ) { $this->_terrainStoragePrototypes->setPlains( new EarthPlains( $arrParamPlains )); }
    protected function buildForest( array $arrParamForest ) { $this->_terrainStoragePrototypes->setForest( new EarthForest( $arrParamForest )); }	
}
// Создать объекты семейства Mars
final class FactoryMarsBuilder extends AbstractFactoryTerrainBuilder {
    protected function buildSea( array $arrParamSea ) { $this->_terrainStoragePrototypes->setSea( new MarsSea( $arrParamSea )); }		// Factory Method
    protected function buildPlains( array $arrParamPlains ) { $this->_terrainStoragePrototypes->setPlains( new MarsPlains( $arrParamPlains )); }
    protected function buildForest( array $arrParamForest ) { $this->_terrainStoragePrototypes->setForest( new MarsForest( $arrParamForest )); }
}

///////////////////////// Директор Director //////////////////////////////////////////
class DirectorTerrain {
    private $_terrainBuilder;
    public function setTerrainBuilder( AbstractFactoryTerrainBuilder $terrainBuilder ) {
        $this->_terrainBuilder = $terrainBuilder;
    }
    public function createTerrain( array $arrParamSea, array $arrParamPlains, array $arrParamForest ){
        $this->_terrainBuilder->createTerrain( $arrParamSea, $arrParamPlains, $arrParamForest );
    }
    public function getTerrain() {
        return $this->_terrainBuilder->getTerrainStoragePrototypes();
    }   
}
//////////////////////////// Client //////////////////////////////////
echo '<pre>';
echo 'Хранилище объектов-прототипов $earth: <br>';
$director = new DirectorTerrain();
$director->setTerrainBuilder(new FactoryEarthBuilder());
$director->createTerrain( ['name' => 'elephant'], ['name' => 'eagle'], ['name' => 'volf'] );
$earth = $director->getTerrain();
print_r($earth);

echo '<hr>';
echo 'Хранилище объектов-прототипов $mars: <br>';
$director->setTerrainBuilder(new FactoryMarsBuilder());
$director->createTerrain( ['name' => 'zerg'], ['name' => 'ursus'], ['name' => 'tor'] );
$mars = $director->getTerrain();
print_r($mars);

// клонируем объекты из хранилищ прототипов:
$earthSea = $earth->getSea();
$marsPlains = $mars->getPlains();
$marsForest = $mars->getForest();

