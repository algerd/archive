<?php
/*
Паттерн Bridge разделяет абстракцию (представление) и реализацию(функционал) на две отдельные иерархии классов так, что их можно изменять независимо друг от друга.
Первая иерархия определяет интерфейс абстракции, доступный пользователю. Все детали реализации, связанные с особенностями среды скрываются во второй иерархии.
Простыми словами: 
    - есть семейство классов-представлений (абстракций) с определённым набором свойств и методов
    - есть семeйство классов (реализаций), которое обеспечивает в разных вариациях определённую функцию первого семейства
    - агреггируя внутрь абстракции объект реализации мы наделяем представление соответствующим функционалом.     
*/
/////////////////////////////////// Реализация (функционал) ////////////////////////////////////
// Реализует функционал - рисование круга
interface Drawable {
	public function drawCircle($x, $y, $radius);
}
// Рисовать маленький круг 
class SmallCircleDrawer implements Drawable {
	const RADIUS_MULTIPLIER = 0.25;
	public function drawCircle($x, $y, $radius) {
		echo 'Small circle center = ( '.$x.', '.$y.' ) radius = '.($radius * self::RADIUS_MULTIPLIER).'<br>';
	}
}
// Рисовать большой круг 
class LargeCircleDrawer implements Drawable {
	const RADIUS_MULTIPLIER = 10;
	public function drawCircle($x, $y, $radius) {
		echo 'Large circle center = ( '.$x.', '.$y.' ) radius = '.($radius * self::RADIUS_MULTIPLIER).'<br>';
	}
}

////////////////////////////// Абстракция (представление) ///////////////////////////////////////
abstract class Shape {
	protected $drawer;      // объект-релизация IDrawer
	protected function __construct( Drawable $drawer ) { $this->drawer = $drawer; }
	abstract public function draw();
	abstract public function enlargeRadius($multiplier);
}
 
class Circle extends Shape {
	private $x;
	private $y;
	private $radius;
 
	public function __construct( $x, $y, $radius, Drawable $drawer ) {
		parent::__construct($drawer);
		$this->x = $x;
		$this->y = $y;
		$this->radius = $radius;
	}
	public function draw() {
        // применить Реализацию
		$this->drawer->drawCircle($this->x, $this->y, $this->radius);
	}
	public function enlargeRadius($multiplier) { $this->radius *= $multiplier; }
}
 


$circle = new Circle(5, 10, 10, new LargeCircleDrawer());
$circle->draw();	// Large circle center = ( 5, 10 ) radius = 100
$circle = new Circle(20, 30, 100, new SmallCircleDrawer());
$circle->draw();	// Small circle center = ( 20, 30 ) radius = 25    