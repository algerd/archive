<?php
/*
array class_implements ( mixed $class [, bool $autoload = true ] )
	$class - Объект (экземпляр класса) или строка (имя класса).
	$autoload - В зависимости от переданного значения фунция может загрузить описание класса автоматически магическим методом __autoload().
	Возвращаемые значения: В случае успеха будет возвращен массив. В случае ошибки - FALSE.
		
class_implements — Возвращает список интерфейсов, реализованных в заданном классе
Функция возвращает массив имен интерфейсов, реализованных в заданном классе class и его родительских классах.
*/

interface Foo1 { }
interface Foo2 { }
class Bar implements Foo1, Foo2 {}

print_r(class_implements(new Bar));

// начиная с версии PHP 5.1.0 можно передавать имя класса вместо объекта
print_r(class_implements('Bar'));

/*
function __autoload($class_name) {
   require_once $class_name . '.php';
}
// использование __autoload для загрузки еще незагруженного класса 'not_loaded'
print_r(class_implements('not_loaded', true));
*/