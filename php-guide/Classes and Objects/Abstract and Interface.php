<?php
/*
Интерфейс группирует классы по набору функционала (методов), поэтому мнтерфейсы называют по
    слову, описывающему этот функционал, с окончанием able (способный). Например, интерфейс группирует классы по 
    методу  масштабирования фигуры (scale()), т.е. все классы от этого интерфейса будут способны масштабировать или
    по английски они будут Scaleable. И таким образом название интерфейса будет - Scaleable. 
	Поскольку функционал у класса может быть разным, то и допустимо множественное наследование от интерфейсов.	
	В интерфейсах доступ к методам только публичный, поэтому уровень доступа у них указывать не надо.
		

Абстрация - это общее представление об объектах, их общее описание. Классы от абстракции - это конкретная
	точная реализация абстракции. Поэтому в абстракцию помещают общие свойства и методы, дающие общее описние о объектах.
	Главный упор в абстракциях надо делать на свойства и методы работы сними (геттеры, сеттеры).
	Поскольку описание объекта может быть только одно, то недопустимо множественное наследование от абстракций.
	Классы, произошедшие от абстакции, могут иметь свои более конкретные свойства и обладать каким-то специфичным функционалом.
	Если этот функционал повторяется и в других классах	, произошедших от абстакции, но он не свойственен всей абстракции, то его надо
	вынести в интерфейс.
			
Вывод:
	- интерфейс группирует объекты по функционалу
	- абстракция группирует объекты по описанию
	Более детально это разделение реализовано в структурном паттерне Bridge.
*/	

// Реализовать функционал - масштабирование фигуры
interface Scaleable {
	function scaleFigure ($scale);
}		
		
// Абстракция - фигура. Её нельзя отрисовать, потому что это абстракция, а не какая-то конкретная фигура. 
abstract class Figure {
	protected $x;
	protected $y;
	public function __construct( $x, $y ) {
		$this->x = $x;
		$this->y = $y;
	}
	public function getX() { return $this->x; }
	public function getY() { return $this->y; }
	public abstract function drawFigure();
}	

// Реализации абстракции - точка
class Point extends Figure {
	public function drawFigure() {
		echo " Точка с координатами x = {$this->x} y = {$this->y}<br>";
	}
}
// Реализуем абстракцию - окружность и добавляем дополнительный функционал - масштабирование 
class Circle extends Figure implements Scaleable {
	private $radius;
	
	public function __construct( $radius, $x, $y ) {
		parent::__construct( $x, $y );
		$this->radius = $radius;
	}
	public function scaleFigure ($scale) {
		$this->radius = $this->radius * $scale;
		return $this;
	}
	public function drawFigure() {
		echo " Окружность с координатами x = {$this->x} y = {$this->y} и радиусом r = {$this->radius}<br>";
	}
}
// Реализуем абстракцию - элипс и добавляем дополнительный функционал - масштабирование
class Ellipse extends Figure implements Scaleable {
	private $outerradius;
	private $innerradius;
	
	public function __construct( $outerradius, $innerradius, $x, $y ) {
		parent::__construct( $x, $y );
		$this->outerradius = $outerradius;
		$this->innerradius = $innerradius;
	}
	public function scaleFigure ($scale) {
		$this->outerradius = $this->outerradius * $scale;
		$this->innerradius = $this->innerradius * $scale;
		return $this;
	}
	public function drawFigure() {
		echo "Эллипс с координатами x = {$this->x} y = {$this->y} и радиусами r1 = {$this->innerradius} и r2 = {$this->outerradius}<br> ";
	}
}

(new Point( 10, 20 ))->drawFigure();
(new Circle(10, 20, 30))->scaleFigure(100)->drawFigure();
(new Ellipse(10, 20, 30, 40))->scaleFigure(200)->drawFigure();

