<?php
/*
Шаблон Adapter предназначен для адаптации объекта к определённым условиям. 
По сути это простое расширение или изменение функционала объекта в зависимости от поставленых задач перед адаптируемым объектом.
Адаптер - это контейнер-оболочка всего адаптируемого объекта, в отличие от Decjrator, который тоже является контейнером-оболочкой, но он изменяет(декорирует) только какой-то конкретный метод.
Это достигается путём применения чистого DI - Composition(Aggregation): делегирование(агруггирование) объекта в адаптер. 
*/
// Пример: требуется изменить(адаптировать) функционал Продукта, чтобы он показывал цену с учётом скидки   
   
class Product {
	private $price;
	private $discount;

	function __construct( $price, $discount ) {
		$this->price = $price;
		$this->discount = $discount;
	}
	function getPrice() {
		return $this->price;
	}
	function getDiscount() {
		return $this->discount;
	}
}
// Адаптер - Контейнер-обработчик объектов класса Product
class ProductAdapter {
	private $product;

	function __construct(Product $product) {
		$this->product = $product;
	}
	function getPrice() {
		return $this->product->getPrice() - $this->product->getDiscount();
	}
}


$product1 = new Product(100, 20);
echo 'Discounted price = '.($product1->getPrice() - $product1->getDiscount());
// помещаем объект $product1 в контейнер-обработчик ProductAdapter
$product2 = new ProductAdapter($product1);
echo 'Discounted price = '.$product2->getPrice();
?>