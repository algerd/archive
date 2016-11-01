<?php

/**
 * Паттерн Состояние управляет изменением поведения объекта (изменяется работа какого-то его метода)  
 * при изменении его внутреннего состояния (какого-то его свойства, характеризующего состояние объекта).
 * В данном примере в объекте класса Context меняется поведение метода request() 
 * в зависимости состояния-свойства $state, в которое передаётся объект-состояние класса State 
 * В данном примере состояние меняется:
 *	- клиентом, передачей флага состояния (можно напрямую объекта состояния как в примере State_2.php)
 *  - методом объекта-состояние путём переключения состояния на следующее по заранее определённому порядку, как в автомате продуктов.
 *	Т.е. клиент выбирает конкретный продукт с автомата (передаёт флаг состояния), 
 *  а затем автомат после выдачи продукта сам меняет своё состояние - переключается на следующий продукт
 */

class StateException extends Exception {};

//Класс с несколькими внутренними состояниями
class Context {	
	const STATE_A = 1;	// флаги выбора состояния объекта
	const STATE_B = 2;
	const STATE_C = 3;	
	public $state;		// объект класса State - характеризует состояние объекта класса Context
	function __construct() {
		$this->setState(Context::STATE_A);
	}
	// Действия Context делегируются объектам состояний для обработки
	function request() {
		$this->state->handle();
	}	
	// Переключение состояний через передачу флага и проверку совпадения флага в switch
	// $const_state - константа Context::STATE_... определяет состояние объекта
	function setState( $const_state ) {
		switch ( $const_state ) {
			case Context::STATE_A : $this->state = new ConcreteStateA($this); break;
			case Context::STATE_B : $this->state = new ConcreteStateB($this); break;
			case Context::STATE_C : $this->state = new ConcreteStateC($this); break;
			default: throw new StateException('Error: нет такого состояния!');
		}
	}
}

// Абстракция состояний.
abstract class State {
	protected $context;		// объект класса Context

	function __construct( Context $context ) {
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
		// переключаем состояние
		$this->context->setState(Context::STATE_B);
	}
}
class ConcreteStateB extends State {
	function handle() {
		echo "State B handle<br>";
		// переключаем состояние
		$this->context->setState(Context::STATE_C);
	}
}
class ConcreteStateC extends State {
	function handle() {
		echo "State C handle<br>";
		// переключаем состояние
		$this->context->setState(Context::STATE_A);
	}
}

try {
	$context = new Context();
	$context->setState(2);	// задаём принудительно состояние объекта
	$context->request();	// произвести действие, соответствующее состоянию объекта в данный момент, и переключить состояние объекта на следующее.
	$context->request();
	$context->request();
	$context->request();
	$context->request();
	$context->request();
} 
catch( StateException $e ) {
	echo $e->getMessage();
}

