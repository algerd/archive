<?php
// Адаптированный пример к PHP 5.3 из Head First Patterns 
// Пример кривой реализации взят с http://ru.wikipedia.org/wiki/%D0%A1%D0%BE%D1%81%D1%82%D0%BE%D1%8F%D0%BD%D0%B8%D0%B5_%28%D1%88%D0%B0%D0%B1%D0%BB%D0%BE%D0%BD_%D0%BF%D1%80%D0%BE%D0%B5%D0%BA%D1%82%D0%B8%D1%80%D0%BE%D0%B2%D0%B0%D0%BD%D0%B8%D1%8F%29
// Правильная с точки зрения ООП реализация этого примера сделана в State_4.php - сравните!!!

class Context {
	const STATE_SOLD_OUT = 1;			//Возможные состояния
	const STATE_NO_QUARTER_STATE = 2;
	const STATE_HAS_QUARTER_STATE = 3;
	const STATE_SOLD_STATE = 4;
	const STATE_WINNER_STATE = 5;
	
	public $state;			// class State
	public $count = 2;		// Сколько жвачки в автомате?

	function __construct() {
		$this->setState(Context::STATE_NO_QUARTER_STATE);
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

	/**
	 * Это один из способов реализации переключения состояний
	 * @param $state выбранное состояние, возможные варианты перечислены в списке констант Context::STATE_..
	 */
	function setState($state) {
		if ($state == Context::STATE_SOLD_OUT) {
			$this->state = new StateSoldOut($this);
		} elseif ($state == Context::STATE_NO_QUARTER_STATE) {
			$this->state = new StateNoQuarter($this);
		} elseif ($state == Context::STATE_HAS_QUARTER_STATE) {
			$this->state = new StateHasQuarter($this);
		} elseif ($state == Context::STATE_SOLD_STATE) {
			$this->state = new StateSoldState($this);
		} elseif ($state == Context::STATE_WINNER_STATE) {
			$this->state = new StateWinnerState($this);
		}
	}

	function releaseBall() {
		if ( $this->count > 0 ) {
			echo "Ball released";
			$this->count--;
		} else {
			echo "No balls to release :(";
		}
	}

}
//////////////////
abstract class State {
	protected $context;		// class Context
	function __construct( $context ) {
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
		$this->context->setState(Context::STATE_HAS_QUARTER_STATE);
	}
}

class StateHasQuarter extends State {
	function ejectQuarter() {
		echo "take your money back<br>";
		// переключаем состояние
		$this->context->setState(Context::STATE_NO_QUARTER_STATE);
	}

	function turnCrank() {
		echo "you turned<br>";
		$winner = rand(1, 3) == 3 ? 1 : 0;
		if ($winner) {
			$this->context->setState(Context::STATE_WINNER_STATE);
		} else {
			$this->context->setState(Context::STATE_SOLD_STATE);
		}
	}
}

class StateSoldState extends State {
	function dispense() {
		echo "dispensing, yeah!<br>";
		$this->context->releaseBall();
		if ($this->context->count == 0) {
			$this->context->setState(Context::STATE_SOLD_OUT);
		} else {
			// переключаем состояние
			$this->context->setState(Context::STATE_NO_QUARTER_STATE);
		}
	}
}

class StateWinnerState extends State {
	function dispense() {
		echo "dispensing, yeah!<br>";
		$this->context->releaseBall();
		if ($this->context->count == 0) {
			$this->context->setState(Context::STATE_SOLD_OUT);
		} else {
			echo "p.s. you are WINNER, you get extra ball!<br>";
			$this->context->releaseBall();
			if ($this->context->count == 0) {
				$this->context->setState(Context::STATE_SOLD_OUT);
			} else {
				$this->context->setState(Context::STATE_NO_QUARTER_STATE);
			}
		}
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


