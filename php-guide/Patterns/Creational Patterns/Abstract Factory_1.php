<?php
/*
 * Abstract Factory - Предоставляет интерфейс для создания семейств! взаимосвязанных или взаимозависимых объектов
 */
///////////////////////////// Products /////////////////////////////////
// Интерфейс указывает, что AbstractProductA и AbstractProductB - взаимосвязанные классы и входят водно семейство,т.е. создавая объект
// класса AbstractProductA нужно одновременно создавать и объект класса AbstractProductB
interface InterfaceCatalog{}
abstract class AbstractProductA implements InterfaceCatalog{ 
	abstract function getName();
}
abstract class AbstractProductB implements InterfaceCatalog{
    abstract function getItem();
}

// Семейство продуктов Catalog1
interface InterfaceCatalog1{}
class ProductA1 extends AbstractProductA implements InterfaceCatalog1 {
    private $name = 'A1';
    public function getName(){return $this->name.'-ProductA1';}
}
class ProductB1 extends AbstractProductB implements InterfaceCatalog1 {
    private $item = 'B1';
    public function getItem(){return $this->item.'-ProductB1';}
}
// Семейство продуктов Catalog2
interface InterfaceCatalog2{}
class ProductA2 extends AbstractProductA implements InterfaceCatalog2 {
    private $name = 'A2';
    public function getName(){return $this->name.'-ProductA2';}
}
class ProductB2 extends AbstractProductB implements InterfaceCatalog2 {
    private $item = 'B2';
    public function getItem(){return $this->item.'-ProductB2';}
}
//////////////////////////// Abstract Factory ////////////////////////////
// Создать объекты какого-то семейства (Catalog1 или Catalog2)
abstract class AbstractFactory{
   abstract function createProductA();
   abstract function createProductB();	
}
// Создать объекты семейства Catalog1
class FactoryCatalog1 extends AbstractFactory{
    function createProductA(){ return new ProductA1();} // Factory Method
    function createProductB(){ return new ProductB1();} // Factory Method
}
// Создать объекты семейства Catalog2
class FactoryCatalog2 extends AbstractFactory{
    function createProductA(){ return new ProductA2();} // Factory Method
    function createProductB(){ return new ProductB2();} // Factory Method
}
/////////////////////////////// Client ////////////////////////////////////
// Класс каталога
class Catalog{
	public $productA;	// объект класса AbstractProductA
	public $productB;	// объект класса AbstractProductB
}
// Класс для сщздания семейств каталогов и заполнения их объектами с помощью фабрик
class Client{
    const CATALOG1 = 'FactoryCatalog1';
    const CATALOG2 = 'FactoryCatalog2';	
	//
    function createCatalog($const){
		$catalog = new Catalog();
		$factory = new $const();
		$catalog->productA = $factory->createProductA();
		$catalog->productB = $factory->createProductB();		
		return $catalog;	
	}
}
///////////////////////////////////////////////////////////////////////////

$client = new Client();
$catalog1 = $client->createCatalog(Client::CATALOG1);
$catalog2 = $client->createCatalog(Client::CATALOG2);

echo 'Продукты католога1: <br>';
echo $catalog1->productA->getName().'<br>';
echo $catalog1->productB->getItem().'<br>';
echo '<hr>';
echo 'Продукты католога2: <br>';
echo $catalog2->productA->getName().'<br>';
echo $catalog2->productB->getItem().'<br>';
