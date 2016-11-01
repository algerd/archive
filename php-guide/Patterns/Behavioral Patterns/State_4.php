<?php
// Правильная с точки зрения ООП реализация примера из Head First Patterns 
// Кривая реализация в state_3.php - Сравните!!! 

class Context {
	private $state;			// class State
	public $count = 2;		// Сколько жвачки в автомате?
	function __construct() {
		$this->setState( new StateNoQuarter() );
	}
	// задать состояние объекта
	function setState( State $state) {
		$this->state = $state;
		$this->state->setContext($this);
	}
	// Действия Context делегируются объектам состояний для обработки
	function insertQuarter() {
		$this->state->insertQuarter();
	}
	function ejectQuarter() {
		$this->state->ejectQuarter();
	}
	function turnCrank() {
		$this->state->turnCrank();
		$this->state->dispense();
	}
	function dispense() {
		$this->state->dispense();
	}
	function releaseBall() {
		if ( $this->count > 0 ) {
			echo "Ball released";
			$this->count--;
		} 
		else echo "No balls to release :(";
		return $this->count;
	}
}
////////////////// State
abstract class State {
	protected $context;		// class Context
	function setContext( Context $context ) {
		$this->context = $context;
	}		
	function insertQuarter() {
		echo "lol, you can't do that<br>";
	}
	function ejectQuarter() {
		echo "lol, you can't do that<br>";
	}
	function turnCrank() {
		echo "lol, you can't do that<br>";
	}
	function dispense() {
		echo "lol, you can't do that<br>";
	}
	function releaseBall() {
		if ( $this->context->releaseBall() )
			$this->context->setState( new StateNoQuarter() );		
	    else 
			$this->context->setState( new StateSoldOut() );
	}
}

class StateSoldOut extends State {
	function insertQuarter() {
		echo "sorry, i'm sold out, can't take quarters<br>";
	}
}
class StateNoQuarter extends State {
	function insertQuarter() {
		echo "got quarter, yeah!<br>";
		// переключаем состояние
		$this->context->setState( new StateHasQuarter() );
	}
}
class StateHasQuarter extends State {
	function ejectQuarter() {
		echo "take your money back<br>";
		// переключаем состояние
		$this->context->setState( new StateNoQuarter() );
	}

	function turnCrank() {
		echo "you turned<br>";
		if ( rand(1, 3) == 3 ) {
			$this->context->setState( new StateWinnerState() );
		} else {
			$this->context->setState( new StateSoldState() );
		}
	}
}
class StateSoldState extends State {
	function dispense() {
		echo "dispensing, yeah!<br>";
		parent::releaseBall();
	}
}
class StateWinnerState extends State {
	function dispense() {
		echo "dispensing, yeah!<br>";
		if ( $this->context->releaseBall() ) {
			echo "p.s. you are WINNER, you get extra ball!<br>";
			parent::releaseBall();
		} else  
			$this->context->setState( new StateSoldOut() );
	}
}

$context = new Context();
$context->dispense();
$context->insertQuarter();
$context->turnCrank();
$context->insertQuarter();
$context->turnCrank();
$context->insertQuarter();
$context->turnCrank();


