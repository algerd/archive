<?php
/*
array class_parents ( mixed $class [, bool $autoload = true ] )
	$class - Объект (экземпляр класса) или строка (имя класса).
	$autoload - В зависимости от переданного значения фунция может загрузить описание класса автоматически магическим методом __autoload().
	Возвращаемые значения: В случае успеха будет возвращен массив. В случае ошибки - FALSE.

class_parents — Возвращает список родительских классов заданного класса		
Эта функция возвращает массив с именами базовых классов заданного класса class.
*/
class Foo1{ }
class Foo2 extends Foo1 {}
class Foo3 extends Foo2 {}

print_r(class_parents(new Foo3));

// начиная с версии PHP 5.1.0 можно передавать имя класса вместо объекта
print_r(class_parents('Foo3'));

/*
function __autoload($class_name) {
   require_once $class_name . '.php';
}
// использование __autoload для загрузки еще незагруженного класса 'not_loaded'
print_r(class_parents('not_loaded', true));
*/

