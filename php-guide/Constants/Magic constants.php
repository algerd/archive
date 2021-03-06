<?php
# http://www.php.net/manual/ru/language.constants.predefined.php
/*
__LINE__		Текущий номер строки в файле.
__FILE__		Полный(абсолютный) путь и имя текущего файла. 
__DIR__			Директория файла. Если используется внутри подключаемого файла, то возвращается директория этого файла. Это эквивалентно вызову dirname(__FILE__).
				Возвращаемое имя директории не оканчивается на слэш, за исключением корневой директории.
__FUNCTION__ 	Имя функции (с учетом регистра). 
__CLASS__		Имя класса (с учетом регистра). Имя класса содержит название пространства имен, в котором класс был объявлен (например, Foo\Bar). 
				__CLASS__ также работает в трейтах. При использовании в методах трейтов __CLASS__ является именем класса, в котором эти методы используется.
__TRAIT__		Имя трейта (с учетом регистра). Это имя содержит название пространства имен, в котором трейт был объявлен (например, Foo\Bar).
__METHOD__		Имя метода класса(с учетом регистра).
__NAMESPACE__ 	Имя текущего пространства имен (с учетом регистра).

*/
namespace User;

function foo(){
	echo 'Это функция: '.__FUNCTION__.'<br>';
}

trait TraitInfo
{
	public function getTraitInfo () {
		echo 'Это трейт: '.__TRAIT__.'<br>';		
	}
}

class Info
{
	use TraitInfo;
	
	public function getСlassInfo () {
		echo 'Это пространство имён: '.__NAMESPACE__.'<br>';
		echo 'Это метод: '.__METHOD__.' класса: '.__CLASS__.'<br>';		
	}
	public function getFileInfo () {
		echo 'Это позиция в коде: '.__LINE__.' файла: '.__FILE__.' директории: '.__DIR__.'<br>';
	}
}

$obj = new Info();

foo();
$obj->getFileInfo();
$obj->getTraitInfo();
$obj->getСlassInfo();

?>