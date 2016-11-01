<?php
/*
array class_uses ( mixed $class [, bool $autoload = true ] )
	class - Объект (экземпляр класса) или строка (имя класса).
	autoload - В зависимости от переданного значения фунция может загрузить описание класса автоматически магическим методом __autoload().
	Возвращаемые значения: В случае успеха будет возвращен массив. В случае ошибки - FALSE.
				
class_uses — Возвращает список трэйтов, используемых заданным классом
Эта функция возвращает массив с именами трэйтов, которые использует заданный класс class. В этот массив, однако, не попадут трэйты, используемые в классах-родителях.
*/

trait Foo1 { }
trait Foo2 { }
class Bar {
  use Foo1, Foo2;
}

print_r(class_uses(new Bar));

print_r(class_uses('Bar'));
/*
function __autoload($class_name) {
   require_once $class_name . '.php';
}
// использование __autoload для загрузки еще незагруженного класса 'not_loaded'
print_r(class_uses('not_loaded', true));
*/