<?php
/*
В отличие от примера в State_1 выбор объектов-состояний реализован на основе ООП-подхода без использования условных операторов.
*/
class StateException extends Exception {};

//Класс с несколькими внутренними состояниями
class Context {	
	public $state;		// объект класса State - характеризует состояние объекта класса Context
	function __construct() {
		$this->setState( new ConcreteStateA() );
	}
	// Переключение состояний через передачу объекта состояния
	function setState( $state ) {
		if ( !($state instanceof State) ) throw new InvalidArgumentException('Error: неправильно задано состояние!');
		$this->state = $state;
		$this->state->setContext($this);
	}
	// Действия Context делегируются объектам состояний для обработки
	function request() {
		$this->state->handle();
	}	
}

// Абстракция состояний.
abstract class State {
	protected $context;		// объект класса Context
	function setContext( Context $context ) {
		$this->context = $context;
	}			
	// метод, изменяющий поведение метода request() класса Context
	abstract function handle();
}
// Набор конкретных состояний, которые обрабатывают запросы от Context.
// Таким образом, при переходе объекта Context в другое состояние, меняется и его поведенеие.
class ConcreteStateA extends State {
	function handle() {
		echo "State A handle<br>";
		// переключаем состояние на следующее
		$this->context->setState( new ConcreteStateB() );
	}
}
class ConcreteStateB extends State {
	function handle() {
		echo "State B handle<br>";
		// переключаем состояние на следующее
		$this->context->setState( new ConcreteStateC() );
	}
}
class ConcreteStateC extends State {
	function handle() {
		echo "State C handle<br>";
		// переключаем состояние на следующее
		$this->context->setState( new ConcreteStateA() );
	}
}

try {
	$context = new Context();
	$context->setState( new ConcreteStateB() );	// задаём принудительно состояние объекта
	$context->request();	// произвести действие, соответствующее состоянию объекта в данный момент, и переключить состояние объекта на следующее.
	$context->request();
	$context->request();
	$context->request();
	$context->request();
	$context->request();
} 
catch( InvalidArgumentException $e ) {
	echo $e->getMessage();
}

