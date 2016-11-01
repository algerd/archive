<?php
// Пример адаптации класса Plumber к определённому функционалу.

class Plumber { 
	public function getPipe() {  } 
	public function getKey() {  }
	public function getScrewDriver() {  }
}

// Интерфейс, указывающий к какому функционалу надо адаптировать класс Plumber
interface Chief {
    public function makeBreakfast(); 
	public function makeDinner();
	public function makeSupper();   
}

class ChiefAdapter implements Chief { 
	private $_plumber;
 
    public function __construct () {
        $this->_plumber = new Plumber();
    }
	public function makeBreakfast() { return $this->_plumber->getKey(); } 
	public function makeDinner() { return $this->_plumber->getScrewDriver(); } 
	public function makeSupper() { return $this->_plumber->getPipe(); } 
}
 

$key = (new ChiefAdapter())->makeDinner();


