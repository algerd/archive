<?php
/*
Приспособленец (англ. Flyweight) - шаблон, при котором объект, представляющий себя как уникальный экземпляр в разных местах программы, по факту не является таковым.
Flyweight используется для уменьшения затрат при работе с большим количеством мелких  легковесных(Flyweight) объектов. 
При проектировании приспособленца необходимо разделить его свойства на внешние и внутренние. 
Внутренние свойства всегда неизменны, тогда как внешние могут отличаться в зависимости от места и контекста применения и должны быть вынесены за пределы приспособленца.
Flyweight дополняет паттерн Factory таким образом, что Factory при обращении к ней клиента для создания нового объекта ищет уже созданный объект с такими же параметрами, что и у требуемого, и возвращает его клиенту. 
Если такого объекта нет, то фабрика создаст новый.
Фактически Flyweight играет роль и создателя, и контейнера-хранилища легковесных(Flyweight) объектов однотипных классов (одного семейства).
Использовать этот паттерн следует только для действительно простых однотипных объектов, потому что он запутывает код и нарушает принципы полиморфизма в ооп.		
*/

// "FlyweightFactory"
// Создаёт объект по переданному ключу, если таковой ещё не был создан и возвращает его 
// или возвращает ранее созданный объект, соответствующий ключу
class CharacterFactory {
	private $characters = array();
	
	public function getCharacter( $key ) {
		// Uses "lazy initialization"
		if ( !array_key_exists( $key, $this->characters ) ) {
			switch ( $key ) {
				case 'A': $this->characters[$key] = new CharacterA(); break;
				case 'B': $this->characters[$key] = new CharacterB(); break;
				//...
				case 'Z': $this->characters[$key] = new CharacterZ(); break;
			}
		}
		return $this->characters[$key];
	}
}
 
///////////////////////// "Flyweight" //////////////////////////////
abstract class Character {
	protected $symbol;
	protected $width;
	protected $height;
	protected $ascent;
	protected $descent;
	protected $pointSize;
 
	public abstract function display($pointSize);
}
// "ConcreteFlyweight"
class CharacterA extends Character {
	public function __construct() {
		$this->symbol = 'A';
		$this->height = 100;
		$this->width = 120;
		$this->ascent = 70;
		$this->descent = 0;
	}
 
	public function display( $pointSize ) {
		$this->pointSize = $pointSize;
		print( $this->symbol." (pointsize ".$this->pointSize.")" );
	}
}
// "ConcreteFlyweight"
class CharacterB extends Character {
	public function __construct() {
		$this->symbol = 'B';
		$this->height = 100;
		$this->width = 140;
		$this->ascent = 72;
		$this->descent = 0;
	}
	public function  display( $pointSize ) {
		$this->pointSize = $pointSize;
		print( $this->symbol." (pointsize ".$this->pointSize.")" );
	}
}
// ... C, D, E, etc.
// "ConcreteFlyweight"
class CharacterZ extends Character {
	public function __construct() {
		$this->symbol = 'Z';
		$this->height = 100;
		$this->width = 100;
		$this->ascent = 68;
		$this->descent = 0;
	}
	public function  display( $pointSize ) {
		$this->pointSize = $pointSize;
		print( $this->symbol." (pointsize ".$this->pointSize.")" );
	}
}
 

// Преобразуем строку в массив
$chars = str_split( "AAZZBBZB" );

// Для каждого элемента массива использовать соответствующий объект для вывода
$factory = new CharacterFactory();
$pointSize = 0;
foreach ($chars as $key) {
	$pointSize++;
	$factory->getCharacter($key)->display($pointSize);
}