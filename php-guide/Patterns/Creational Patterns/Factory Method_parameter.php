<?php
/*
 * Factory Method - Предоставляет интерфейс для создания объекта какого-то одного семейства
 * Если семейств продуктов несколько, то надо создавать для каждого из них свой креатор.
 * Но если семейства продуктов как-то между собой связаны, то лучше воспользоваться Abstract Factory, 
 * чтобы креаторы тоже были связаны в единой абстрактной фабрике.
 *  Parameter Factory Method - это один фабричный метод с условием (switch), в котором в зависимости от передаваемого в метод параметра
 * создаётся соответствующий класс. Он отличается простотой исполнения, но использование switch для создания объектов нарушает принцип ооп-полиморфизма(разделение).
 * Есть свои минусы и при использовании статического метода. Такой вариант эффективен при создании простых фабрик без дальнейшего их развития и расширения.
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
// Креатор со статическим параметрическим фабричным методом
class Creator {
	const ProductA = 'CreatorA';
	const ProductB = 'CreatorB';
	const ProductC = 'CreatorC';
	// Метод запуска фабрик часто делают со switch, в котором идёт сравнение аргумента с соответствующими меткми фабрик, но лучше условия избегать как в createProduct()
	public static function ParameterFactoryMethod($const){
		switch ($const){
			case (self::ProductA) : 
				return new ProductA();
			case (self::ProductB) : 
				return new ProductB();	
			case (self::ProductC) : 
				return new ProductC();	
			default : false ;	
		}
	}
}
///////////////////////////////////////////////////////////////////////////////

$product1 = Creator::ParameterFactoryMethod(Creator::ProductA);
$product2 = Creator::ParameterFactoryMethod(Creator::ProductB);
$product3 = Creator::ParameterFactoryMethod(Creator::ProductC);

echo $product1->GetName().'<br>';
echo $product2->GetName().'<br>';
echo $product3->GetName().'<br>';