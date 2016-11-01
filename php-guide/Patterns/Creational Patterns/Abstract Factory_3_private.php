<?php
/*
 * Abstract Factory - шаблон конкретных семейств объектов, но в примере Abstract Factory_1 мой класс
 * Client я могу сделать таким образом, что буду получать наборы объектов с разных семейств, фактически 
 * создавая новое семейство объектов. Это противоречит идеолологии шаблона (не смешивать классы разных семейств), такими вещами должен заниматься
 * шаблон создания семейств Prototype Factory. И поэтому в нижеприведенном примере я ужесточил правила
 * шаблона, чтобы он мог создавать наборы объектов только существующих семейств.
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
/* Благодаря тому, что фабричные методы скрыты, нельзя создавать объекты вне фабрики.
 * Единственный метод, отвечающий за создание объектов находится в фабрике и он устанавливает 
 * жёсткое правило создания объектов в семействе: вызов методов createProductA() и createProductB() из
 * той фабрики, откуда был вызван метод createProduct. Т.е. теперь нельзя где-то в коде вызвать метод 
 * createProductA() класса FactoryCatalog1 и работать с полученным объектом без создания семейства объектов.
 * Сначало надо создать каталог с продуктами A и B, а потом работать с отдельным продуктом каталога.
 * Это и есть инкапсуляция фабричных методов в абстрактной фабрике. 
 */
abstract class AbstractFactory{ 
   abstract protected function createProductA();
   abstract protected function createProductB(); 
   // создание семейства объектов определённого каталога
   public function createProduct(Catalog $objCatalog){
       $objCatalog->productA = $this->createProductA();
       $objCatalog->productB = $this->createProductB();
       return $objCatalog;
   }
}
// Создать объекты семейства Catalog1
class FactoryCatalog1 extends AbstractFactory{
    protected function createProductA(){ return new ProductA1();} // Factory Method
    protected function createProductB(){ return new ProductB1();} // Factory Method
}
// Создать объекты семейства Catalog2
class FactoryCatalog2 extends AbstractFactory{
    protected function createProductA(){ return new ProductA2();} // Factory Method
    protected function createProductB(){ return new ProductB2();} // Factory Method
}

/////////////////////////////// Client ////////////////////////////////////
/* 
 * Класс Catalog теперь абсолютно независим от реализации фабрик. И если потребуется ввести ещё одно семейство
 * каталога (Catalog3,4 и т.д.), то это никак не отразится на коде каталога. За создание новых семейств теперь отвечает только AbstractFactory!!!
 */
// Класс семейства объектов - каталог
class Catalog{
	public $productA;	// объект класса AbstractProductA
	public $productB;	// объект класса AbstractProductB
	// возвращает каталог с продуктами-объектами A и B 
	static function createCatalog (AbstractFactory $factory){
		return $factory->createProduct(new Catalog());
	}
}
///////////////////////////////////////////////////////////////////////////

// создаём объекты-семейства каталоги с продуктами-объектами A и B
$catalog1 = Catalog::createCatalog( new FactoryCatalog1() );
$catalog2 = Catalog::createCatalog( new FactoryCatalog2() );

echo 'Продукты католога1: <br>';
echo $catalog1->productA->getName().'<br>';
echo $catalog1->productB->getItem().'<br>';
echo '<hr>';
echo 'Продукты католога2: <br>';
echo $catalog2->productA->getName().'<br>';
echo $catalog2->productB->getItem().'<br>';
