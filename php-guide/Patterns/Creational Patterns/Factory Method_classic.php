<?php
/*
 * Factory Method - Предоставляет интерфейс для создания объектов какого-то одного семейства
 * Если семейств продуктов несколько, то надо создавать для каждого из них своё семейство креаторов.
 * Но если семейства продуктов как-то между собой связаны, то лучше воспользоваться Abstract Factory, 
 * чтобы креаторы тоже были связаны в единой абстрактной фабрике.
 *   Classic Factory Method предусматривает разделение фабричных методов по отдельным классам, что соответствует принципам полиморфизма в ооп.
 * Но запуск таких методов сопряжён с определёнными сложностями: надо всё-равно создавать дополнительный клиент-сервис (шаблон DI-Composition), 
 * который будет запускать определённый Classic Factory Method по заданным параметрам. 
 * Classic Factory Method используют для более сложных проектов, где надо создавать объекты от большого числа классов одного семейства.
 */
////////////////////////// Products /////////////////////////////////////////
// Семейство продуктов (одно). Если существует несколько семейств, то надо использовать AbstractFactory
interface Product{
    public function GetName();
} 
class ProductA implements Product{
    public function GetName() { return "ProductA"; }
} 
class ProductB implements Product{
    public function GetName() { return "ProductB"; }
}
class ProductC implements Product{
    public function GetName() { return "ProductC"; }
}
 
///////////////////////// Creator ////////////////////////////////////////////
// семейство фабричных классов (одно) с фабричным методом создания продукта семейства Product
interface Creator{
    public function FactoryMethod();	// фабричный метод создания продукта семейства Product
}  
class CreatorA implements Creator{
    public function FactoryMethod() { return new ProductA(); } 
} 
class CreatorB implements Creator{
    public function FactoryMethod() { return new ProductB(); }
}
class CreatorC implements Creator{
    public function FactoryMethod() { return new ProductC(); }
}
/////////////////////////// Client ////////////////////////////////////////////////////
// Клиент-сервис, предоставляющий 3 разных способа создания фабрик
class Client { 
    const ProductA = 'CreatorA';
	const ProductB = 'CreatorB';
	const ProductC = 'CreatorC';
    
    // 1.классический запуск фабричных методов через передачу объекта-фабрики - самый удачный
    public static function create(Creator $object){
        return $object->FactoryMethod();
    } 
	// 2.Параметрически-динамический запуск фабричных методов - менее удачный, требует проверки передаваемого параметра	
    public static function createDinamic($const){
		$factory = new $const();			// здесь надо делать try-catch c отловом ошибки несуществующего класса, имя которого передано в $const ()
		return $factory->FactoryMethod();		
	}	
	// 3.Параметрический запуск фабричных методов с помощью switch - самый неудачный, тогда лучше использовать Parameter Factory Method
	public static function createSwitch($const){
		switch ($const){
			case (self::ProductA) : 
				$factory = new CreatorA();
				return $factory->FactoryMethod();
			case (self::ProductB) : 
				$factory = new CreatorB();
				return $factory->FactoryMethod();	
			case (self::ProductC) : 
				$factory = new CreatorC();
				return $factory->FactoryMethod();	
			default : ;	
		}
	}
}

// 1. Прямое создание объектов (без Клиент-сервиса). Фактически нигде не используется
$creatorA = new CreatorA();
$creatorB = new CreatorB();
$creatorC = new CreatorC();
$product1 = $creatorA->FactoryMethod();
$product2 = $creatorB->FactoryMethod();
$product3 = $creatorC->FactoryMethod();

// 2. Cоздание объектов c помощью Клиент-сервиса: классическое
$product1 = Client::create(new CreatorA());
$product2 = Client::create(new CreatorB());
$product3 = Client::create(new CreatorC());

// 3. Cоздание объектов c помощью Клиент-сервиса: параметрически-динамическое
$product1 = Client::createDinamic(new CreatorA());
$product2 = Client::createDinamic(new CreatorB());
$product3 = Client::createDinamic(new CreatorC());

// 4. Cоздание объектов c помощью Клиент-сервиса: параметрическое с использованием switch
$product1 = Client::createSwitch(new CreatorA());
$product2 = Client::createSwitch(new CreatorB());
$product3 = Client::createSwitch(new CreatorC());

echo $product1->GetName().'<br>';
echo $product2->GetName().'<br>';
echo $product3->GetName().'<br>';
?>