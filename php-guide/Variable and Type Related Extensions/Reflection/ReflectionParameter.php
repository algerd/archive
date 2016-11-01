<?php
/*
ReflectionParameter implements Reflector 
{
	$name ;
	
	__construct ( string $function , string $parameter ) $function - функция или array('classname', 'methodname'), $parameter - аргумент функции или метода 
	private void __clone ( void )		- клонирование невозможно
	string __toString ( void )			- Преобразование общей информации о параметре в строку
	static string export (string $function , string $parameter [, bool $return]) - экспорт общей информации о параметре, для метода array('classname', 'methodname')	
	bool allowsNull ( void )			- Проверяет, допустимо ли значение null для аргумента
	bool canBePassedByValue ( void )	- Проверяет, можно ли передать этот аргумент по значению		
	ReflectionClass getClass ( void )	- Получение класса
	ReflectionClass getDeclaringClass ( void ) - Получение объявляющего класса
	ReflectionFunction getDeclaringFunction ( void ) - Получение объявляющей функции
	mixed getDefaultValue ( void )		- Получение значения по умолчанию аргумента
	string getDefaultValueConstantName ( void )- Returns the default value's constant name if default value is constant or null
	string getName ( void )				- Получение имени аргумента
	int getPosition ( void )			- Получение позиции аргумента
	bool isArray ( void )				- Проверяет, ожидает ли аргумент массив в качестве значения
	bool isCallable ( void )			- Returns whether parameter MUST be callable
	bool isDefaultValueAvailable ( void ) - Проверяет доступно ли значение по умолчанию аргумента
	bool isDefaultValueConstant ( void )  - Returns whether the default value of this parameter is constant
	bool isOptional ( void )			- Проверка, является ли аргумент необязательным
	bool isPassedByReference ( void )	- Проверяет, что аргумент передан по ссылке	
}
Класс ReflectionParameter сообщает информацию об аргументах методов и функций.
Чтобы иметь возможность исследовать аргументы функции, сначала создайте представителя класса ReflectionFunction либо ReflectionMethod 
и затем используйте его метод getParameters() для получения массива аргументов. 
*/
//Reflection::export(new ReflectionClass('ReflectionParameter'));
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
echo '<pre>';
function foo($a, $b, $c) { }
function bar(Exception $a, &$b, $c) { }
function baz(ReflectionFunction $a, $b = 1, $c = null) { }
function abc() { }

// если требуется пройтись по конкретному параметру функции, создаём такой объект и пименяем к нему методы ReflectionParameter
$refl = new ReflectionParameter('foo', 'b');
echo $refl; 
echo '<hr>';

$reflect = new ReflectionFunction('foo');
//$reflect = new ReflectionFunction('bar');
//$reflect = new ReflectionFunction('baz');
//$reflect = new ReflectionFunction('abc');

foreach ($reflect->getParameters() as $i => $param) {
    printf(
        "-- Аргумент #%d: %s {\n".
        "   Класс: %s\n".
        "   Допускает значения NULL: %s\n".
        "   Передается по ссылке: %s\n".
        "   Необязательный?: %s\n".
        "}\n",
        $param->getPosition(),
        $param->getName(),
        var_export($param->getClass(), 1),
        var_export($param->allowsNull(), 1),
        var_export($param->isPassedByReference(), 1),
        $param->isOptional() ? 'да' : 'нет'
    );
}
echo '<hr>';
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

class User{
	function foo($a, $b, $c) { }
	function bar(Exception $a, &$b, $c) { }
	function baz(ReflectionFunction $a, $b = 1, $c = null) { }
	function abc() { }	
}
// если требуется пройтись по конкретному параметру метода, создаём такой объект и пименяем к нему методы ReflectionParameter
$refl = new ReflectionParameter(array('User', 'foo'), 'b');
echo $refl; 

//$reflect = new ReflectionMethod('User','foo');
//$reflect = new ReflectionMethod('User','bar');
$reflect = new ReflectionMethod('User','baz');
//$reflect = new ReflectionMethod('User','abc');

foreach ($reflect->getParameters() as $i => $param) {
	echo '<hr>';
	$arr['getName'] = $param->getName();
	$arr['getPosition'] = $param->getPosition();	
	$arr['allowsNull'] = $param->allowsNull();
	$arr['canBePassedByValue'] = $param->canBePassedByValue();	
	$arr['isArray'] = $param->isArray();
	$arr['isCallable'] = $param->isCallable();
	$arr['isDefaultValueAvailable'] = $param->isDefaultValueAvailable();
	$arr['isOptional'] = $param->isOptional();
	$arr['isPassedByReference'] = $param->isPassedByReference();
	if ($param->isDefaultValueAvailable()) $arr['getDefaultValue'] = $param->getDefaultValue();
	
	var_dump($arr);
}
