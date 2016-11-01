<?php
/*
 * Factory (или biulder) создаёт объкты в зависимости от передаваемых данных. 
 * Суть - есть класс Завод, который имеет метод Производство. При обращении заказчика к методу Производство
 * объкта Завод с желанием получить определённую машину (тоёту или ладу) - метод возвращает ему объект машины
 * (тоёту или ладу)
 * Фабрика даёт гибкость в изменении, удалении и дополнении наборов объектов, с которыми будет работать Класс-фабрика.
 * В нашем примере в любой момент можно будет добавить классы Renault или Suzuki и прописать их подключение в классе-стратегии.
 */
////////////////////////////////////////////////////////////////////////////////////////////////////
// эскиз классов, объекты которых будет производить Класс-фабрика
abstract class Car {
	abstract function getMaxSpeed();
	abstract function getWeight();
}
// Фабричные классы
class Toyota extends Car {
	public function getMaxSpeed(){return 180;}
	public function getWeight() {return 1800;}
}

class BMW extends Car {
	public function getMaxSpeed() {return 200;}
	public function getWeight() {return 1900;}
}

class Lada extends Car {
    public function getMaxSpeed() {return 150;}
	public function getWeight() {return 1500;}
}
// дефолтный класс-пустышка с пустыми перегруженными методами абстрактного родителя
class EmptyCar extends Car {
	public function getMaxSpeed() {}
	public function getWeight() {}
}

////////////////////////////////////////////////////////////////////////////////////////////////////
// Класс - фабрика (или билдер). Его методы создают новые классы
class CarsFactory {
    // метод-билдер новых классов в зависимости от входных данных
	function createCar($brand='') {
		switch (strtolower($brand)) {
            case 'toyota' : return new Toyota;
            case 'bmw'    : return new BMW;
            case 'lada'   : return new Lada;    
			default       : return new EmptyCar;
		}
	}
}

////////////////////////////////////////////////////////////////////////////////////////////////////
// создаём объект - фабрику объектов
$carsFactory = new CarsFactory();
// создаём новые объекты с помощью фабричного метода в зависимости от передаваемого значения
$car1 = $carsFactory->createCar('toyota');
$car2 = $carsFactory->createCar('bmw');
$car3 = $carsFactory->createCar('lada');
// работаем с полученными объектами
echo 'Max speed: '.$car1->getMaxSpeed().'<br>';
echo 'Weight: '.$car1->getWeight();
   
?>