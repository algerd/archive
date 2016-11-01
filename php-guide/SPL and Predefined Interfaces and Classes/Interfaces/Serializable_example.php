<?php
/* 
 * Пытаемся сериализовать "по старинке" с помощью __sleep()
 */
echo 'Пытаемся сериализовать "по старинке" с помощью __sleep()<br>';

error_reporting(E_ALL);  
class A 
{
	private $varA;
	public function __construct() {
		$this->varA = 'A';
	}
}
class B extends A 
{
	private $varB;
	public function __construct() {
		parent::__construct();
		$this->varB = 'B';
	}
	// пытаемся сериализовать объект класса B вместе с его родителем A 
	public function __sleep() {
		return array('varB', 'varA');
	}
}
// Пытаемся сериализовать и получаем сообщение о невозможности сериализации родительского приватного свойства private $varA;
$obj = new B();
$serialized = serialize($obj);
echo $serialized . "<br>";
echo '<hr>';
////////////////////////////////////////////////////////////////////////////////////////////////////
/* 
 * Сериализуем правильно!!! через интерфейс Serializable
 * Таким путём можно сериализовать в одну строку длинные цепочки наследник-родитель, а не делать сериализацию каждого потомка по отдельности
 */
// Класс-родитель
class Aser implements Serializable
{
	private $varAser;
		public function __construct() {
		$this->varAser = 'Aser';
	}
	// загружаемый метод в наследнике при сериализации наследника с родителем
	public function serialize() {
		return serialize($this->varAser);
	}
	// загружаемый метод в наследнике при десериализации наследника с родителем
	public function unserialize($serialized) {
		$this->varAser = unserialize($serialized);
	}
	public function showVar() {
		echo $this->varAser . "<br>";
	}
}
// Класс-наследник
class Bser extends Aser 
{
	private $varBser;
	public function __construct() {
		parent::__construct();
		$this->varBser = 'Bser';
	}
	public function serialize() {
		// сериализуем родителя Aser с приватными свойствами
		$serialized = parent::serialize();
		// сериализуем наследника Bser и родителя Aser
		return serialize(array($this->varBser, $serialized));
	}
	public function unserialize( $serialized ) {
		// десериализуем  наследника Bser и родителя Aser
		$temp = unserialize($serialized);
		$this->varBser= $temp[0];
		// десериализуем родителя Aser с приватными свойствами
		parent::unserialize($temp[1]);
	}
}
echo 'Сериализуем правильно!!! через интерфейс Serializable<br><br>';

$obj = new Bser();
$serialized = serialize($obj);			// сериализация цепочки наследник-родитель
echo $serialized . "<br>";
$restored = unserialize($serialized);	// десериализация цепочки наследник-родитель
$restored->showVar();

?>