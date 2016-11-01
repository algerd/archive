<?php

// Класс поддерживаюший сохранение состояний внутреннего состояния   
class Originator {
	private $state;
	function setState( $state ) {
		$this->state = $state;
		echo sprintf("State setted %s\n", $this->state);
	}
	function getState() {
		return $this->state;
	}
	//Сделать снимок состояния объекта
	public function saveState() {
		return new Memento($this->state);
	}

	// Восстановить состояние
	public function restoreState( Memento $memento ) {
		echo sprintf("Restoring state...\n");
		$this->state = $memento->getState();
	}
}

// Хранитель состояния
class Memento {
	private $state;
	function __construct( $state ) {
		$this->state = $state;
	}
	function getState() {
		return $this->state;
	}
}

// Смотрящий за состоянием объекта
class Caretaker {
	private $memento;		// класс Memento
	function getMemento() {
		return $this->memento;
	}
	function setMemento( Memento $memento ) {
		$this->memento = $memento;
	}
}

$originator = new Originator();
$originator->setState("On");

// Store internal state
$caretaker = new Caretaker();
$caretaker->setMemento($originator->saveState());

// Continue changing originator
$originator->setState("Off");

// Restore saved state
$originator->restoreState($caretaker->getMemento());
